<?php

namespace Gis\Service;

use Gis\Service\Storage\RubricInterface as RubricStorage;
use Silex\Application;

class Rubric
{
    public function __construct(Application $app, RubricStorage $storage)
    {
        $this->app = $app;
        $this->storage = $storage;
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
        $rubricListFlat = $this->storage->getList($nameFilter);

        $rubricList = array();
        $rubricChildren = array();
        foreach ($rubricListFlat as $rubric) {
            $id = $rubric['id'];
            $parentId = $rubric['parentId'];

            $rubricChildren[$id] = array();
            if (!$parentId) {
                $rubricList[$id] = $rubric + array(
                    'children' => &$rubricChildren[$id],
                );
            } else {
                if (!isset($rubricChildren[$parentId])) {
                    $rubricChildren[$parentId] = array();
                }
                $rubricChildren[$parentId][$id] = $rubric + array(
                    'children' => &$rubricChildren[$id],
                );
            }
        }

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
        $companyIdsRows = $this->storage->getCompanyIds($id);
        return array_map(function ($companyRow) {
            return $companyRow['companyId'];
        }, $companyIdsRows);
    }
}
