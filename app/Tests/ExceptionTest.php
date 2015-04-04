<?php
namespace Tricolore\Tests;

use Tricolore\Foundation\Application;
use Tricolore\Exception\InvalidArgumentException;

class ExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testHandleException()
    {
        $service_locator = $this->getMockForAbstractClass('Tricolore\Services\ServiceLocator');
        $service_path = Application::createPath('app:Tests:Fixtures:ServiceLocator.yml');
        $service_view = $service_locator->get('view');

        try {
            throw new InvalidArgumentException('Unicorn');
        } catch (InvalidArgumentException $exception) {
            $service_view->handleException($exception, true);
        }
    }
}
