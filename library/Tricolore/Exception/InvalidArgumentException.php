<?php
namespace Tricolore\Exception;

class InvalidArgumentException extends \Exception
{
    /**
     * Return exception name
     * 
     * @return string
     */
    public function getExceptionName()
    {
        return 'InvalidArgumentException';
    }
}
