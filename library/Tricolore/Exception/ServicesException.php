<?php
namespace Tricolore\Exception;

class ServicesException extends \Exception
{
    /**
     * Return exception name
     * 
     * @return string
     */
    public function getExceptionName()
    {
        return 'ServicesException';
    }
}
