<?php

/**
 * @namespace
 */
namespace Gis\Console\Generator;

use Silex\Application;

class Rubric
{
    protected $app;
    protected $isDebug = false;

    public function __construct(Application $app, $eraseOldData, $isDebug = false)
    {
        $this->app = $app;
        $this->isDebug = $isDebug;
        if ($eraseOldData && !$isDebug) {
            $this->clearData();
        }
    }

    public function clearData()
    {
        $this->app['db']->delete('t_rubric');
    }

    public function generate($rubricCount = 20)
    {
        $rubricId = null;
        $rubricIds = array();
        for ($i = 0; $i < $rubricCount; $i++) {
            $rubricId = $this->generateRubric($i, $rubricId);
            $rubricIds[] = $rubricId;
        }
        return $rubricIds;
    }

    protected function generateRubric($rubricIndex, $oldRubricId)
    {
        if ($this->isDebug) {
            return $rubricIndex;
        }

        $rubricName = $this->getRubricName($rubricIndex);

        $data = array(
            'f_name' => $rubricName,
            'f_parent_id' => (($rubricIndex + $oldRubricId) % 3 == 0) ? $oldRubricId : null,
        );
        $this->app['db']->insert('t_rubric', $data);
        return $this->app['db']->lastInsertId();
    }

    protected function getRubricName($rubricIndex)
    {
        $rubricNameList = array(
            'Спорт',
            'Одежда',
            'Кошки',
            'Собаки',
            'Еда',
            'Мясо',
            'Лыжи',
            'Театры',
            'Отдых',
            'Машины',
            'СТО',
        );
        return $rubricNameList[array_rand($rubricNameList)] . " №{$rubricIndex}";
    }
}
