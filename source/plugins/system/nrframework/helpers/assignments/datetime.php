<?php

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2015 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

defined('_JEXEC') or die;

require_once dirname(__DIR__) . '/assignment.php';

class nrFrameworkAssignmentsDateTime extends NRAssignment
{
	/**
	 *  Checks if current date passes date range
	 *
	 *  @return  bool
	 */
	function passDate()
	{
		$tz = new DateTimeZone($this->app->getCfg('offset'));

		$publish_up   = $this->params->assign_datetime_param_publish_up;
		$publish_down = $this->params->assign_datetime_param_publish_down;

		// No valid dates
		if (!$publish_up && !$publish_down)
		{
			return false;
		}

		NRFrameworkFunctions::fixDate($publish_up);
		NRFrameworkFunctions::fixDate($publish_down);

		$now  = $this->getNow();
		$up   = JFactory::getDate($publish_up)->setTimeZone($tz);
		$down = JFactory::getDate($publish_down)->setTimeZone($tz);

		// Out of range
		if (((int) $publish_up   && strtotime($up->format('Y-m-d H:i:s', true)) > $now) ||
			((int) $publish_down && strtotime($down->format('Y-m-d H:i:s', true)) < $now))
		{
			return false;
		}

		// Pass
		return true;
	}

	/**
	 *  Returns current date time
	 *
	 *  @return  string
	 */
	private function getNow()
	{
		return strtotime($this->date->format('Y-m-d H:i:s', true));
	}

}
