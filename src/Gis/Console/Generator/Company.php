<?php

/**
 * @namespace
 */
namespace Gis\Console\Generator;

use Silex\Application;

class Company
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

    public function generate($addressesIds, $rubricsIds, $companyCount = 20)
    {
        $companyIds = array();
        for ($i = 0; $i < $companyCount; $i++) {
            $companyIds[] = $this->generateCompany($i, $addressesIds, $rubricsIds);
        }
        return $companyIds;
    }

    protected function generateCompany($companyIndex, $addressesIds, $rubricsIds)
    {
        if ($this->isDebug) {
            return $companyIndex;
        }

        $companyName = $this->getCompanyName($companyIndex);

        $data = array(
            'f_name' => $companyName,
        );
        $this->app['db']->insert('t_company', $data);
        $companyId = $this->app['db']->lastInsertId();

        $this->generatePhoneList($companyId);
        return $companyId;
    }

    protected function generatePhoneList($companyId)
    {
        $phoneCount = rand(0, 3);
        if (!$phoneCount) {
            return;
        }

        for ($i = 0; $i < $phoneCount; $i++) {
            $data = array(
                'f_company_id' => $companyId,
                'f_phone' => $this->getPhone($i, $companyId),
            );
            $this->app['db']->insert('t_company_phones', $data);
        }
    }

    protected function getPhone($companyIndex, $companyId)
    {
        return rand(100, 999) . '-' . rand(100, 999) . '-' . rand(100, 999);
    }

    protected function getCompanyName($companyIndex)
    {
        $companyNameList = array(
            'Магазин Спорта',
            'Бутик Одежды',
            'Лечебница Кошек',
            'Беговые Собаки',
            'Вкусная Еда',
            'Невкусное Мясо',
            'Театр',
            'Сауна',
            'АЗС',
            'СТО',
        );
        return $companyNameList[array_rand($companyNameList)] . " №{$companyIndex}";
    }
}
