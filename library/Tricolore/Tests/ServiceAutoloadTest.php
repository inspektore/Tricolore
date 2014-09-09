<?php
namespace Tricolore\Tests;

use Tricolore\Application;
use Tricolore\Services\AutoloadService;

class ServiceAutoloadTest extends \PHPUnit_Framework_TestCase
{
    private $loaded_classes;

    public function __construct()
    {
        $path = Application::createPath('library:Tricolore:Tests:Fixtures:ServiceAutoload.yml');
        $service = new AutoloadService();
        $this->loaded_classes = $service->dispatch($path);
    }

    public function testLoadedClassesWithNoFunc()
    {
        $this->assertContains('Tricolore\Tests\Fixtures\ServiceAutoloadExtraClassFirst', $this->loaded_classes);
        $this->assertContains('Tricolore\Tests\Fixtures\ServiceAutoloadExtraClassThird', $this->loaded_classes);
    }

    public function testLoadedClassesWithFunc()
    {
        $this->assertContains('Tricolore\Tests\Fixtures\ServiceAutoloadExtraClassSecond:testFunc', $this->loaded_classes);
        $this->assertContains('Tricolore\Tests\Fixtures\ServiceAutoloadExtraClassFourth:testFunc', $this->loaded_classes);
    }

    public function testClassExistsServiceAutoloadExtraClassFirst()
    {
        if(class_exists('Tricolore\Tests\Fixtures\ServiceAutoloadExtraClassFirst')) {
            $this->assertTrue(true);
        } else {
            $this->assertTrue(false);
        }
    }

    public function testClassExistsServiceAutoloadExtraClassThird()
    {
        if(class_exists('Tricolore\Tests\Fixtures\ServiceAutoloadExtraClassThird')) {
            $this->assertTrue(true);
        } else {
            $this->assertTrue(false);
        }
    }
}
