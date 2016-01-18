<?php 

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2015 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

defined('_JEXEC') or die;

class nrFrameworkAssignmentsHelper {
	
	var $db = null;
	var $init = false;
	var $types = array();

	public function __construct()
	{
		$this->db = JFactory::getDBO();
		$this->types = array(
			'devices'        => 'Devices',
			'urls'           => 'URls',
			'lang'           => 'Languages',
			'php'            => 'PHP',
			'timeonsite'     => 'Users.TimeOnSite',
			'usergroups'     => 'Users.GroupLevels',
			'menu'           => 'Menu',
			'datetime'       => 'DateTime.Date'
		);
	}

	function getItemAssignments($item) {

		if (!$item) {
			return;
		}

		$params = json_decode($item->params);

		if (!is_object($params)) {
			return;
		}

		$types = array();
		foreach ($this->types as $id => $type) {

			if (!isset($params->{'assign_' . $id}) || !$params->{'assign_' . $id})
			{
				continue;
			}

			// Discover assignment params
			$AssignmentParams = new stdClass();
			foreach ($params as $key => $value) {
				if (strpos($key, "assign_".$id."_param") !== false) {
					$AssignmentParams->$key = $value; 
				}
			}

			$types[$type] = (object) array(
				'assignment' => $this->getAssignmentState($params->{'assign_' . $id}),
				'selection'  => array(),
				'params'  => $AssignmentParams
			);

			if (isset($params->{'assign_' . $id . '_list'}))
			{
				$selection = $params->{'assign_' . $id . '_list'};
				$types[$type]->selection = $selection;
			}
		}

		return $types;
	}

	function passSimple($values, $selection) {

		$values = $this->makeArray($values);

		$pass = false;
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

	function makeArray($object) {

		if (is_array($object)) {
			return $object;
		}

		if (!is_array($object)) {
			$x = explode(" ", $object);
			return $x;
		}
	}

	function passAll($item, $match_method = 'and') {
		
		if (!$item) {
			return 1;
		}

	    $assignments = $this->getItemAssignments($item);

	    if (!is_array($assignments)) {
	    	return 1;
	    }

		jimport('joomla.filesystem.file');

		$pass = (bool) ($match_method == 'and');

		foreach ($this->types as $type)
		{

			// Break if not passed and matching method is ALL
			// Or if  passed and matching method is ANY
			if (
				(!$pass && $match_method == 'and')
				|| ($pass && $match_method == 'or')
			)
			{
				break;
			}

			if (!isset($assignments[$type])) {
				continue;
			}

			$pass = $this->passByType($assignments[$type], $type);
			$pass = $this->pass($pass, $assignments[$type]->assignment);
		}

		return $pass;
	}

    public static function pass($pass = true, $assignment = 'include')
    {
        return $pass ? ($assignment == 'include') : ($assignment == 'exclude');
    }

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

	function initParamsByType(&$assignment, $type = '') {
		if (strpos($type, '.') === false)
		{
			$assignment->maintype = $type;
			$assignment->subtype = $type;

			return;
		}

		$type = explode('.', $type, 2);
		$assignment->maintype = $type['0'];
		$assignment->subtype = $type['1'];
	}

	private function passByType($assignment, $type) {

		$this->initParamsByType($assignment, $type);

		$main_type = $assignment->maintype;
		$sub_type = $assignment->subtype;

		$pass = false;

		$file = __DIR__ . "/".strtolower($main_type) . '.php';
		$class = 'nrFrameworkAssignments' . $main_type;
		$method = 'pass' . $sub_type;

        if ((!class_exists($class)) && JFile::exists($file)) {
            require_once($file);
        }

		if (class_exists($class))
		{
			if (method_exists($class, $method))
			{
				$cl = new $class($assignment);
				$pass = $cl->$method();
			}
		}

		return $pass;
	}
}


?>