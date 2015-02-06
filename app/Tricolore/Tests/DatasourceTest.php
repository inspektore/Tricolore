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
}
