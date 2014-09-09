<?php
namespace Tricolore\Tests;

use Tricolore\Application;
use Tricolore\Services\ServiceLocator;

class ServiceLocatorAccessor extends ServiceLocator { }

class ServiceLocatorTest extends \PHPUnit_Framework_TestCase
{
    private $service_locator;
    private $service_path;
    private $service;

    public function __construct()
    {
        $this->service_locator = new ServiceLocatorAccessor();

        $this->service_path = Application::createPath('library:Tricolore:Tests:Fixtures:ServiceLocator.yml');
        $this->service = $this->service_locator->get('extra', [], $this->service_path);
    }

    public function testServiceInstanceOf()
    {
        $expected = 'Tricolore\Tests\Fixtures\ServiceLocatorExtra';
        $actual = $this->service->getInstance();

        $this->assertInstanceOf($expected, $actual);
    }

    /**
     * @expectedException Tricolore\Exception\ServicesException
     */
    public function testExceptionServiceNotExists()
    {
        return $this->service_locator->get('fake_foo', [], $this->service_path);
    }

    /**
     * @expectedException Tricolore\Exception\AssetNotFound
     */
    public function testExceptionWrongPath()
    {
        return $this->service_locator->get('fake', [], 'fake/path');
    }

    public function testMethodReturn()
    {
        $expected = 'Hello World';
        $actual = $this->service->getInstance()->stringReturn();

        $this->assertEquals($expected, $actual);
    }

    public function testServiceFunction()
    {
        $expected = 'myFunc';
        $actual = $this->service_locator->get('extra_func', [], $this->service_path);

        $this->assertEquals($expected, $actual);
    }
}
