<?php
namespace Tricolore\Tests;

use Tricolore\Config\Config;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    public function testGetKey()
    {
        $expected = 'utf-8';
        $actual = Config::getParameter('base.charset');

        $this->assertEquals($expected, $actual);

        $expected = false;
        $actual = Config::getParameter('router.use_httpd_rewrite');

        $this->assertEquals($expected, $actual);

        $expected = 5874;
        $actual = Config::getParameter('test.int');

        $this->assertEquals($expected, $actual);
    }

    public function testGetAll()
    {
        $expected = [
            'base.full_url' => 'http://localhost/Tricolore/tests',
            'base.locale' => 'en',
            'base.charset' => 'utf-8',
            'base.timezone' => 'Europe/Warsaw',
            'router.use_httpd_rewrite' => false,
            'trans.locale' => 'en_EN',
            'test.int' => 5874,
            'directory.storage' => 'storage',
            'directory.assets' => 'static'
        ];
        $actual = Config::all('Configuration')['test'];

        $this->assertEquals($expected, $actual);
    }

    public function testNotExistingCollection()
    {
        $actual = Config::getParameter('key', 'NotExistingCollection');

        $this->assertFalse($actual);
    }

    public function testNotExistingKey()
    {
        $actual = Config::getParameter('Not.ExistingKey');

        $this->assertFalse($actual);
    }

    public function testGetAllNotExistingCollection()
    {
        $expected = [];
        $actual = Config::all('NotExistingCollection');

        $this->assertEquals($expected, $actual);
    }
}
