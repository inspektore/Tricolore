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
            $components[] = [
                'name' => $this->get('translator')->trans('PHP 5.5 (current %version%)', [
                    '%version%' => phpversion()
                ]),
                'status' => 'ok',
                'required' => true
            ];
        } else {
            $components[] = [
                'name' => $this->get('translator')->trans('PHP 5.5 (current %version%)', [
                    '%version%' => phpversion()
                ]),
                'status' => 'fail',
                'required' => true
            ];
        }
        
        if (extension_loaded('intl') === true) {
            $components[] = [
                'name' => $this->get('translator')->trans('INTL extension'),
                'status' => 'ok',
                'required' => true
            ];
        } else {
            $components[] = [
                'name' => $this->get('translator')->trans('INTL extension'),
                'status' => 'fail',
                'required' => true
            ];
        }

        if (extension_loaded('pdo_pgsql') === true) {
            $components[] = [
                'name' => $this->get('translator')->trans('Enabled pdo_pgsql extension'),
                'status' => 'ok',
                'required' => true
            ];
        } else {
            $components[] = [
                'name' => $this->get('translator')->trans('Enabled pdo_pgsql extension'),
                'status' => 'fail',
                'required' => true
            ];
        }

        if (extension_loaded('zlib') === true) {
            $components[] = [
                'name' => $this->get('translator')->trans('zlib (optional)'),
                'status' => 'ok',
                'required' => false
            ];
        } else {
            $components[] = [
                'name' => $this->get('translator')->trans('zlib (optional)'),
                'status' => 'fail',
                'required' => false
            ];
        }

        if (function_exists('xdebug_get_headers') === true) {
            $components[] = [
                'name' => $this->get('translator')->trans('Xdebug (optional)'),
                'status' => 'ok',
                'required' => false
            ];
        } else {
            $components[] = [
                'name' => $this->get('translator')->trans('Xdebug (optional)'),
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
