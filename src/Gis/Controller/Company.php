<?php

namespace Gis\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;

class Company implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->get('/{id}', function ($id) use ($app) {
            $company = $app['company']->getById($id);
            return $app->json(array(
                'code' => 200,
                'data' => $company,
            ));
        })
        ->assert('id', '\d+');

        $controllers->get('/by-name/{name}', function ($name) use ($app) {
            $company = $app['company']->getByName($name);
            return $app->json(array(
                'code' => 200,
                'data' => $company,
            ));
        });

        $controllers->get('/by-ids', function (Request $request) use ($app) {
            $ids = explode(',', $request->query->get('ids'));
            $companyList = $app['company']->getByIds($ids);
            return $app->json(array(
                'code' => 200,
                'data' => $companyList,
            ));
        });

        $controllers->get('/by-area', function (Request $request) use ($app) {
            $latitude = $request->query->get('latitude');
            $longitude = $request->query->get('longitude');
            $radius = $request->query->get('radius');
            if (!$latitude || !$longitude || !$radius) {
                throw new \Gis\Exception('Wrong params', 404);
            }

            $companyList = $app['company']->getByArea($latitude, $longitude, $radius);
            return $app->json(array(
                'code' => 200,
                'data' => $companyList,
            ));
        });

        $controllers->get('/by-buildings', function (Request $request) use ($app) {
            $ids = explode(',', $request->query->get('ids'));
            $companyList = $app['company']->getByBuildings($ids);
            return $app->json(array(
                'code' => 200,
                'data' => $companyList,
            ));
        });

        $controllers->get('/by-rubrics', function (Request $request) use ($app) {
            $ids = explode(',', $request->query->get('ids'));
            $companyList = $app['company']->getByRubricIds($ids);
            return $app->json(array(
                'code' => 200,
                'data' => $companyList,
            ));
        });

        return $controllers;
    }
}
