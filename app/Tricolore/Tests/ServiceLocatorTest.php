<?php
namespace Tricolore\Tests;

use Tricolore\Application;

class ServiceLocatorTest extends \PHPUnit_Framework_TestCase
{
    private function getServiceLocator()
    {
        return $this->getMockForAbstractClass('Tricolore\Services\ServiceLocator');
    }

    public function testServiceInstanceOf()
    {
        $service_path = Application::createPath('app:Tricolore:Tests:Fixtures:ServiceLocator.yml');
        $service_extra = $this->getServiceLocator()->get('extra', [], $service_path);

        $expected = 'Tricolore\Tests\Fixtures\ServiceLocatorExtra';
        $actual = $service_extra->getInstance();

        $this->assertInstanceOf($expected, $actual);
    }

    /**
     * @expectedException Tricolore\Exception\ServicesException
     */
    public function testExceptionServiceNotExists()
    {
        $service_path = Application::createPath('app:Tricolore:Tests:Fixtures:ServiceLocator.yml');

        return $this->getServiceLocator()->get('fake_foo', [], $service_path);
    }

    /**
     * @expectedException Tricolore\Exception\AssetNotFound
     */
    public function testExceptionWrongPath()
    {
        return $this->getServiceLocator()->get('fake', [], 'fake/path');
    }

    public function testMethodReturn()
    {
        $service_path = Application::createPath('app:Tricolore:Tests:Fixtures:ServiceLocator.yml');
        $service_extra = $this->getServiceLocator()->get('extra', [], $service_path);

        $expected = 'Hello World';
        $actual = $service_extra->getInstance()->stringReturn();

        $this->assertEquals($expected, $actual);
    }

    public function testServiceFunction()
    {
        $service_path = Application::createPath('app:Tricolore:Tests:Fixtures:ServiceLocator.yml');

        $expected = 'myFunc';
        $actual = $this->getServiceLocator()->get('extra_func', [], $service_path);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @expectedException Tricolore\Exception\ServicesException
     */
    public function testServiceClassNotExists()
    {
        $service_path = Application::createPath('app:Tricolore:Tests:Fixtures:ServiceLocator.yml');

        $this->getServiceLocator()->get('class_not_exists', [], $service_path);
    }

    /**
     * @expectedException Tricolore\Exception\ServicesException
     */
    public function testServiceMethodNotExists()
    {
        $service_path = Application::createPath('app:Tricolore:Tests:Fixtures:ServiceLocator.yml');

        $this->getServiceLocator()->get('method_not_exists', [], $service_path);
    }

    public function testServiceStaticFunction()
    {
        $service_path = Application::createPath('app:Tricolore:Tests:Fixtures:ServiceLocator.yml');

        $expected = 'staticFunc';
        $actual = $this->getServiceLocator()->get('static_func', [], $service_path);

        $this->assertEquals($expected, $actual);
    }
}
