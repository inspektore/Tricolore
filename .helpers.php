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
    foreach (func_get_args() as $variable) {
        VarDumper::dump($variable);
    }
}

/**
 * Starts with
 * 
 * @param string $condition
 * @param string $from
 * @param int $length
 * @param bool $ignore_whitespaces
 * @return bool
 */
function startsWith($condition, $from, $length = 1, $ignore_whitespaces = false)
{
    if ($ignore_whitespaces === true) {
        $from = ltrim($from);
    }

    if (substr($from, 0, $length) === $condition) {
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
 * @param bool $ignore_whitespaces
 * @return bool
 */
function endsWith($condition, $from, $length = 1, $ignore_whitespaces = false)
{
    if ($ignore_whitespaces === true) {
        $from = rtrim($from);
    }

    if (substr($from, -$length) === $condition) {
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
