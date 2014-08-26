<?php
use Tricolore\Autoloader;
use Tricolore\Application;

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . 
    DIRECTORY_SEPARATOR . '..' . 
    DIRECTORY_SEPARATOR . 'Autoloader.php';

Autoloader::register();

Application::register([
    'directory' => __DIR__ . '/../../../..' . DIRECTORY_SEPARATOR,
    'environment' => 'test'
]);
