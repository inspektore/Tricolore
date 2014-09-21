<?php
namespace Tricolore\DataSources;

use Tricolore\Application;
use Tricolore\Exception\DatabaseException;
use Symfony\Component\Yaml\Yaml;

class DatabaseDriver
{
    /**
     * Get database factory
     * 
     * @throws Tricolore\Exception\DatabaseException
     * @return Tricolore\DataSources\PostgreSQL\DatabaseFactory
     */
    public function getDatabaseFactory()
    {
        $database_config = Yaml::parse(Application::createPath('library:Tricolore:Config:Resources:Database.yml'));

        $allowed_drivers = ['PostgreSQL'];

        if(in_array($database_config['driver'], $allowed_drivers, true) === false) {
            throw new DatabaseException(
                sprintf('Driver "%s" not found. Known drivers: %s', $database_config['driver'], implode(', ', $allowed_drivers)));
        }

        $driver_class = __NAMESPACE__ . '\\' . $database_config['driver'] . '\\DatabaseFactory';
        $driver_class = new $driver_class;

        unset($database_config['driver']);

        return $driver_class->connection($database_config);
    }
}
