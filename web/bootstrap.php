<?php

defined('SRC_DIR') || define('SRC_DIR', __DIR__ . '/../src');
defined('VENDOR_DIR') || define('VENDOR_DIR', __DIR__ . '/../vendor');

$loader = require VENDOR_DIR . '/autoload.php';
$loader->add(null, SRC_DIR);

return $loader;
