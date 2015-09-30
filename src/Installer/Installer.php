<?php
namespace Tricolore\Installer;

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
        $components = [];

        if (version_compare(phpversion(), '5.5.0', '>') === true) {
            $components['php'] = [
                'name' => $this->get('translator')->trans('PHP 5.5'),
                'status' => 'ok',
                'required' => true
            ];
        } else {
            $components['php'] = [
                'name' => $this->get('translator')->trans('PHP 5.5'),
                'status' => 'fail',
                'required' => true
            ];
        }
        
        if (extension_loaded('intl') === true) {
            $components['intl'] = [
                'name' => $this->get('translator')->trans('INTL extension'),
                'status' => 'ok',
                'required' => true
            ];
        } else {
            $components['intl'] = [
                'name' => $this->get('translator')->trans('INTL extension'),
                'status' => 'fail',
                'required' => true
            ];
        }

        if (extension_loaded('pdo_pgsql') === true) {
            $components['pgsql'] = [
                'name' => $this->get('translator')->trans('Enabled pdo_pgsql extension'),
                'status' => 'ok',
                'required' => true
            ];
        } else {
            $components['pgsql'] = [
                'name' => $this->get('translator')->trans('Enabled pdo_pgsql extension'),
                'status' => 'fail',
                'required' => true
            ];
        }

        if (extension_loaded('zlib') === true) {
            $components['zlib'] = [
                'name' => $this->get('translator')->trans('zlib (optional)'),
                'status' => 'ok',
                'required' => false
            ];
        } else {
            $components['zlib'] = [
                'name' => $this->get('translator')->trans('zlib (optional)'),
                'status' => 'fail',
                'required' => false
            ];
        }

        if (function_exists('xdebug_get_headers') === true) {
            $components['xdebug'] = [
                'name' => $this->get('translator')->trans('Xdebug (optional)'),
                'status' => 'ok',
                'required' => false
            ];
        } else {
            $components['xdebug'] = [
                'name' => $this->get('translator')->trans('Xdebug (optional)'),
                'status' => 'fail',
                'required' => false
            ];
        }

        if (class_exists('Memcache') === true) {
            $components['memcache'] = [
                'name' => $this->get('translator')->trans('Memcache (optional)'),
                'status' => 'ok',
                'required' => false
            ];
        } else {
            $components['memcache'] = [
                'name' => $this->get('translator')->trans('Memcache (optional)'),
                'status' => 'fail',
                'required' => false
            ];
        }

        if (class_exists('Memcached') === true) {
            $components['memcached'] = [
                'name' => $this->get('translator')->trans('Memcached (optional)'),
                'status' => 'ok',
                'required' => false
            ];
        } else {
            $components['memcached'] = [
                'name' => $this->get('translator')->trans('Memcached (optional)'),
                'status' => 'fail',
                'required' => false
            ];
        }

        if (class_exists('Redis') === true) {
            $components['redis'] = [
                'name' => $this->get('translator')->trans('Redis (optional)'),
                'status' => 'ok',
                'required' => false
            ];
        } else {
            $components['redis'] = [
                'name' => $this->get('translator')->trans('Redis (optional)'),
                'status' => 'fail',
                'required' => false
            ];
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
