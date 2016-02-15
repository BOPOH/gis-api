<?php

namespace Gis\Service;

use Gis\Service\Storage\CompanyInterface as CompanyStorage;
use Silex\Application;

class Company
{
    public function __construct(Application $app, CompanyStorage $storage)
    {
        $this->app = $app;
        $this->storage = $storage;
    }

    /**
     * Finds company by given ID
     *
     * @param  integer $id
     * @return array
     */
    public function getById($id)
    {
        $company = $this->storage->getById($id);
        return $company;
    }

    /**
     * Finds companies by IDs
     *
     * @param  array<integer> $companyIds
     * @return array
     */
    public function getByIds($companyIds)
    {
        $companyList = $this->storage->getByIds($companyIds);
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
        $companyList = $this->storage->getByName($name);
        return $companyList;
    }

    /**
     * Finds companies by given area
     *
     * @param  float $latitude
     * @param  float $longitude
     * @param  float $radius
     * @return array
     */
    public function getByArea($latitude, $longitude, $radius)
    {
        $companyIdsRows = $this->storage->getIdsByArea($latitude, $longitude, $radius);
        $companyIds = array_map(function ($companyRow) {
            return $companyRow['companyId'];
        }, $companyIdsRows);
        return $this->getByIds($companyIds);
    }

    /**
     * Finds companies by building IDs
     *
     * @param  array<integer> $buildingIds
     * @return array
     */
    public function getByBuildings($buildingIds)
    {
        $companyList = $this->storage->getByBuildingIds($buildingIds);
        return $companyList;
    }

    /**
     * Finds companies by rubric IDs
     *
     * @param  array<integer> $rubricIds
     * @return array
     */
    public function getByRubricIds($rubricIds)
    {
        $companyList = $this->storage->getByBuildingIds($rubricIds);
        return $companyList;
    }
}
