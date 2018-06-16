<?php

use \NRFramework\CacheManager;

class CacheManagerTest extends PHPUnit\Framework\TestCase
{
    public $cache = null;

    public function cacheData()
    {
        // hash -> value
        return [
            ['hash1', 'data1'],
            ['hash2', 'data2'],
            ['hash3', 'data3'],
            ['hash4', 'data4'],
            ['hash5', 'data5'],
        ];
    }

    public function setUp()
    {
        $this->cache = CacheManager::getInstance(\JFactory::getCache('novarain',''));
    }

    public function testGetInstance()
    {
        $inst1 = CacheManager::getInstance(null);
        $inst2 = CacheManager::getInstance(null);

        $this->assertEquals($inst1, $inst2);
    }

    /**
     * @dataProvider cacheData
     */
    public function testGet($hash, $value)
    {
        $this->cache->set($hash, $value);
        $this->assertEquals($this->cache->get($hash), $value);
    }

    /**
     * @dataProvider cacheData
     */
    public function testHas($hash, $value)
    {
        $this->cache->set($hash, $value);
        $this->assertEquals($this->cache->has($hash), true);
    }

    /**
     * @dataProvider cacheData
     */
    // public function testRead($hash, $value)
    // {
    //     $this->cache->write($hash, $value);
    //     $this->assertEquals($this->cache->read($hash), $value);
    // }

}