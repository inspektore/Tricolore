<?php
if (file_exists(__DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php') === false) {
    echo 'You must set up the project dependencies. 
        <a href="https://github.com/Macsch15/Tricolore#downloading-and-installing-dependencies">Help</a>';

    exit(1);
}

use Tricolore\Application;

require __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
require __DIR__ . DIRECTORY_SEPARATOR . '.helpers.php';

Application::register([
    'directory' => __DIR__ . DIRECTORY_SEPARATOR,
    'environment' => 'dev',
    'version' => '0.1'
]);
