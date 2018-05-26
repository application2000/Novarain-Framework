<?php

class WrapperTestCase extends PHPUnit\Framework\TestCase
{
    static $wrapper;

    static function setUpBeforeClass()
    {
        self::loadWrapperClass();
    }

    static function loadWrapperClass()
    {   
        $name = str_replace('Test', '', get_called_class());

        // Load wrapper Class
        JLoader::register('NR_' . $name, JPATH_PLUGINS . '/system/nrframework/helpers/wrappers/' . strtolower($name) . '.php');

        // Construct Class
        $class_name = '\NR_' . $name;

        static::$wrapper = new $class_name(static::$credentials);

        return static::$wrapper;
    }

    public function getFirstListID()
    {
        $lists = self::$wrapper->getLists();
        return (is_array($lists) && isset($lists[0]['id'])) ? $lists[0]['id'] : false;
    }

    public function assertSubscribe($expected)
    {
        $wrapper = self::$wrapper;

        // If it's a string it's probably an error.
        if (is_string($expected))
        {
            $this->assertContains($expected, $wrapper->getLastError());
        } else 
        {
            $this->assertTrue($wrapper->success());
        }
    }
}