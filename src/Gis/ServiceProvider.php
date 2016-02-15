<?php

/**
 * @namespace
 */
namespace Gis;

use Gis\Service\Storage\Company as CompanyStorage;
use Gis\Service\Storage\Building as BuildingStorage;
use Gis\Service\Storage\Rubric as RubricStorage;
use Gis\Controller\Company as CompanyController;
use Gis\Controller\Building as BuildingController;
use Gis\Controller\Rubric as RubricController;

use Silex\Application;
use Silex\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['company'] = $app->share(function () use ($app) {
            $storage = new CompanyStorage($app);
            return new Service\Company($app, $storage);
        });
        $app['building'] = $app->share(function () use ($app) {
            $storage = new BuildingStorage($app);
            return new Service\Building($app, $storage);
        });
        $app['rubric'] = $app->share(function () use ($app) {
            $storage = new RubricStorage($app);
            return new Service\Rubric($app, $storage);
        });

        $app->mount('/company', new CompanyController());
        $app->mount('/building', new BuildingController());
        $app->mount('/rubric', new RubricController());
    }
    
    public function boot(Application $app)
    {
    }
}
