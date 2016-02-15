<?php

namespace Gis\Service\Storage;

interface RubricInterface
{
    /**
     * Gets rubric list
     * Applies a filter, if specified
     *
     * @param  string $nameFilter
     * @return array
     */
    public function getList($nameFilter = '');

    /**
     * Finds companies ids in the given rubric
     *
     * @param  integer $id
     * @return array
     */
    public function getCompanyIds($id);
}
