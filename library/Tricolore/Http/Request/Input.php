<?php
namespace Tricolore\Http\Request;

class Input
{
    /**
     * Get input value from request
     * 
     * @param string $field_name
     * @return string|bool
     */
    public static function get($field_name)
    {
        if(self::has($field_name) === false) {
            return false;
        }

        if($field_name == null) {
            return false;
        }

        return self::all()[$field_name];
    }

    /**
     * Determining if an input value is present
     * 
     * @param string $field_name 
     * @return bool
     */
    public static function has($field_name)
    {
        if(isset(self::all()[$field_name]) === true) {
            return true;
        }

        return false;
    }

    /**
     * Fetch all input from request
     * 
     * @return array
     */
    public static function all()
    {
        if(count($_POST) > 0) {
            return $_POST;
        }

        return [];
    }
}
