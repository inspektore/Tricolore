<?php
namespace Tricolore\Cache\Providers;

use Tricolore\Config\Config;
use Doctrine\Common\Cache\RedisCache;

class Redis extends RedisCache
{
    /**
     * Construct
     *
     * @return void
     */
    public function __construct()
    {
        $redis = new \Redis();
        $redis->connect(Config::getParameter('cache.redis.server'), Config::getParameter('cache.redis.port'));

        $this->setRedis($redis);
    }
}
