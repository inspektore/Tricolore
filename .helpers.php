<?php
use Symfony\Component\VarDumper\VarDumper;

/**
 * Dump variable
 * 
 * @return mixed
 */
function dump()
{
    foreach(func_get_args() as $variable) {
        VarDumper::dump($variable);
    }
}
