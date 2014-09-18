<?php
namespace Tricolore\Exception;

class RuntimeException extends \Exception
{
    /**
     * Return exception name
     * 
     * @return string
     */
    public function getExceptionName()
    {
        return 'RuntimeException';
    }
}
