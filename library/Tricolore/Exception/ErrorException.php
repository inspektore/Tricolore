<?php
namespace Tricolore\Exception;

class ErrorException extends \Exception
{
    /**
     * Return exception name
     * 
     * @return string
     */
    public function getExceptionName()
    {
        return 'ErrorException';
    }
}
