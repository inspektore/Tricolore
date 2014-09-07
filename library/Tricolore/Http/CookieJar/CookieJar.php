<?php
namespace Tricolore\Http\CookieJar;

use Tricolore\Config\Config;

class CookieJar
{
    /**
     * Cookie options
     * 
     * @var array
     */
    private $options;

    /**
     * Set cookie path
     * 
     * @param string $path 
     * @return Tricolore\Http\CookieJar\CookieJar
     */
    public function setPath($path)
    {
        $this->options['path'] = $path;

        return $this;
    }

    /**
     * Set cookie domain
     * 
     * @param string $domain 
     * @return Tricolore\Http\CookieJar\CookieJar
     */
    public function setDomain($domain)
    {
        $this->options['domain'] = $domain;

        return $this;
    }

    /**
     * Cookie secure
     * 
     * @param bool $secure
     * @return Tricolore\Http\CookieJar\CookieJar
     */
    public function isSecure($secure)
    {
        $this->options['secure'] = $secure;

        return $this;
    }

    /**
     * Set new cookie
     * 
     * @param string $name
     * @param string $value 
     * @param int $expire
     * @return bool
     */
    public function set($name, $value, $expire = 86400)
    {
        if(isset($this->options['path']) === false) {
            $this->options['path'] = Config::key('cookie.path');
        }

        if(isset($this->options['domain']) === false) {
            $this->options['domain'] = Config::key('cookie.domain');
        }

        if(isset($this->options['secure']) === false) {
            $this->options['secure'] = Config::key('cookie.secure');
        }

        return setcookie($name, $value, time() + $expire, 
            $this->options['path'], 
            $this->options['domain'], 
            $this->options['secure']
        );
    }

    /**
     * Get existing cookie
     * 
     * @param string $name
     * @return string|bool
     */
    public function get($name)
    {
        if(isset($_COOKIE[$name])) {
            return trim(str_replace(["\0", "\n", "\t", "\s"], '', $_COOKIE[$name]));
        }

        return false;
    }

    /**
     * Destroy cookie
     * 
     * @param string $name
     * @return bool
     */
    public function destroy($name)
    {
        if(isset($_COOKIE[$name])) {
            unset($_COOKIE[$name]);
        }

        if(isset($this->options['path']) === false) {
            $this->options['path'] = '';
        }

        if(isset($this->options['domain']) === false) {
            $this->options['domain'] = '';
        }

        if(isset($this->options['secure']) === false) {
            $this->options['secure'] = false;
        }

        return setcookie($name, null, -1, 
            $this->options['path'], 
            $this->options['domain'], 
            $this->options['secure']
        );
    }
}
