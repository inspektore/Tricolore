<?php
use Tricolore\Exception\RuntimeException;

if (function_exists('startsWith') === false) {
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
}

if (function_exists('endsWith') === false) {
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
}

if (function_exists('underscoreToStudlyCaps') === false) {
    /**
     * under_score to StudlyCaps
     * 
     * @param string $text 
     * @return string
     */
    function underscoreToStudlyCaps($text)
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $text)));
    }
}

if (function_exists('redirect') === false) {
    /**
     * Redirect
     * 
     * @param string $target
     * @throws Tricolore\Exception\RuntimeException
     * @return void
     */
    function redirect($target)
    {
        if (headers_sent() === true) {
            throw new RuntimeException('Headers already sent.');
        }

        header(sprintf('Location: %s', $target));
    }
}
