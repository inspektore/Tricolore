<?php
use Tricolore\Foundation\Application;

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
