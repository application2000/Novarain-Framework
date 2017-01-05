<?php

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2015 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

defined('_JEXEC') or die;

require_once __DIR__ . '/cache.php';

/**
 *  Assignment Class
 */
class NRAssignment
{
	/**
	 *  Application Object
	 *
	 *  @var  object
	 */
	public $app;

	/**
	 *  Document Object
	 *
	 *  @var  object
	 */
	public $doc;

	/**
	 *  Request Object
	 *
	 *  @var  object
	 */
	public $request;

	/**
	 *  Date Object
	 *
	 *  @var  object
	 */
	public $date;

	/**
	 *  Database Object
	 *
	 *  @var  object
	 */
	public $db;

	/**
	 *  User Object
	 *
	 *  @var  object
	 */
	public $user;

	/**
	 *  Assignment Selection
	 *
	 *  @var  mixed
	 */
	public $selection;

	/**
	 *  Assignment Parameters
	 *
	 *  @var  mixed
	 */
	public $params;

	/**
	 *  Assignment State (Include|Exclude)
	 *
	 *  @var  string
	 */
	public $assignment;

	/**
	 *  Class constructor
	 *
	 *  @param  object  $assignment
	 *  @param  object  $request     
	 *  @param  object  $date        
	 */
	public function __construct($assignment, $request = null, $date = null)
	{
		// Set General Joomla Objects
		$this->db   = JFactory::getDbo();
		$this->app  = JFactory::getApplication();
		$this->doc  = JFactory::getDocument();
		$this->user = JFactory::getUser();

		// Set Assignment Options
		$this->params     = $assignment->params;
		$this->selection  = $assignment->selection;
		$this->assignment = $assignment->assignment;

		// Set Request object
		if (is_null($request))
		{
			$request = new stdClass;

			$request->view   = $this->app->input->get("view");
			$request->task   = $this->app->input->get("task");
			$request->option = $this->app->input->get("option");
			$request->layout = $this->app->input->get('layout', '', 'string');
			$request->id     = $this->app->input->get("id");
			$request->Itemid = $this->app->input->getInt('Itemid', 0);
		}

		$this->request = $request;

		// Set date object
		if (is_null($date))
		{
			$tz   = new DateTimeZone(JFactory::getApplication()->getCfg('offset'));
			$date = JFactory::getDate()->setTimeZone($tz);
		}

		$this->date = $date;
	}

	/**
	 *  Makes a simple assignment check
	 *
	 *  @param   mixed   $values     Current state
	 *  @param   string  $selection  User's selection
	 *
	 *  @return  bool
	 */
	public function passSimple($values, $selection)
	{
		if (empty($selection))
		{
			return false;
		}
		
		$values = $this->makeArray($values);
		$pass   = false;

		foreach ($values as $value)
		{
			if (in_array(strtolower($value), array_map('strtolower', $selection)))
			{
				$pass = true;
				break;
			}
		}

		return $pass;
	}

	/**
	 *  Makes array from object
	 *
	 *  @param   object  $object  
	 *
	 *  @return  array
	 */
	public function makeArray($object)
	{
		if (is_array($object))
		{
			return $object;
		}

		if (!is_array($object))
		{
			$x = explode(" ", $object);
			return $x;
		}
	}

	/**
	 *  Returns paramteres of the active menu item
	 *
	 *  @param   integer  $id  Menu Item
	 *
	 *  @return  array         Menu Item parameters
	 */
	public function getMenuItemParams($id = 0)
	{
		$hash = md5('getMenuItemParams_' . $id);

		if (NRCache::has($hash))
		{
			return NRCache::get($hash);
		}

		$query = $this->db->getQuery(true)
			->select('m.params')
			->from('#__menu AS m')
			->where('m.id = ' . (int) $id);

		$this->db->setQuery($query);
		$params = $this->db->loadResult();
		
		return NRCache::set($hash, json_decode($params));
	}

	/**
	 *  Returns all parent rows
	 *
	 *  @param   integer  $id      Row primary key
	 *  @param   string   $table   Table name
	 *  @param   string   $parent  Parent column name
	 *  @param   string   $child   Child column name
	 *
	 *  @return  array             Array with IDs
	 */
	public function getParentIDs($id = 0, $table = 'categories', $parent = 'parent_id', $child = 'id')
	{
		if (!$id)
		{
			return array();
		}

		$hash = md5('getParentIDs_' . $id . '_' . $table . '_' . $parent . '_' . $child);

		if (NRCache::has($hash))
		{
			return NRCache::get($hash);
		}

		$parent_ids = array();

		while ($id)
		{
			$query = $this->db->getQuery(true)
				->select('t.' . $parent)
				->from('#__' . $table . ' as t')
				->where('t.' . $child . ' = ' . (int) $id);
			$this->db->setQuery($query);

			$id = $this->db->loadResult();

			if (!$id || in_array($id, $parent_ids))
			{
				break;
			}

			$parent_ids[] = $id;
		}

		return NRCache::set($hash, $parent_ids);
	}
}

?>