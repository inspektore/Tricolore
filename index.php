<?php
use Tricolore\Foundation\Application;

require __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
require __DIR__ . DIRECTORY_SEPARATOR . '.helpers.php';

Application::register([
    'directory' => __DIR__ . DIRECTORY_SEPARATOR,
    'environment' => 'dev',
    'version' => '0.1.1'
]);
