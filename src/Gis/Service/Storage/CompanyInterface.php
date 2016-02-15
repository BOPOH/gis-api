<?php

namespace Gis\Service\Storage;

interface CompanyInterface
{
    /**
     * Finds company by given ID
     *
     * @param  integer $id
     * @return array
     */
    public function getById($id);

    /**
     * Finds companies by IDs
     *
     * @param  array<integer> $companyIds
     * @return array
     */
    public function getByIds(array $companyIds);

    /**
     * Finds companies by rubric IDs
     *
     * @param  array<integer> $rubricIds
     * @return array
     */
    public function getByRubricIds(array $rubricIds);

    /**
     * Finds companies by building IDs
     *
     * @param  array<integer> $buildingIds
     * @return array
     */
    public function getByBuildingIds(array $buildingIds);

    /**
     * Finds companies by name filter
     *
     * @param  string $name
     * @return array
     */
    public function getByName($name);

    /**
     * Finds companies by given area
     *
     * @param  float $latitude
     * @param  float $longitude
     * @param  float $radius
     * @return array
     */
    public function getIdsByArea($latitude, $longitude, $radius);
}
