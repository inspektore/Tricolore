<?php
namespace Tricolore\Tests;

use Tricolore\Foundation\Application;
use Tricolore\Exception\InvalidArgumentException;
use Tricolore\View\ExceptionHandler\ExceptionHandler;

class ExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testHandleException()
    {
        try {
            throw new InvalidArgumentException('Unicorn');
        } catch (InvalidArgumentException $exception) {
            $handler = new ExceptionHandler();

            $handler->handle($exception, true);
        }
    }
}
