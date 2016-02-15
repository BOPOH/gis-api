<?php

namespace Gis\Service\Storage;

use Silex\Application;

class Building implements BuildingInterface
{
    protected $app = null;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Gets building list
     *
     * @return array
     */
    public function getList()
    {
        $sql = "SELECT `f_id`, `f_address`, `f_latitude`, `f_longitude` FROM `t_building`";
        $buildingListRows = $this->app['db']->fetchAll($sql);
        $buildingListRows = $buildingListRows ?: array();
        $buildingList = array_map(array($this, 'toBuilding'), $buildingListRows);
        return $buildingList;
    }

    /**
     * Finds companies in the given building
     *
     * @param  integer $id
     * @return array
     */
    public function getCompanyIds($id)
    {
        $sql = "SELECT `f_company_id` FROM `t_company_address` WHERE `f_building_id` = ?";
        $companyIdsRows = $this->app['db']->fetchAll($sql, array((int) $id));
        $companyIdsRows = $companyIdsRows ?: array();
        $companyIds = array_map(array($this, 'toCompanyId'), $companyIdsRows);
        return $companyIds;
    }

    /**
     * Converts DB row to the building info
     *
     * @param  array $buildingRow
     * @return array
     */
    protected function toBuilding($buildingRow)
    {
        return array(
            'id' => $buildingRow['f_id'],
            'address' => $buildingRow['f_address'],
            'latitude' => $buildingRow['f_latitude'],
            'longitude' => $buildingRow['f_longitude'],
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
}
