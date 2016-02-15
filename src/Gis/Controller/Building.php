<?php

namespace Gis\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;

class Building implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->get('/{id}/company-list', function ($id) use ($app) {
            $companyIds = $app['building']->getCompanyIds($id);
            $companyList = $app['company']->getByIds($companyIds);
            return $app->json(array(
                'code' => 200,
                'data' => $companyList,
            ));
        })
        ->assert('id', '\d+');

        $controllers->get('/list', function () use ($app) {
            $data = $app['building']->getList();
            return $app->json(array(
                'code' => 200,
                'data' => $data,
            ));
        });

        return $controllers;
    }
}
