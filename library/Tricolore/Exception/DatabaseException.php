<?php
namespace Tricolore\Exception;

class DatabaseException extends \Exception
{
    /**
     * Return exception name
     * 
     * @return string
     */
    public function getExceptionName()
    {
        return 'DatabaseException';
    }
}
