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

        $database_name = (Application::inTravis() === true) ? 'tricolore_tests' : 'travis_ci_test';

        $actual = $service_datasource->databaseExists($database_name);

        $this->assertTrue($actual);
    }
}
