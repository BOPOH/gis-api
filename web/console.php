<?php

use Gis\Console\GenerateCommand;
use Knp\Provider\ConsoleServiceProvider;

$app = require_once __DIR__ . '/app.php';
$app->register(new ConsoleServiceProvider(), array(
    'console.name' => 'ConsoleApp',
    'console.version' => '1.0.0',
    'console.project_directory' => __DIR__ . '/..'
));

$application = $app['console'];

$application->add(new GenerateCommand());
$application->run();
