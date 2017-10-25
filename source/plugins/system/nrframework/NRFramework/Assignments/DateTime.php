<?php

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2017 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/
namespace NRFramework\Assignments;

defined('_JEXEC') or die;

use NRFramework\Assignment;

class DateTime extends Assignment
{
	/**
	 * Server's Timezone
	 *
	 * @var DateTimeZone
	 */
	private $tz;

	/**
	 *  Class constructor
	 *
	 *  @param  object  $assignment
	 */
	public function __construct($assignment)
	{
		parent::__construct($assignment);

		$this->tz = new \DateTimeZone($this->app->getCfg('offset'));
	}
	/**
	 *  Checks if current date passes date range
	 *
	 *  @return  bool
	 */
	function passDate()
	{
		$publish_up   = $this->params->assign_datetime_param_publish_up;
		$publish_down = $this->params->assign_datetime_param_publish_down;

		// No valid dates
		if (!$publish_up && !$publish_down)
		{
			return false;
		}

		\NRFramework\Functions::fixDate($publish_up);
		\NRFramework\Functions::fixDate($publish_down);

		$now  = $this->getNow();
		$up   = \JFactory::getDate($publish_up)->setTimeZone($this->tz);
		$down = \JFactory::getDate($publish_down)->setTimeZone($this->tz);

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
	 * Checks if current time passes the given time range
	 *
	 * @return bool
	 */
	public function passTimeRange()
	{
		list($up_hours, $up_mins) = explode(':', $this->params->assign_timerange_param_publish_up);
		list($down_hours, $down_mins) = explode(':', $this->params->assign_timerange_param_publish_down);


		$up = \JFactory::getDate()->setTimezone($this->tz)->setTime($up_hours, $up_mins);
		$down = \JFactory::getDate()->setTimezone($this->tz)->setTime($down_hours, $down_mins);
		$now = $this->getNow();

		return $this->checkRange($up, $down);
	}

	/**
	 *  Returns current date time
	 *
	 *  @return  string
	 */
	private function getNow()
	{
		return strtotime($this->date->setTimezone($this->tz)->format('Y-m-d H:i:s', true));
	}


	/**
	 * Checks if the current datetime is between the specified range
	 *
	 * @param JDate $up_date
	 * @param JDate $down_date
	 * 
	 * @return bool
	 */
	private function checkRange($up_date, $down_date)
	{
		$now = $this->getNow();

		if (strtotime($up_date->format('Y-m-d H:i:s', true)) > $now ||
			strtotime($down_date->format('Y-m-d H:i:s', true)) < $now)
		{
			return false;
		}

		return true;

	}

}
