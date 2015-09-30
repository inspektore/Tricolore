<?php
namespace Tricolore\Cache;

use Tricolore\Foundation\Application;
use Tricolore\Config\Config;
use Tricolore\Exception\RuntimeException;

class Cache
{
    /**
     * Cache provider
     *
     * @param string $cache_folder
     * @throws Tricolore\Exception\RuntimeException
     * @return object
     */
    public function getProvider($cache_folder = null)
    {
        $provider_name = Config::getParameter('cache.provider');
        $provider_class = 'Tricolore\\Cache\\Providers\\' . $provider_name;

        if (class_exists($provider_class) === false) {
            throw new RuntimeException(sprintf('Cache provider "%s" do not exists.', $provider_name));
        }

        return new $provider_class(Application::createPath(Config::getParameter('directory.storage') . '/' . $cache_folder));
    }
}
