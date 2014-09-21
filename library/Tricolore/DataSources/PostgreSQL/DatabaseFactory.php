<?php
namespace Tricolore\DataSources\PostgreSQL;

use Tricolore\Exception\DatabaseException;
use Tricolore\Exception\InvalidArgumentException;

class DatabaseFactory
{
    /**
     * PDO instance
     * 
     * @var \PDO
     */
    private $pdo;

    /**
     * Table prefix
     * 
     * @var string
     */
    private $table_prefix;

    /**
     * Connection
     * 
     * @param array $config
     * @throws Tricolore\Exception\DatabaseException
     * @return Tricolore\DataSources\PostgreSQL\DatabaseFactory
     */
    public function connection(array $config)
    {
        if(isset($config['server']) === false) {
            throw new DatabaseException('Missing config key: driver');
        }

        if(isset($config['port']) === false) {
            throw new DatabaseException('Missing config key: port');
        }

        if(isset($config['database_name']) === false) {
            throw new DatabaseException('Missing config key: database_name');
        }

        if(isset($config['username']) === false) {
            throw new DatabaseException('Missing config key: username');
        }

        if(isset($config['password']) === false) {
            throw new DatabaseException('Missing config key: driver');
        }

        if(isset($config['table_prefix']) === false) {
            $config['table_prefix'] = null;
        }

        $dsn = sprintf('pgsql:host=%s;port=%d;dbname=%s', $config['server'], $config['port'], $config['database_name']);

        try {
            $this->pdo = new \PDO($dsn, $config['username'], $config['password']);
            $this->table_prefix = $config['table_prefix'];
        } catch(\PDOException $exception) {
            throw new DatabaseException($exception->getMessage());
        }

        return $this;
    }

    /**
     * Build query
     * 
     * @param string $type
     * @throws Tricolore\Exception\InvalidArgumentException
     * @return object
     */
    public function buildQuery($type)
    {
        $type = ucfirst($type);

        $allowed_types = ['Select'];

        if(in_array($type, $allowed_types, true) === false) {
            throw new InvalidArgumentException(
                sprintf('Type "%s" is not allowed. Known types: %s', $type, implode(', ', $allowed_types)));
        }

        $class_type = __NAMESPACE__ . '\\Query\\' . $type;

        return new $class_type($this->pdo, $this->table_prefix);
    }
}
