<?php

defined('SRC_DIR') || define('SRC_DIR', __DIR__ . '/../src');
defined('VENDOR_DIR') || define('VENDOR_DIR', __DIR__ . '/../vendor');
defined('CONFIG_DIR') || define('CONFIG_DIR', __DIR__ . '/../config');

$loader = require __DIR__ . '/bootstrap.php';

use Gis\ServiceProvider;

use Silex\Provider\DoctrineServiceProvider;

$app = new Silex\Application();
$app['composer'] = $loader;
$app['config'] = include_once CONFIG_DIR . '/config.php';

$appConfig = $app['config'];
$dbConfig = $appConfig['db.options'];
$app->register(new DoctrineServiceProvider(), array(
    'db.options' => array (
        'driver'    => 'pdo_mysql',
        'host'      => $dbConfig['host'],
        'dbname'    => $dbConfig['database'],
        'user'      => $dbConfig['user'],
        'password'  => $dbConfig['password'],
        'charset'   => 'utf8mb4',
    ),
));

$app->register(new ServiceProvider());

$app->error(function (\Exception $e, $code) use ($app) {
    $message = 'Unknown error is occurs. Please try again later';
    if ($e instanceof Gis\Exception) {
        $code = $e->getCode();
        $message = $e->getMessage();
    }
    return $app->json(array('code' => $code, 'error' => $message));
});

return $app;
