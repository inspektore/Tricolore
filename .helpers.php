<?php
use Symfony\Component\VarDumper\VarDumper;

/**
 * Dump variable
 * 
 * @codeCoverageIgnore
 * @return mixed
 */
function dump()
{
    foreach(func_get_args() as $variable) {
        VarDumper::dump($variable);
    }
}

/**
 * Starts with
 * 
 * @param string $condition
 * @param string $from
 * @param int $length
 * @return bool
 */
function startsWith($condition, $from, $length = 1)
{
    if(substr($from, 0, $length) === $condition) {
        return true;
    }

    return false;
}

/**
 * Starts with
 * 
 * @param string $condition
 * @param string $from
 * @param int $length
 * @return bool
 */
function endsWith($condition, $from, $length = 1)
{
    if(substr($from, -$length) === $condition) {
        return true;
    }

    return false;
}

/**
 * Underscore to CamelCase
 * 
 * @param string $text 
 * @return string
 */
function underscoreToCamelCase($text)
{
    return str_replace(' ', '', ucwords(str_replace('_', ' ', $text)));
}
