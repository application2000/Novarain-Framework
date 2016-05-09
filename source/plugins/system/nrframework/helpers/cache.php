<?php

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2015 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

defined('_JEXEC') or die;

class NRCache
{
	static $cache = array();

	static public function has($hash)
	{
		return isset(self::$cache[$hash]);
	}

	static public function get($hash)
	{
		if (!self::has($hash))
		{
			return false;
		}

		return is_object(self::$cache[$hash]) ? clone self::$cache[$hash] : self::$cache[$hash];
	}

	static public function set($hash, $data)
	{
		self::$cache[$hash] = $data;
		return $data;
	}

	static public function read($hash, $force = false)
	{
		if (self::has($hash))
		{
			return self::get($hash);
		}

		$cache = JFactory::getCache('novarain', '');

		if ($force)
		{
			$cache->setCaching(true);
		}

		return $cache->get($hash);
	}

	static public function write($hash, $data, $ttl = 0)
	{
		$cache = JFactory::getCache('novarain','');

		if ($ttl)
		{
			$cache->setLifeTime($ttl * 60);
		}

		$cache->setCaching(true);
		$cache->store($data, $hash);

		self::set($hash, $data);

		return $data;
	}
}
