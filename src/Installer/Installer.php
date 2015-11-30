<?php
namespace Tricolore\Installer;

use Tricolore\Foundation\Application;
use Tricolore\Config\Config;
use Tricolore\Services\ServiceLocator;

class Installer extends ServiceLocator
{
    /**
     * Check system requirements
     *
     * @return array
     */
    public function checkRequirements()
    {
        $components['php'] = function () {
            $details = [
                'name' => $this->get('translator')->trans('PHP 5.6'),
                'required' => true,
                'status' => 'fail'
            ];

            if (version_compare(phpversion(), '5.6.0', '>') === true) {
                $details['status'] = 'ok';
            }

            return $details;
        };

        $components['storage_writable'] = function () {
            $details = [
                'name' => $this->get('translator')->trans('Writable storage directory'),
                'required' => true,
                'status' => 'fail'
            ];

            if (is_writable(Application::createPath(Config::getParameter('directory.storage')))) {
                $details['status'] = 'ok';
            }

            return $details;
        };

        $components['config_writable'] = function () {
            $details = [
                'name' => $this->get('translator')->trans('Writable configuration file'),
                'required' => true,
                'status' => 'fail'
            ];

            if (is_writable(Application::createPath('app/config/configuration.yml'))) {
                $details['status'] = 'ok';
            }

            return $details;
        };

        $components['intl'] = function () {
            $details = [
                'name' => $this->get('translator')->trans('INTL extension'),
                'required' => true,
                'status' => 'fail'
            ];

            if (extension_loaded('intl') === true) {
                $details['status'] = 'ok';
            }

            return $details;
        };

        $components['pgsql'] = function () {
            $details = [
                'name' => $this->get('translator')->trans('Enabled pdo_pgsql extension'),
                'required' => true,
                'status' => 'fail'
            ];

            if (extension_loaded('pdo_pgsql') === true) {
                $details['status'] = 'ok';
            }

            return $details;
        };

        $components['zlib'] = function () {
            $details = [
                'name' => $this->get('translator')->trans('zlib (optional)'),
                'required' => false,
                'status' => 'fail'
            ];

            if (extension_loaded('zlib') === true) {
                $details['status'] = 'ok';
            }

            return $details;
        };

        $components['xdebug'] = function () {
            $details = [
                'name' => $this->get('translator')->trans('Xdebug (optional)'),
                'required' => false,
                'status' => 'fail'
            ];

            if (function_exists('xdebug_get_headers') === true) {
                $details['status'] = 'ok';
            }

            return $details;
        };

        $components['memcache'] = function () {
            $details = [
                'name' => $this->get('translator')->trans('Memcache (optional)'),
                'required' => false,
                'status' => 'fail'
            ];

            if (class_exists('Memcache') === true) {
                $details['status'] = 'ok';
            }

            return $details;
        };

        $components['memcached'] = function () {
            $details = [
                'name' => $this->get('translator')->trans('Memcached (optional)'),
                'required' => false,
                'status' => 'fail'
            ];

            if (class_exists('Memcached') === true) {
                $details['status'] = 'ok';
            }

            return $details;
        };

        $components['redis'] = function () {
            $details = [
                'name' => $this->get('translator')->trans('Redis (optional)'),
                'required' => false,
                'status' => 'fail'
            ];

            if (class_exists('Redis') === true) {
                $details['status'] = 'ok';
            }

            return $details;
        };

        $components['xcache'] = function () {
            $details = [
                'name' => $this->get('translator')->trans('XCache (optional)'),
                'required' => false,
                'status' => 'fail'
            ];

            if (function_exists('xcache_set') === true) {
                $details['status'] = 'ok';
            }

            return $details;
        };

        return $this->returnClosureArray($components);
    }

    /**
     * Return closure array
     *
     * @param array $components
     * @return array
     */
    private function returnClosureArray(array $components)
    {
        foreach ($components as $component => $closure) {
            $components[$component] = $closure();  
        }

        return $components;
    }

    /**
     * Check if Tricolore can be installed
     *
     * @return bool
     */
    public function canInstall()
    {
        foreach($this->checkRequirements() as $requirement) {
            if ($requirement['required'] === true && $requirement['status'] === 'fail') {
                return false;
            }
        }

        return true;
    }
}
