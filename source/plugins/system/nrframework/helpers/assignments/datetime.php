<?php

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2015 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

defined('_JEXEC') or die;

class nrFrameworkAssignmentsDateTime extends nrFrameworkAssignmentsHelper
{

	private $assignment;
	private $params;
	private $tz;
	private $date;

	function __construct($assignment) {
    	$this->assignment = $assignment;
    	$this->params = $assignment->params;
    	$this->tz = new DateTimeZone(JFactory::getApplication()->getCfg('offset'));
    	$this->date = JFactory::getDate()->setTimeZone($this->tz);
   	}

	function passDate()
	{
		$this->params->publish_up = $this->params->assign_datetime_param_publish_up;
		$this->params->publish_down = $this->params->assign_datetime_param_publish_down;

		if (!$this->params->publish_up && !$this->params->publish_down)
		{
			// no date range set
			return ($this->assignment->assignment == 'include');
		}

		NRFrameworkFunctions::fixDate($this->params->publish_up);
		NRFrameworkFunctions::fixDate($this->params->publish_down);

		$now = strtotime($this->date->format('Y-m-d H:i:s', true));
		$up = JFactory::getDate($this->params->publish_up)->setTimeZone($this->tz);
		$down = JFactory::getDate($this->params->publish_down)->setTimeZone($this->tz);

		if (
			(
				(int) $this->params->publish_up
				&& strtotime($up->format('Y-m-d H:i:s', true)) > $now
			)
			|| (
				(int) $this->params->publish_down
				&& strtotime($down->format('Y-m-d H:i:s', true)) < $now
			)
		)
		{
			// outside date range
			return $this->pass(false);
		}

		// pass
		return ($this->assignment->assignment == 'include');
	}
}
