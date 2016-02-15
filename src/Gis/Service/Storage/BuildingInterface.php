<?php

namespace Gis\Service\Storage;

interface BuildingInterface
{
    /**
     * Gets building list
     *
     * @return array
     */
    public function getList();

    /**
     * Finds companies in the given building
     *
     * @param  integer $id
     * @return array
     */
    public function getCompanyIds($id);
}
