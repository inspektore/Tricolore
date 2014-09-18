<?php
namespace Tricolore\Exception;

class AssetNotFound extends \Exception
{
    /**
     * Return exception name
     * 
     * @return string
     */
    public function getExceptionName()
    {
        return 'AssetNotFound';
    }
}
