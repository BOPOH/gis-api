<?php

namespace Gis\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;

class Rubric implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->get('/list', function (Request $request) use ($app) {
            $nameFilter = $request->query->get('name');
            $rubricList = $app['rubric']->getList($nameFilter);
            return $app->json(array(
                'code' => 200,
                'data' => $rubricList,
            ));
        });

        $controllers->get('/{id}/company-list', function ($id) use ($app) {
            $companyIds = $app['rubric']->getCompanyIds($id);
            $companyList = $app['company']->getByIds($companyIds);
            return $app->json(array(
                'code' => 200,
                'data' => $companyList,
            ));
        })
        ->assert('id', '\d+');

        return $controllers;
    }
}
