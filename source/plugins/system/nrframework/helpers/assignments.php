<?php

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2015 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

defined('_JEXEC') or die;

jimport('joomla.filesystem.file');

/**
 *  Novarain Framework Assignments Helper Class
 */
class nrFrameworkAssignmentsHelper
{
	/**
	 *  Assignment Types
	 *
	 *  @var  array
	 */
	public $types = array(
		'devices'         => 'Devices',
		'urls'            => 'URLs',
		'referrer'        => 'URLs.Referrer',
		'lang'            => 'Languages',
		'php'             => 'PHP',
		'timeonsite'      => 'Users.TimeOnSite',
		'usergroups'      => 'Users.GroupLevels',
		'menu'            => 'Menu',
		'datetime'        => 'DateTime.Date',
		'acymailing'      => 'AcyMailing',
		'akeebasubs'      => 'AkeebaSubs',
		'contentcats'     => 'Content.Categories',
		'contentarticles' => 'Content.Articles',
		'convertforms'	  => 'ConvertForms'
	);

	/**
	 *  Pass Check Item's All Assignments
	 *
	 *  @param   object  $item          The item to be checked
	 *  @param   string  $match_method  The matching method (and|or)
	 *
	 *  @return  bool                   True if check passes
	 */
	function passAll($item, $match_method = 'and')
	{
		if (!$item)
		{
			return true;
		}

		$assignments = $this->getItemAssignments($item);

		if (!is_array($assignments) || count($assignments) == 0)
		{
			return true;
		}

		$pass = (bool) ($match_method == 'and');

		foreach ($this->types as $type)
		{
			// Break if not passed and matching method is ALL
			// Or if passed and matching method is ANY
			if (
				(!$pass && $match_method == 'and')
				|| ($pass && $match_method == 'or')
			)
			{
				break;
			}

			if (!isset($assignments[$type]))
			{
				continue;
			}

			$pass = $this->passByType($assignments[$type], $type);
			$pass = $this->pass($pass, $assignments[$type]->assignment);
		}

		return $pass;
	}

	/**
	 *  Assignment pass check basedon the assignment state
	 *
	 *  @param   boolean  $pass        
	 *  @param   string   $assignment  The assignment state
	 *
	 *  @return  boolean
	 */
	private function pass($pass = true, $assignment = null)
	{
		$assignment = $assignment ?: $this->assignment;
		return $pass ? ($assignment == 'include') : ($assignment == 'exclude');
	}

	/**
	 *  Setups an object with item's valid assignments only
	 *
	 *  @param   object  $item  The item to be checked
	 *
	 *  @return  object
	 */
	function getItemAssignments($item)
	{
		if (!$item)
		{
			return;
		}

		$params = json_decode($item->params);

		if (!is_object($params))
		{
			return;
		}

		$types = array();
		foreach ($this->types as $id => $type)
		{
			if (!isset($params->{'assign_' . $id}) || !$params->{'assign_' . $id})
			{
				continue;
			}

			// Discover assignment params
			$AssignmentParams = new stdClass();
			foreach ($params as $key => $value)
			{
				if (strpos($key, "assign_" . $id . "_param") !== false)
				{
					$AssignmentParams->$key = $value;
				}
			}

			$types[$type] = (object) array(
				'itemid'     => (int) $item->id,
				'assignment' => $this->getAssignmentState($params->{'assign_' . $id}),
				'selection'  => array(),
				'params'     => $AssignmentParams,
			);

			if (isset($params->{'assign_' . $id . '_list'}))
			{
				$selection = $params->{'assign_' . $id . '_list'};
				$types[$type]->selection = $selection;
			}
		}

		return $types;
	}

	/**
	 *  Returns assignment's state by ID
	 *  1: Include
	 *  2: Exclude
	 *  3, -1: None
	 *
	 *  @param   integer  $assignment  Assignment's state ID
	 *
	 *  @return  string                Assignment's state name
	 */
	private function getAssignmentState($assignment)
	{
		switch ($assignment)
		{
			case 1:
			case 'include':
				$assignment = 'include';
				break;
			case 2:
			case 'exclude':
				$assignment = 'exclude';
				break;
			case 3:
			case -1:
			case 'none':
				$assignment = 'none';
				break;
			default:
				$assignment = 'all';
				break;
		}

		return $assignment;
	}

	/**
	 *  Pass check by assignment type
	 *
	 *  @param   object  $assignment  The assignment object
	 *  @param   string  $type        The assignment type
	 *
	 *  @return  bool
	 */
	protected function passByType($assignment, $type)
	{
		$this->initParamsByType($assignment, $type);

		// Load assignment file. If it doesn't exist fails silently.
		if (!@include_once(__DIR__ . "/assignments/" . strtolower($assignment->maintype) . '.php'))
		{
			return false;
		}

		// Validate assignment class and method
		$class  = 'nrFrameworkAssignments' .  $assignment->maintype;
		$method = 'pass' . $assignment->subtype;

		if (!class_exists($class) && !method_exists($class, $method))
		{
			return false;
		}

		// Run Pass Check
		$pass = false;
		$cl   = new $class($assignment);
		$pass = $cl->$method();	

		return $pass;
	}

	/**
	 *  Sets propert assignment class and method name
	 *
	 *  @param   object  &$assignment  The assignment object
	 *  @param   string  $type         The assignment type
	 *
	 *  @return  void
	 */
	public function initParamsByType(&$assignment, $type = '')
	{
		if (strpos($type, '.') === false)
		{
			$assignment->maintype = $type;
			$assignment->subtype  = $type;

			return;
		}

		$type = explode('.', $type, 2);
		$assignment->maintype = $type['0'];
		$assignment->subtype  = $type['1'];
	}
}

?>