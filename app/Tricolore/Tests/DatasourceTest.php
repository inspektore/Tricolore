<?php
namespace Tricolore\Tests;

use Tricolore\Application;
use Tricolore\Config\Config;

class DatasourceTest extends \PHPUnit_Framework_TestCase
{
    public function testConnection()
    {
        $service_datasource = $this->getMockForAbstractClass('Tricolore\Services\ServiceLocator')
        ->get('datasource');
    }

    public function testDatabaseExists()
    {
        $service_datasource = $this->getMockForAbstractClass('Tricolore\Services\ServiceLocator')
        ->get('datasource');

        $database_config = Config::all('Database');
        $config_key = (Application::getInstance()->inTravis() === true) ? 'travis' : 'default';
        $database_name = $database_config[Application::getInstance()->getEnv()][$config_key]['database_name'];

        $actual = $service_datasource->databaseExists($database_name);

        $this->assertTrue($actual);
    }

    public function testCreateTable()
    {
        $service_datasource = $this->getMockForAbstractClass('Tricolore\Services\ServiceLocator')
        ->get('datasource');

        $this->assertFalse($service_datasource->tableExists('tmp'));

        $service_datasource->buildQuery('create_table')
        ->name('tmp')
        ->columns([
            'tmp_col' => 'TEXT',
            'tmp_col2' => 'INT'
        ])
        ->ifNotExists()
        ->execute();

        $this->assertTrue($service_datasource->tableExists('tmp'));

        $service_datasource->dropTable('tmp');
    }

    /**
     * @expectedException Tricolore\Exception\InvalidArgumentException
     */
    public function testExceptionCreateDatabase()
    {
        $service_datasource = $this->getMockForAbstractClass('Tricolore\Services\ServiceLocator')
        ->get('datasource');

        $service_datasource->createDatabase('&foo');
    }

    public function testCreateDatabase()
    {
        $service_datasource = $this->getMockForAbstractClass('Tricolore\Services\ServiceLocator')
        ->get('datasource');

        if ($service_datasource->databaseExists('tmp_db') === false) {
            $service_datasource->createDatabase('tmp_db');
        }

        $this->assertTrue($service_datasource->databaseExists('tmp_db'));

        $service_datasource->dropDatabase('tmp_db');
    }

    public function testDelete()
    {
        $service_datasource = $this->getMockForAbstractClass('Tricolore\Services\ServiceLocator')
        ->get('datasource');

        $service_datasource->buildQuery('create_table')
        ->name('test_delete')
        ->columns([
            'tmp_col' => 'TEXT',
            'tmp_col2' => 'INT'
        ])
        ->ifNotExists()
        ->execute();

        $service_datasource->buildQuery('insert')
        ->into('test_delete')
        ->values([
            'tmp_col' => 'Doggy',
            'tmp_col2' => 6
        ])
        ->execute();

        $result = $service_datasource->buildQuery('select')
        ->select('tmp_col, tmp_col2')
        ->from('test_delete')
        ->execute();

        $this->assertSame($result[0], [
            'tmp_col' => 'Doggy',
            0 => 'Doggy',
            'tmp_col2' => 6,
            1 => 6
        ]);

        $service_datasource->buildQuery('delete')
        ->deleteFrom('test_delete')
        ->where('tmp_col = ?', [
            1 => [
                'value' => 'Doggy'
            ]
        ])
        ->execute();

        $result = $service_datasource->buildQuery('select')
        ->select('tmp_col, tmp_col2')
        ->from('test_delete')
        ->execute();

        $this->assertSame($result, []);
    }

    public function testSelectAllResults()
    {
        $service_datasource = $this->getMockForAbstractClass('Tricolore\Services\ServiceLocator')
        ->get('datasource');

        $service_datasource->buildQuery('create_table')
        ->name('test_select_all_results')
        ->columns([
            'tmp_col' => 'TEXT',
            'tmp_col2' => 'INT'
        ])
        ->ifNotExists()
        ->execute();

        $service_datasource->buildQuery('insert')
        ->into('test_select_all_results')
        ->values([
            'tmp_col' => 'Doggy',
            'tmp_col2' => 6
        ])
        ->execute();

        $result = $service_datasource->buildQuery('select')
        ->select('tmp_col, tmp_col2')
        ->from('test_select_all_results')
        ->execute();

        $this->assertSame($result[0], [
            'tmp_col' => 'Doggy',
            0 => 'Doggy',
            'tmp_col2' => 6,
            1 => 6
        ]);
    }

    public function testSelectWhere()
    {
        $service_datasource = $this->getMockForAbstractClass('Tricolore\Services\ServiceLocator')
        ->get('datasource');

        $service_datasource->buildQuery('create_table')
        ->name('test_select_all_results')
        ->columns([
            'tmp_col' => 'TEXT',
            'tmp_col2' => 'INT'
        ])
        ->ifNotExists()
        ->execute();

        $service_datasource->buildQuery('insert')
        ->into('test_select_all_results')
        ->values([
            'tmp_col' => 'Catty',
            'tmp_col2' => 7
        ])
        ->execute();

        $result = $service_datasource->buildQuery('select')
        ->select('tmp_col, tmp_col2')
        ->from('test_select_all_results')
        ->where('tmp_col = ?', [
            1 => [
                'value' => 'Catty',
                'type' => 'str'
            ]
        ])
        ->execute();

        $this->assertSame($result[0], [
            'tmp_col' => 'Catty',
            0 => 'Catty',
            'tmp_col2' => 7,
            1 => 7
        ]);
    }

    public function testSelectLeftJoin()
    {
        $service_datasource = $this->getMockForAbstractClass('Tricolore\Services\ServiceLocator')
        ->get('datasource');

        $service_datasource->buildQuery('create_table')
        ->name('join1table')
        ->columns([
            'join1col' => 'TEXT'
        ])
        ->ifNotExists()
        ->execute();

        $service_datasource->buildQuery('create_table')
        ->name('join2table')
        ->columns([
            'join2col' => 'TEXT'
        ])
        ->ifNotExists()
        ->execute();

        $service_datasource->buildQuery('insert')
        ->into('join1table')
        ->values([
            'join1col' => '___data___'
        ])
        ->execute();

        $service_datasource->buildQuery('insert')
        ->into('join2table')
        ->values([
            'join2col' => '___data___'
        ])
        ->execute();

        $result = $service_datasource->buildQuery('select')
        ->select('join1table.join1col, join2table.join2col')
        ->from('join1table')
        ->leftJoin('join2table', 'join1table.join1col = join2table.join2col')
        ->execute();

        $this->assertSame($result[0], [
            'join1col' => '___data___',
            0 => '___data___',
            'join2col' => '___data___',
            1 => '___data___'
        ]);
    }

    public function testSelectGroupByOrderBy()
    {
        $service_datasource = $this->getMockForAbstractClass('Tricolore\Services\ServiceLocator')
        ->get('datasource');

        $service_datasource->buildQuery('create_table')
        ->name('groupbytable')
        ->columns([
            'groupby_col1' => 'TEXT'
        ])
        ->ifNotExists()
        ->execute();

        foreach ([1, 2, 3] as $name) {
            $service_datasource->buildQuery('insert')
            ->into('groupbytable')
            ->values([
                'groupby_col1' => $name
            ])
            ->execute();
        }

        $asc = $service_datasource->buildQuery('select')
        ->select('groupby_col1')
        ->from('groupbytable')
        ->groupBy('groupby_col1')
        ->orderBy('groupby_col1', 'asc')
        ->execute();

        $asc_non_valid_sorting = $service_datasource->buildQuery('select')
        ->select('groupby_col1')
        ->from('groupbytable')
        ->groupBy('groupby_col1')
        ->orderBy('groupby_col1', 'not-valid')
        ->execute();

        $desc = $service_datasource->buildQuery('select')
        ->select('groupby_col1')
        ->from('groupbytable')
        ->groupBy('groupby_col1')
        ->orderBy('groupby_col1', 'desc')
        ->execute();

        $this->assertSame($desc, [
            0 => [
                'groupby_col1' => '3',
                0 => '3'
            ],

            1 => [
                'groupby_col1' => '2',
                0 => '2'
            ],

            2 => [
                'groupby_col1' => '1',
                0 => '1'
            ]
        ]);

        $this->assertSame($asc, [
            0 => [
                'groupby_col1' => '1',
                0 => '1'
            ],

            1 => [
                'groupby_col1' => '2',
                0 => '2'
            ],

            2 => [
                'groupby_col1' => '3',
                0 => '3'
            ]
        ]);

        $this->assertSame($asc_non_valid_sorting, [
            0 => [
                'groupby_col1' => '1',
                0 => '1'
            ],

            1 => [
                'groupby_col1' => '2',
                0 => '2'
            ],

            2 => [
                'groupby_col1' => '3',
                0 => '3'
            ]
        ]);
    }

    public function testSelectMaxResults()
    {
        $service_datasource = $this->getMockForAbstractClass('Tricolore\Services\ServiceLocator')
        ->get('datasource');

        $service_datasource->buildQuery('create_table')
        ->name('maxresultstable')
        ->columns([
            'maxresultscol' => 'TEXT'
        ])
        ->ifNotExists()
        ->execute();

        foreach ([1, 2, 3, 4, 5] as $name) {
            $service_datasource->buildQuery('insert')
            ->into('maxresultstable')
            ->values([
                'maxresultscol' => $name
            ])
            ->execute();
        }

        $result = $service_datasource->buildQuery('select')
        ->select('maxresultscol')
        ->from('maxresultstable')
        ->maxResults(3)
        ->execute();

        $this->assertCount(3, $result);
    }

    /**
     * @expectedException Tricolore\Exception\DatabaseException
     * @expectedExceptionMessage "Select" in query is required. Add select() method to your query builder.
     */
    public function testExceptionSelectRequiredSelect()
    {
        $service_datasource = $this->getMockForAbstractClass('Tricolore\Services\ServiceLocator')
        ->get('datasource');

        $service_datasource->buildQuery('select')
        ->from('test')
        ->execute();
    }

    /**
     * @expectedException Tricolore\Exception\DatabaseException
     * @expectedExceptionMessage "From" in query is required. Add from() method to your query builder.
     */
    public function testExceptionSelectRequiredFrom()
    {
        $service_datasource = $this->getMockForAbstractClass('Tricolore\Services\ServiceLocator')
        ->get('datasource');

        $service_datasource->buildQuery('select')
        ->select('test')
        ->execute();
    }

    /**
     * @expectedException Tricolore\Exception\DatabaseException
     */
    public function testExceptionSelectNotExistingTable()
    {
        $service_datasource = $this->getMockForAbstractClass('Tricolore\Services\ServiceLocator')
        ->get('datasource');

        $service_datasource->buildQuery('select')
        ->select('thing')
        ->from('some_table')
        ->execute();
    }
}
