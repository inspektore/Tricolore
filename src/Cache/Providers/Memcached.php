<?php
namespace Tricolore\Cache\Providers;

use Tricolore\Config\Config;
use Doctrine\Common\Cache\MemcachedCache;

class Memcached extends MemcachedCache
{
    /**
     * Construct
     *
     * @return void
     */
    public function __construct()
    {
        $memcached = new \Memcached();
        $memcached->addServer(Config::getParameter('cache.memcached.server'), Config::getParameter('cache.memcached.port'));

        $this->setMemcached($memcached);
    }
}
