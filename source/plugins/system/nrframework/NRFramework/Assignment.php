<?php

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2018 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

namespace NRFramework;

defined('_JEXEC') or die;

use \NRFramework\Cache;

/**
 *  Assignment Class
 */
class Assignment
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
     *  Framework factory object
     */
    public $factory;

	/**
	 *  Class constructor
	 *
	 *  @param  object  $assignment
	 *  @param  object  $request
	 *  @param  object  $date
	 */
	public function __construct($options, $factory)
	{
        // Save the factory object
        $this->factory = $factory;

		// Set General Joomla Objects
		$this->db   = $factory->getDbo();
		$this->app  = $factory->getApplication();
		$this->doc  = $factory->getDocument();
		$this->user = $factory->getUser();

		// Set Assignment Options
		$this->selection        = $options->selection;
		$this->assignment_state = isset($options->assignment_state) ? $options->assignment_state : 'include';
		$this->params           = isset($options->params) ? $options->params : null;
    }
    
    /**
     *  Base assignment check
     * 
     *  @return bool
     */
	public function pass()
	{
    	return $this->passSimple($this->value(), $this->selection);
	}

	/**
	 *  Makes a simple assignment check
	 *
	 *  @param   mixed   $values     Current state
	 *  @param   array  $selection   User's selection
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
			$x = explode(' ', $object);
			return $x;
		}
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
	public function getParentIds($id = 0, $table = 'menu', $parent = 'parent_id', $child = 'id')
	{
		if (!$id)
		{
			return [];
		}

		$cache = $this->factory->getCache(); 
		$hash  = md5('getParentIds_' . $id . '_' . $table . '_' . $parent . '_' . $child);

		if ($cache->has($hash))
		{
			return $cache->get($hash);
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

			// Break if no parent is found or parent already found before for some reason
			if (!$id || in_array($id, $parent_ids))
			{
				break;
			}

			$parent_ids[] = $id;
		}

		return $cache->set($hash, $parent_ids);
	}
	
	/**
	 * Checks whether the current page is within the selected categories
	 *
	 * @param	string	   $ref_table				The referenced table
	 * @param	string	   $ref_parent_column		The name of the parent column in the referecned table
	 * @param	boolean	   $inc_categories			Indicates whether the Category view should me included in the check
	 * @param	boolean    $inc_items				Indicates whether the Item view should me included in the check
	 * 
	 * @return	boolean
	 */
    protected function passComponentCategories($ref_table = 'categories', $ref_parent_column = 'parent_id', $inc_categories = false, $inc_items = true)
    {
		// Include Children switch: 0 = No, 1 = Yes, 2 = Child Only
		$inc_children = $this->params->inc_children;

		// Check whether we support the Category and the Item views
		if (isset($this->params->inc) && is_array($this->params->inc))
		{
			$inc_categories = in_array('inc_categories', $this->params->inc);
			$inc_items      = in_array('inc_items', $this->params->inc);
		}

		// Check if we are in a valid context
		if (!($inc_categories && $this->isCategory()) && !($inc_items && $this->isItem()))
		{
			return false;
		}

		// Start Checks
		$pass = false;

		// Get current page assosiated category IDs. It can be a single ID of the current Category view or multiple IDs assosiated to active item.
		$catids = $this->getCategoryIds();
		$catids = is_array($catids) ? $catids : (array) $catids;

		foreach ($catids as $catid)
		{
			$pass = in_array($catid, $this->selection);

			if ($pass)
			{
				// If inc_children is either disabled or set to 'Also on Childs', there's no need for further checks. 
				// The condition is already passed.
				if (in_array($this->params->inc_children, [0, 1]))
				{
					break;
				}

				// We are here because we need childs only. Disable pass and continue checking parent IDs.
				$pass = false;
			}

			// Pass check for child items
			if (!$pass && $this->params->inc_children)
			{
				$parent_ids = $this->getParentIDs($catid, $ref_table, $ref_parent_column);

				foreach ($parent_ids as $id)
				{
					if (in_array($id, $this->selection))
					{
						$pass = true;
						break 2;
					}
				}

				unset($parent_ids);
			}
		}

		return $pass;
	}
    
    /**
     *  Splits a keyword string on commas and newlines
     *
     *  @param string $keywords
     *  @return array
     */
    protected function splitKeywords($keywords)
    {
        if (empty($keywords) || !is_string($keywords))
        {
            return [];
        }

        // replace newlines with commas
        $keywords = str_replace("\r\n", ',', $keywords);

        // split keywords on commas
        $keywords = explode(',', $keywords);
        
        // trim entries
        $keywords = array_map(function($str)
        {
            return trim($str);
        }, $keywords);

        // filter out empty strings and return the resulting array
        return array_filter($keywords, function($str)
        {
            return !empty($str);
        });
    }
}
