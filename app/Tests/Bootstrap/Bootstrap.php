<?php
@error_reporting(E_ALL);
@date_default_timezone_set('UTC');

use Tricolore\Application;

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . 
    DIRECTORY_SEPARATOR . '..' . 
    DIRECTORY_SEPARATOR . '..' .
    DIRECTORY_SEPARATOR . 'vendor' .
    DIRECTORY_SEPARATOR . 'autoload.php';

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' .
    DIRECTORY_SEPARATOR . '..' . 
    DIRECTORY_SEPARATOR . '..' . 
    DIRECTORY_SEPARATOR . '.helpers.php';

Application::register([
    'directory' => __DIR__ . '/../../..' . DIRECTORY_SEPARATOR,
    'environment' => 'test'
]);
