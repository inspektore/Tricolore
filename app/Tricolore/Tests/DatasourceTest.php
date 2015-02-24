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

    /**
     * @expectedException Tricolore\Exception\InvalidArgumentException
     */
    public function testExceptionFactoryNotAllowedType()
    {
        $service_datasource = $this->getMockForAbstractClass('Tricolore\Services\ServiceLocator')
        ->get('datasource');

        $service_datasource->buildQuery('not_valid');
    }

    /**
     * @expectedException Tricolore\Exception\DatabaseException
     */
    public function testExceptionFactoryBindingMissingValue()
    {
        $service_datasource = $this->getMockForAbstractClass('Tricolore\Services\ServiceLocator')
        ->get('datasource');

        $service_datasource->buildQuery('create_table')
        ->name('binding1table')
        ->columns([
            'binding1col' => 'TEXT'
        ])
        ->ifNotExists()
        ->execute();

        $service_datasource->buildQuery('insert')
        ->into('binding1table')
        ->values([
            'binding1col' => '___data___'
        ])
        ->execute();

        $service_datasource->buildQuery('select')
        ->select('binding1col')
        ->from('binding1table')
        ->where('binding1col = ?', [
            1 => [
                'not_valid' => 'not_valid'
            ]
        ])
        ->execute();
    }

    /**
     * @expectedException Tricolore\Exception\DatabaseException
     */
    public function testExceptionFactoryBindingWrongDataType()
    {
        $service_datasource = $this->getMockForAbstractClass('Tricolore\Services\ServiceLocator')
        ->get('datasource');

        $service_datasource->buildQuery('create_table')
        ->name('binding2table')
        ->columns([
            'binding2col' => 'TEXT'
        ])
        ->ifNotExists()
        ->execute();

        $service_datasource->buildQuery('insert')
        ->into('binding2table')
        ->values([
            'binding2col' => '___data___'
        ])
        ->execute();

        $service_datasource->buildQuery('select')
        ->select('binding2col')
        ->from('binding2table')
        ->where('binding2col = ?', [
            1 => [
                'value' => 'foo',
                'type' => 'not_valid'
            ]
        ])
        ->execute();
    }

    public function testFactoryDropTableArray()
    {
        $service_datasource = $this->getMockForAbstractClass('Tricolore\Services\ServiceLocator')
        ->get('datasource');

        $service_datasource->buildQuery('create_table')
        ->name('drop1table')
        ->columns([
            'drop1col' => 'TEXT'
        ])
        ->ifNotExists()
        ->execute();

        $service_datasource->buildQuery('create_table')
        ->name('drop2table')
        ->columns([
            'drop2col' => 'TEXT'
        ])
        ->ifNotExists()
        ->execute();

        $this->assertTrue($service_datasource->tableExists('drop1table'));
        $this->assertTrue($service_datasource->tableExists('drop2table'));

        $service_datasource->dropTable(['drop1table', 'drop2table']);

        $this->assertFalse($service_datasource->tableExists('drop1table'));
        $this->assertFalse($service_datasource->tableExists('drop2table'));
    }

    public function testFactoryQueriesNumber()
    {
        $service_datasource = $this->getMockForAbstractClass('Tricolore\Services\ServiceLocator')
        ->get('datasource');

        $expected = 15;
        $actual = $service_datasource->getQueriesNumber();

        $this->assertSame($actual, $expected);
    }

    public function testFactoryDropField()
    {
        $service_datasource = $this->getMockForAbstractClass('Tricolore\Services\ServiceLocator')
        ->get('datasource');

        $service_datasource->buildQuery('create_table')
        ->name('dropfield1table')
        ->columns([
            'dropfield1col' => 'TEXT'
        ])
        ->ifNotExists()
        ->execute();

        $this->assertTrue($service_datasource->fieldExists('dropfield1col', 'dropfield1table'));

        $service_datasource->dropField('dropfield1table', 'dropfield1col');

        $this->assertFalse($service_datasource->fieldExists('dropfield1col', 'dropfield1table'));

        $service_datasource->dropTable('dropfield1table');
    }

    public function testFactoryDropFieldArray()
    {
        $service_datasource = $this->getMockForAbstractClass('Tricolore\Services\ServiceLocator')
        ->get('datasource');

        $service_datasource->buildQuery('create_table')
        ->name('dropfield2table')
        ->columns([
            'dropfield1col' => 'TEXT',
            'dropfield2col' => 'TEXT'
        ])
        ->ifNotExists()
        ->execute();

        $this->assertTrue($service_datasource->fieldExists('dropfield1col', 'dropfield2table'));
        $this->assertTrue($service_datasource->fieldExists('dropfield2col', 'dropfield2table'));

        $service_datasource->dropField('dropfield2table', ['dropfield1col', 'dropfield2col']);

        $this->assertFalse($service_datasource->fieldExists('dropfield1col', 'dropfield2table'));
        $this->assertFalse($service_datasource->fieldExists('dropfield2col', 'dropfield2table'));

        $service_datasource->dropTable('dropfield2table');
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
     * @expectedException Tricolore\Exception\DatabaseException
     * @expectedExceptionMessage "Name" in query is required. Add name() method to your query builder.
     */
    public function testExceptionCreateTableNameRequired()
    {
        $service_datasource = $this->getMockForAbstractClass('Tricolore\Services\ServiceLocator')
        ->get('datasource');

        $service_datasource->buildQuery('create_table')
        ->columns([
            'col' => 'value'
        ])
        ->execute();
    }

    /**
     * @expectedException Tricolore\Exception\DatabaseException
     * @expectedExceptionMessage "Columns" in query is required. Add columns() method to your query builder.
     */
    public function testExceptionCreateTableColumnsRequired()
    {
        $service_datasource = $this->getMockForAbstractClass('Tricolore\Services\ServiceLocator')
        ->get('datasource');

        $service_datasource->buildQuery('create_table')
        ->name('tmp')
        ->execute();
    }

    /**
     * @expectedException Tricolore\Exception\DatabaseException
     * @expectedExceptionMessage "Columns" array is empty.
     */
    public function testExceptionCreateTableColumnsEmptyArray()
    {
        $service_datasource = $this->getMockForAbstractClass('Tricolore\Services\ServiceLocator')
        ->get('datasource');

        $service_datasource->buildQuery('create_table')
        ->name('tmp')
        ->columns([])
        ->execute();
    }

    /**
     * @expectedException Tricolore\Exception\DatabaseException
     */
    public function testExceptionCreateTableQuery()
    {
        $service_datasource = $this->getMockForAbstractClass('Tricolore\Services\ServiceLocator')
        ->get('datasource');

        $service_datasource->buildQuery('create_table')
        ->name('tmp')
        ->columns([
            '' => 'value'
        ])
        ->execute();
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

    /**
     * @expectedException Tricolore\Exception\DatabaseException
     * @expectedExceptionMessage "From" in query is required. Add deleteFrom() method to your query builder.
     */
    public function testExceptionDeteleFromRequired()
    {
        $service_datasource = $this->getMockForAbstractClass('Tricolore\Services\ServiceLocator')
        ->get('datasource');

        $service_datasource->buildQuery('delete')
        ->where('foo = ?', [
            1 => [
                'value' => 'bar'
            ]
        ])
        ->execute();
    }

    /**
     * @expectedException Tricolore\Exception\DatabaseException
     * @expectedExceptionMessage "Where" in query is required. Add where() method to your query builder.
     */
    public function testExceptionDeteleWhereRequired()
    {
        $service_datasource = $this->getMockForAbstractClass('Tricolore\Services\ServiceLocator')
        ->get('datasource');

        $service_datasource->buildQuery('delete')
        ->deleteFrom('foo')
        ->execute();
    }

    /**
     * @expectedException Tricolore\Exception\DatabaseException
     */
    public function testExceptionDeleteQuery()
    {
        $service_datasource = $this->getMockForAbstractClass('Tricolore\Services\ServiceLocator')
        ->get('datasource');

        $service_datasource->buildQuery('delete')
        ->deleteFrom('not_valid')
        ->where('foo = ?', [
            1 => [
                'value' => 'bar'
            ]
        ])
        ->execute();
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

    public function testInsert()
    {
        $service_datasource = $this->getMockForAbstractClass('Tricolore\Services\ServiceLocator')
        ->get('datasource');

        $service_datasource->buildQuery('create_table')
        ->name('insert1table')
        ->columns([
            'insert1col' => 'TEXT'
        ])
        ->ifNotExists()
        ->execute();

        $service_datasource->buildQuery('insert')
        ->into('insert1table')
        ->values([
            'insert1col' => '___data___'
        ])
        ->execute();

        $actual = $service_datasource->buildQuery('select')
        ->select('insert1col')
        ->from('insert1table')
        ->execute();

        $expected = '___data___';

        $this->assertSame($expected, $actual[0]['insert1col']);
    }

    /**
     * @expectedException Tricolore\Exception\DatabaseException
     * @expectedExceptionMessage "Into" in query is required. Add into() method to your query builder.
     */
    public function testExceptionInsertIntoRequired()
    {
        $service_datasource = $this->getMockForAbstractClass('Tricolore\Services\ServiceLocator')
        ->get('datasource');

        $service_datasource->buildQuery('insert')
        ->values([])
        ->execute();
    }

    /**
     * @expectedException Tricolore\Exception\DatabaseException
     * @expectedExceptionMessage "Values" in query is required. Add values() method to your query builder.
     */
    public function testExceptionInsertValuesRequired()
    {
        $service_datasource = $this->getMockForAbstractClass('Tricolore\Services\ServiceLocator')
        ->get('datasource');

        $service_datasource->buildQuery('insert')
        ->into('some_table')
        ->execute();
    }

    /**
     * @expectedException Tricolore\Exception\DatabaseException
     * @expectedExceptionMessage "Values" array is empty.
     */
    public function testExceptionInsertEmptyValues()
    {
        $service_datasource = $this->getMockForAbstractClass('Tricolore\Services\ServiceLocator')
        ->get('datasource');

        $service_datasource->buildQuery('insert')
        ->into('some_table')
        ->values([])
        ->execute();
    }

    /**
     * @expectedException Tricolore\Exception\DatabaseException
     */
    public function testExceptionInsertQuery()
    {
        $service_datasource = $this->getMockForAbstractClass('Tricolore\Services\ServiceLocator')
        ->get('datasource');

        $service_datasource->buildQuery('insert')
        ->into('some_table')
        ->values([
            'foo' => 'bar'
        ])
        ->execute();
    }

    public function testUpdate()
    {
        $service_datasource = $this->getMockForAbstractClass('Tricolore\Services\ServiceLocator')
        ->get('datasource');

        $service_datasource->buildQuery('create_table')
        ->name('update1table')
        ->columns([
            'update1col' => 'TEXT'
        ])
        ->ifNotExists()
        ->execute();

        $service_datasource->buildQuery('insert')
        ->into('update1table')
        ->values([
            'update1col' => '___data___'
        ])
        ->execute();

        $actual = $service_datasource->buildQuery('select')
        ->select('update1col')
        ->from('update1table')
        ->execute();

        $expected = '___data___';

        $this->assertSame($expected, $actual[0]['update1col']);

        $service_datasource->buildQuery('update')
        ->table('update1table')
        ->set('update1col = ?', [
            1 => [
                'value' => '___newdata___'
            ]
        ])
        ->where('update1col = ?', [
            2 => [
                'value' => '___data___'
            ]
        ])
        ->execute();

        $actual = $service_datasource->buildQuery('select')
        ->select('update1col')
        ->from('update1table')
        ->execute();

        $expected = '___newdata___';

        $this->assertSame($expected, $actual[0]['update1col']);

        $service_datasource->dropTable('update1table');
    }

    /**
     * @expectedException Tricolore\Exception\DatabaseException
     * @expectedExceptionMessage "Table" in query is required. Add table() method to your query builder.
     */
    public function testExceptionUpdateTableRequired()
    {
        $service_datasource = $this->getMockForAbstractClass('Tricolore\Services\ServiceLocator')
        ->get('datasource');

        $service_datasource->buildQuery('update')
        ->set('string')
        ->execute();
    }

    /**
     * @expectedException Tricolore\Exception\DatabaseException
     * @expectedExceptionMessage "Set" in query is required. Add set() method to your query builder.
     */
    public function testExceptionUpdateSetRequired()
    {
        $service_datasource = $this->getMockForAbstractClass('Tricolore\Services\ServiceLocator')
        ->get('datasource');

        $service_datasource->buildQuery('update')
        ->table('some_table')
        ->execute();
    }

    /**
     * @expectedException Tricolore\Exception\DatabaseException
     */
    public function testExceptionUpdateQuery()
    {
        $service_datasource = $this->getMockForAbstractClass('Tricolore\Services\ServiceLocator')
        ->get('datasource');

        $service_datasource->buildQuery('update')
        ->table('some_table')
        ->set('foo = bar')
        ->execute();
    }

    public function testGetAllTables()
    {
        $service_datasource = $this->getMockForAbstractClass('Tricolore\Services\ServiceLocator')
        ->get('datasource');

        $expected = 9;
        $actual = count($service_datasource->getAllTables());

        $this->assertEquals($expected, $actual);
    }
}
