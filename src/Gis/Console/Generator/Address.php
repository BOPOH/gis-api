<?php

/**
 * @namespace
 */
namespace Gis\Console\Generator;

use Silex\Application;

class Address
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
        $this->app['db']->delete('t_building');
    }

    public function generate($addressCount = 20)
    {
        $addressesIds = array();
        for ($i = 0; $i < $addressCount; $i++) {
            $addressesIds[] = $this->generateAddress($i);
        }
        return $addressesIds;
    }

    protected function generateAddress($addressIndex)
    {
        if ($this->isDebug) {
            return $addressIndex;
        }

        $streetName = $this->getStreetName($addressIndex);
        $buildingNumber = $this->getBuildingNumber($addressIndex);
        $address = sprintf('%s %s', $streetName, $buildingNumber);

        $data = array(
            'f_address' => $address,
            'f_latitude' => rand(-200, 200),
            'f_longitude' => rand(-200, 200),
        );
        $this->app['db']->insert('t_building', $data);
        return $this->app['db']->lastInsertId();
    }

    protected function getStreetName($addressIndex)
    {
        $streetNameList = array(
            'ул. Ленина',
            'ул. К. Маркса',
            'ул. Гагарина',
            'ул. Восточная',
            'ул. Северная',
            'ул. Западная',
            'ул. Южная',
            'ул. Российская',
            'ул. Загадочная',
            'ул. Красивая',
            'ул. Счастливая',
            'ул. Плаксивая',
        );
        return $streetNameList[array_rand($streetNameList)];
    }

    protected function getBuildingNumber($addressIndex)
    {
        $addressIndex++;
        return rand(1, $addressIndex);
    }
}
