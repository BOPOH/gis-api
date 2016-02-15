<?php

namespace Gis\Service;

use Gis\Service\Storage\BuildingInterface as BuildingStorage;
use Silex\Application;

class Building
{
    public function __construct(Application $app, BuildingStorage $storage)
    {
        $this->app = $app;
        $this->storage = $storage;
    }

    /**
     * Gets building list
     *
     * @return array
     */
    public function getList()
    {
        $buildingList = $this->storage->getList();
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
        $companyIdsRows = $this->storage->getCompanyIds($id);

        return array_map(function ($companyRow) {
            return $companyRow['companyId'];
        }, $companyIdsRows);
    }
}
