<?php
use Tricolore\Autoloader;
use Tricolore\Application;

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . 
    DIRECTORY_SEPARATOR . '..' . 
    DIRECTORY_SEPARATOR . 'Autoloader.php';

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' .
    DIRECTORY_SEPARATOR . '..' .
    DIRECTORY_SEPARATOR . '..' . 
    DIRECTORY_SEPARATOR . '..' . 
    DIRECTORY_SEPARATOR . '.helpers.php';

if(defined('HHVM_VERSION')) {
    @ini_set('hhvm.libxml.ext_entity_whitelist', 'file,http');
}

Autoloader::register();

Application::register([
    'directory' => __DIR__ . '/../../../..' . DIRECTORY_SEPARATOR,
    'environment' => 'test'
]);
