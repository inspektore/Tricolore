<?php
namespace Tricolore\Cache\Providers;

use Doctrine\Common\Cache\PhpFileCache;

class PhpFile extends PhpFileCache
{
    const EXTENSION = '.php';

    /**
     * {@inheritdoc}
     */
    public function __construct($directory, $extension = self::EXTENSION, $umask = 0002)
    {
        parent::__construct($directory, $extension, $umask);
    }
}
