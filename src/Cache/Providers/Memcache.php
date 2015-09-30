<?php
namespace Tricolore\Cache\Providers;

use Tricolore\Config\Config;
use Doctrine\Common\Cache\MemcacheCache;

class Memcache extends MemcacheCache
{
    /**
     * Construct
     *
     * @return void
     */
    public function __construct()
    {
        $memcache = new \Memcache();
        $memcache->connect(Config::getParameter('cache.memcache.server'), Config::getParameter('cache.memcache.port'));

        $this->setMemcache($memcache);
    }
}
