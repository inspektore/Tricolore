<?php
use Tricolore\Autoloader;
use Tricolore\Application;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'library' . 
    DIRECTORY_SEPARATOR . 'Tricolore' . 
    DIRECTORY_SEPARATOR . 'Autoloader.php';

require_once __DIR__ . DIRECTORY_SEPARATOR . '.helpers.php';

Autoloader::register();

Application::register([
    'directory' => __DIR__ . DIRECTORY_SEPARATOR,
    'environment' => 'dev',
    'version' => '0.1 pre-alpha'
]);