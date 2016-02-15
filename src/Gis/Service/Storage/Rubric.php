<?php

namespace Gis\Service\Storage;

use Silex\Application;
use Doctrine\DBAL\Connection;

class Rubric implements RubricInterface
{
    protected $app = null;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Gets rubric list
     * Applies a filter, if specified
     *
     * @param  string $nameFilter
     * @return array
     */
    public function getList($nameFilter = '')
    {
        $sql = "
SELECT *
FROM `t_rubric`
        ";

        $rubricIds = array();
        if ($nameFilter) {
            $rubricIds = $this->getIdsByFilter($nameFilter);
            $sql .= '
WHERE `f_id` IN (
    SELECT `t_rubric_global`.`f_parent_id`
    FROM `t_rubric_global`

    INNER JOIN `t_rubric`
    ON `t_rubric`.`f_id` = `t_rubric_global`.`f_rubric_id`

    WHERE `f_id` IN (?)
) OR `f_id` IN (?)
            ';
        }

        $rubricListRows = $this->app['db']->fetchAll($sql, array($rubricIds, $rubricIds), array(Connection::PARAM_INT_ARRAY, Connection::PARAM_INT_ARRAY));
        $rubricListRows = $rubricListRows ?: array();
        $rubricList = array_map(array($this, 'toRubric'), $rubricListRows);
        return $rubricList;
    }

    /**
     * Finds companies ids in the given rubric
     *
     * @param  integer $id
     * @return array
     */
    public function getCompanyIds($id)
    {
        $sql = "SELECT `f_company_id` FROM `t_company_rubric` WHERE `f_rubric_id` = ?";
        $companyIdsRows = $this->app['db']->fetchAll($sql, array((int) $id));
        $companyIdsRows = $companyIdsRows ?: array();
        $companyIds = array_map(array($this, 'toCompanyId'), $companyIdsRows);
        return $companyIds;
    }

    /**
     * Converts DB row to the rubric info
     *
     * @param  array $rubricRow
     * @return array
     */
    protected function toRubric($rubricRow)
    {
        return array(
            'id' => $rubricRow['f_id'],
            'parentId' => $rubricRow['f_parent_id'],
            'name' => $rubricRow['f_name'],
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
     * Finds runrics ids by name filter
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
        $result = $sphinx->Query("*{$nameFilter}*", $sphinxConfig['index.rubric']);

        $rubricIds = array();
        if ($result === false) {
            return $rubricIds;
        }

        if (!empty($result["matches"])) {
            foreach ($result["matches"] as $docId => $docInfo) {
                $rubricIds[] = $docId;
            }
        }
        return $rubricIds;
    }
}
