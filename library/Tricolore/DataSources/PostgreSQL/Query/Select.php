<?php
namespace Tricolore\DataSources\PostgreSQL\Query;

use Tricolore\Exception\DatabaseException;

class Select
{
    /**
     * PDO instance
     * 
     * @var \PDO
     */
    private $pdo;

    /**
     * Collection
     * 
     * @var array
     */
    private $collection = [];

    /**
     * Table prefix
     * 
     * @var string
     */
    private $table_prefix;

    /**
     * Construct
     * 
     * @param \PDO $pdo
     * @param string $table_prefix
     * @return void
     */
    public function __construct(\PDO $pdo, $table_prefix)
    {
        $this->pdo = $pdo;
        $this->table_prefix = $table_prefix;
    }

    /**
     * Select
     * 
     * @param mixed $select 
     * @return Tricolore\DataSources\PostgreSQL\DatabaseFactory
     */
    public function select($select)
    {
        $this->collection['select'] = $select;

        return $this;
    }

    /**
     * From
     * 
     * @param string $from 
     * @return Tricolore\DataSources\PostgreSQL\DatabaseFactory
     */
    public function from($from)
    {
        $this->collection['from'] = $this->table_prefix . $from;

        return $this;
    }

    /**
     * Left join
     * 
     * @param string $left_join 
     * @param string $on
     * @return Tricolore\DataSources\PostgreSQL\DatabaseFactory
     */
    public function leftJoin($left_join, $on)
    {
        $this->collection['left_join'] = $left_join;
        $this->collection['left_join_on'] = $on;

        return $this;
    }

    /**
     * Where
     * 
     * @param string $where
     * @param array $binding
     * @return Tricolore\DataSources\PostgreSQL\DatabaseFactory
     */
    public function where($where, array $binding = [])
    {
        $this->collection['where'] = $where;
        $this->collection['where_binding'] = $binding;

        return $this;
    }

    /**
     * Group by
     * 
     * @param string $group_by
     * @return Tricolore\DataSources\PostgreSQL\DatabaseFactory
     */
    public function groupBy($group_by)
    {
        $this->collection['group_by'] = $group_by;

        return $this;
    }

    /**
     * Order by
     * 
     * @param string $order_by
     * @param string $sorting
     * @return Tricolore\DataSources\PostgreSQL\DatabaseFactory
     */
    public function orderBy($order_by, $sorting = 'ASC')
    {
        $this->collection['order_by'] = $order_by;

        $sorting = strtoupper($sorting);

        if(in_array($sorting, ['ASC', 'DESC'], true) === false) {
            $this->collection['order_by_sorting'] = 'ASC';
        } else {
            $this->collection['order_by_sorting'] = $sorting;
        }

        return $this;
    }

    /**
     * Max results
     * 
     * @param int $limit 
     * @return Tricolore\DataSources\PostgreSQL\DatabaseFactory
     */
    public function maxResults($limit)
    {
        $this->collection['limit'] = $limit;

        return $this;
    }

    /**
     * Execute
     * 
     * @throws Tricolore\Exception\DatabaseException
     * @return array
     */
    public function execute()
    {
        if(isset($this->collection['select']) === false) {
            throw new DatabaseException('"Select" in query is required. Add select() method to your query builder.');
        }

        if(isset($this->collection['from']) === false) {
            throw new DatabaseException('"From" in query is required. Add from() method to your query builder.');
        }

        if(is_array($this->collection['select']) === true) {
            $this->collection['select'] = implode(',', $this->collection['select']);
        }

        if(is_array($this->collection['from']) === true) {
            $this->collection['from'] = implode(',', $this->collection['from']);
        }

        $query = 'select ' . $this->collection['select'];
        $query .= ' from ' . $this->collection['from'];

        if(isset($this->collection['left_join']) === true 
            && isset($this->collection['left_join_on']) === true
        ) {
            $query .= ' left join ' . $this->collection['left_join'] . ' on ' . $this->collection['left_join_on'];
        }

        if(isset($this->collection['where']) === true) {
            $query .= ' where ' . $this->collection['where'];            
        }

        if(isset($this->collection['group_by']) === true) {
            $query .= ' group by ' . $this->collection['group_by'];
        }

        if(isset($this->collection['order_by']) === true) {
            if(is_array($this->collection['order_by']) === true) {
                $this->collection['order_by'] = implode(',', $this->collection['order_by']);
            }

            $query .= ' order by ' . $this->collection['order_by'] . ' ' . $this->collection['order_by_sorting'];
        }

        if(isset($this->collection['limit']) === true) {
            $query .= ' limit ' . $this->collection['limit'];
        }

        $prepare = $this->pdo->prepare($query);

        if(isset($this->collection['where_binding']) === true && count($this->collection['where_binding'])) {
            foreach($this->collection['where_binding'] as $key => $binding) {
                if(isset($binding['value']) === false) {
                    throw new DatabaseException('Missing "value" in parameters');
                }

                $allowed_types = ['BOOL', 'NULL', 'INT', 'STR', 'LOB', 'STMT'];

                if(isset($binding['type']) === false) {
                    $binding['type'] = 'STR';
                }

                $binding['type'] = strtoupper($binding['type']);

                if(in_array($binding['type'], $allowed_types, true) === false) {
                    throw new DatabaseException(
                        sprintf('Unknown data type "%s". Known types: %s', $binding['type'], implode(', ', $allowed_types)));
                }

                $prepare->bindValue($key, $binding['value'], constant('PDO::PARAM_' . $binding['type']));
            }
        }

        try {
            $prepare->execute();
        } catch(\PDOException $exception) {
            throw new DatabaseException($exception->getMessage());
        }
        
        return $prepare->fetchAll();
    }
}
