<?php
namespace Tricolore\DataSources;

use Tricolore\Application;
use Tricolore\Config\Config;
use Tricolore\Exception\LogicException;

class DatabaseAdapter
{
    /**
     * Get database factory
     * 
     * @param array $custom_config
     * @throws Tricolore\Exception\LogicException
     * @return Tricolore\DataSources\PostgreSQL\DatabaseFactory
     */
    public function getDatabaseFactory(array $custom_config = [])
    {
        if (count($custom_config)) {
            $database_config = $custom_config;
        } else {
            $database_config = Config::all('Database'); 
        }

        $allowed_adapters = ['PostgreSQL'];

        if (in_array($database_config['adapter'], $allowed_adapters, true) === false) {
            throw new LogicException(
                sprintf('Adapter "%s" is not supported. Known adapters: %s', $database_config['adapter'], implode(', ', $allowed_adapters)));
        }

        $driver_class = __NAMESPACE__ . '\\' . $database_config['adapter'] . '\\DatabaseFactory';
        $driver_class = new $driver_class;

        $config_key = (Application::getInstance()->inTravis() === true) ? 'travis' : 'default';

        return $driver_class->connection($database_config[Application::getInstance()->getEnv()][$config_key]);
    }
}
