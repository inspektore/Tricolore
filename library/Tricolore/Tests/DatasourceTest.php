<?php
namespace Tricolore\Tests;

use Tricolore\Application;

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

        $database_name = (Application::getInstance()->inTravis() === true) ? 'travis_ci_test' : 'tricolore_tests';

        $actual = $service_datasource->databaseExists($database_name);

        $this->assertTrue($actual);
    }

    public function testCreateTable()
    {
        $service_datasource = $this->getMockForAbstractClass('Tricolore\Services\ServiceLocator')
        ->get('datasource');

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
}
