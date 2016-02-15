<?php

namespace Gis\Service\Storage;

use Silex\Application;
use Doctrine\DBAL\Connection;

class Company implements CompanyInterface
{
    protected $app = null;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Finds company by given ID
     *
     * @param  integer $id
     * @return array
     */
    public function getById($id)
    {
        $companyList = $this->getByIds(array($id));
        return count($companyList) ? $companyList[0] : array();
    }

    /**
     * Finds companies by IDs
     *
     * @param  array<integer> $companyIds
     * @return array
     */
    public function getByIds(array $companyIds)
    {
        $sql = "
SELECT
  `t_company`.*,
   GROUP_CONCAT(`t_company_phones`.`f_phone`) AS `f_phone_list`
FROM `t_company`

LEFT JOIN `t_company_phones`
ON `t_company`.`f_id` = `t_company_phones`.`f_company_id`

WHERE `t_company`.`f_id` IN (?)
GROUP BY `t_company`.`f_id`
        ";
        $companyListRows = $this->app['db']->fetchAll($sql, array($companyIds), array(Connection::PARAM_INT_ARRAY));
        $companyListRows = $companyListRows ?: array();
        $companyList = array_map(array($this, 'toCompany'), $companyListRows);
        return $companyList;
    }

    /**
     * Finds companies by rubric IDs
     *
     * @param  array<integer> $rubricIds
     * @return array
     */
    public function getByRubricIds(array $rubricIds)
    {
        $sql = "
SELECT
  `t_company`.*,
   GROUP_CONCAT(`t_company_phones`.`f_phone`) AS `f_phone_list`
FROM `t_company`

LEFT JOIN `t_company_phones`
ON `t_company`.`f_id` = `t_company_phones`.`f_company_id`

WHERE `t_company`.`f_id` IN (
  SELECT `t_company_rubric`.`f_company_id`
  FROM `t_company_rubric`
  WHERE `t_company_rubric`.`f_rubric_id` IN (?)
)
GROUP BY `t_company`.`f_id`
        ";
        $companyListRows = $this->app['db']->fetchAll($sql, array($rubricIds), array(Connection::PARAM_INT_ARRAY));
        $companyListRows = $companyListRows ?: array();
        $companyList = array_map(array($this, 'toCompany'), $companyListRows);
        return $companyList;
    }

    /**
     * Finds companies by building IDs
     *
     * @param  array<integer> $buildingIds
     * @return array
     */
    public function getByBuildingIds(array $buildingIds)
    {
        $sql = "
SELECT
  `t_company`.*,
   GROUP_CONCAT(`t_company_phones`.`f_phone`) AS `f_phone_list`
FROM `t_company`

LEFT JOIN `t_company_phones`
ON `t_company`.`f_id` = `t_company_phones`.`f_company_id`

WHERE `t_company`.`f_id` IN (
  SELECT `t_company_address`.`f_company_id`
  FROM `t_company_address`
  WHERE `t_company_address`.`f_building_id` IN (?)
)
GROUP BY `t_company`.`f_id`
        ";
        $companyListRows = $this->app['db']->fetchAll($sql, array($buildingIds), array(Connection::PARAM_INT_ARRAY));
        $companyListRows = $companyListRows ?: array();
        $companyList = array_map(array($this, 'toCompany'), $companyListRows);
        return $companyList;
    }

    /**
     * Finds companies by name filter
     *
     * @param  string $name
     * @return array
     */
    public function getByName($name)
    {
        $companyIds = $this->getIdsByFilter($name);
        return $this->getByIds($companyIds);
    }

    /**
     * Finds companies by given area
     *
     * @param  float $latitude
     * @param  float $longitude
     * @param  float $radius
     * @return array
     */
    public function getIdsByArea($latitude, $longitude, $radius)
    {
        $minLatitude = $latitude - $radius;
        $maxLatitude = $latitude + $radius;
        $minLongitude = $longitude - $radius;
        $maxLongitude = $longitude + $radius;

        $sql = "
SELECT
  `f_company_id`,
  (
    6371 * acos(
      cos( :lat ) * cos( `f_latitude` ) * cos( `f_longitude` - :long ) + sin( :lat ) * sin( `f_latitude` )
    )
  ) AS `distance`
FROM `t_company_address`

INNER JOIN `t_building`
ON `t_building`.`f_id` = `t_company_address`.`f_building_id`

WHERE
  `t_building`.`f_latitude` BETWEEN :minLat AND :maxLat
  AND `t_building`.`f_longitude` BETWEEN :minLong AND :maxLong
HAVING
  `distance` < :distance
        ";
        $statement = $this->app['db']->prepare($sql);
        $statement->bindValue("minLat", $minLatitude);
        $statement->bindValue("maxLat", $maxLatitude);
        $statement->bindValue("minLong", $minLongitude);
        $statement->bindValue("maxLong", $maxLongitude);
        $statement->bindValue("lat", $latitude);
        $statement->bindValue("long", $longitude);
        $statement->bindValue("distance", $radius);
        $statement->execute();

        $companyIdsRows = $statement->fetchAll();
        $companyIdsRows = $companyIdsRows ?: array();
        $companyIds = array_map(array($this, 'toCompanyId'), $companyIdsRows);
        return $companyIds;
    }

    /**
     * Converts DB row to the company info
     *
     * @param  array $companyRow
     * @return array
     */
    protected function toCompany($companyRow)
    {
        return array(
            'id' => $companyRow['f_id'],
            'name' => $companyRow['f_name'],
            'phoneList' => $companyRow['f_phone_list'] ? explode(',', $companyRow['f_phone_list']) : array(),
        );
    }

    /**
     * Converts DB row to the company id info
     *
     * @param  array $companyIdRow
     * @return array
     */
    protected function toCompanyId($companyIdRow)
    {
        return array(
            'companyId' => $companyIdRow['f_company_id'],
        );
    }

    /**
     * Finds companies ids by name filter
     *
     * @param  string $name
     * @return array
     */
    protected function getIdsByFilter($nameFilter)
    {
        $appConfig = $this->app['config'];
        $sphinxConfig = $appConfig['sphinx.options'];
        $sphinx = new \SphinxClient();
        $sphinx->SetServer($sphinxConfig['host'], $sphinxConfig['port']);
        $sphinx->SetMatchMode(SPH_MATCH_ANY);
        $result = $sphinx->Query("*{$nameFilter}*", $sphinxConfig['index.company']);

        $companyIds = array();
        if ($result === false) {
            return $companyIds;
        }

        if (!empty($result["matches"])) {
            foreach ($result["matches"] as $docId => $docInfo) {
                $companyIds[] = $docId;
            }
        }
        return $companyIds;
    }
}
