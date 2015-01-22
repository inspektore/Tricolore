<?php
namespace Tricolore\Tests;

class TwigIntegrationTest extends \PHPUnit_Framework_TestCase
{
    public function testAssetFunction()
    {
        $service_locator = $this->getMockForAbstractClass('Tricolore\Services\ServiceLocator');
        $service_view = $service_locator->get('view');

        $expected = 'http://localhost/Tricolore/tests/static/css/foo';
        $actual = $service_view->display(null, 'TestAsset', [], true);

        $this->assertEquals($expected, $actual);
    }

    public function testConfigFunction()
    {
        $service_locator = $this->getMockForAbstractClass('Tricolore\Services\ServiceLocator');
        $service_view = $service_locator->get('view');

        $expected = 'en';
        $actual = $service_view->display(null, 'TestConfig', [], true);

        $this->assertEquals($expected, $actual);
    }

    public function testAppGlobal()
    {
        $service_locator = $this->getMockForAbstractClass('Tricolore\Services\ServiceLocator');
        $service_view = $service_locator->get('view');

        $expected = 'test';
        $actual = $service_view->display(null, 'TestAppGlobal', [], true);

        $this->assertEquals($expected, $actual);
    }

    public function testUrlFunction()
    {
        $service_locator = $this->getMockForAbstractClass('Tricolore\Services\ServiceLocator');
        $service_view = $service_locator->get('view');

        $expected = 'http://localhost/Tricolore/tests/index.php?/my/test';
        $actual = $service_view->display(null, 'TestUrl', [], true);

        $this->assertEquals($expected, $actual);
    }

    public function testUrlFunctionNoRoute()
    {
        $service_locator = $this->getMockForAbstractClass('Tricolore\Services\ServiceLocator');
        $service_view = $service_locator->get('view');

        $expected = 'http://localhost/Tricolore/tests';
        $actual = $service_view->display(null, 'TestUrlNotRoute', [], true);

        $this->assertEquals($expected, $actual);
    }
}
