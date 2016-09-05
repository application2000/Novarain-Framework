<?php

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2015 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

defined('_JEXEC') or die;

require_once(JPATH_PLUGINS . "/system/rstbox/log.php");

class nrFrameworkAssignmentsImpressions extends nrFrameworkAssignmentsHelper
{
	private $assignment;

	function __construct($assignment) {
    	$this->assignment = $assignment;
    	$this->range = $assignment->params->assign_impressions_param_type;

		parent::__construct();
   	}

	function passImpressions()
	{

		$query = $this->db->getQuery(true);

		$query
		    ->select('COUNT(id)')
		    ->from($this->db->quoteName('#__rstbox_logs'))
		    ->where($this->db->quoteName('event') . ' = 1')
		    ->where($this->db->quoteName('box') . ' = ' . $this->assignment->itemid);

		if ($this->range == "session")
		{
			$query->where($this->db->quoteName('sessionid') . ' = '. $this->db->quote(JFactory::getSession()->getId()));
		} else
		{
			$log = new eBoxLog();
			$query->where($this->db->quoteName('visitorid') . ' = '. $this->db->quote($log->getToken()));
		}

		switch ($this->range)
		{
			case 'hour':
				$query->where('HOUR(date) = ' . $this->date->format("H"));
				break;
			case 'day':
				$query->where('DAY(date) = ' . $this->date->format("d"));
				break;
			case 'week':
				$query->where('YEARWEEK(date) = ' . $this->date->format("oW"));
				break;
			case 'month':
				$query->where('MONTH(date) = ' . $this->date->format("m"));
				break;
			case 'year':
				$query->where('YEAR(date) = ' . $this->date->format("Y"));
				break;
		}

		$this->db->setQuery($query);
		 
		$total  = (int) $this->db->loadResult();
		$result = (int) $this->assignment->selection > $total;

		// echo "Box id: #" . $this->assignment->itemid . "<br>";
		// echo "Assignment Selection: " . (int) $this->assignment->selection . "<br>";
		// echo "Assignment Type: " . $this->range . "<br>";
		// echo "DB Count: " . $total . "<br>";
		// echo "Result: " . (bool) $result . "<br>";
		// echo "Query: " . (string) $query;

		return ($result);
	}
}
