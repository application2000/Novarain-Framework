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
		$publish_up   = $this->params->publish_up;
		$publish_down = $this->params->publish_down;

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
		list($up_hours, $up_mins) = explode(':', $this->params->publish_up);
        if (!is_null($this->params->publish_down))
        {
            list($down_hours, $down_mins) = explode(':', $this->params->publish_down);
        }

		$up = \JFactory::getDate()->setTimezone($this->tz)->setTime($up_hours, $up_mins);
		$down = is_null($this->params->publish_down) ? null : \JFactory::getDate()->setTimezone($this->tz)->setTime($down_hours, $down_mins);

		return $this->checkRange($up, $down);
    }
    
    /**
     * Check current weekday
     *
     * @return bool
     */
    public function passDays()
    {
        if (is_array($this->selection) && !empty($this->selection))
        {
            // 'N' -> week day
            // 'l' -> fulltext week day
            // http://php.net/manual/en/function.date.php
            $today      = date('N');
            $todayText  = date('l');
            if (in_array($today, $this->selection) ||
                in_array($todayText, $this->selection))
            {
                return true;
            }
        }      

        return false;
    }

    /**
     * Check current month
     *
     * @return void
     */
    public function passMonths()
    {
        if (is_array($this->selection) && !empty($this->selection))
        {
            // 'n' -> month number (1 to 12)
            // 'F' -> full-text month name
            // http://php.net/manual/en/function.date.php
            $month = date('n');
            $monthText = date('F');
            if (in_array($month, $this->selection) ||
                in_array($monthText, $this->selection))
            {
                return true;
            }
        }      

        return false;
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

		if (((bool)$up_date && strtotime($up_date->format('Y-m-d H:i:s', true)) > $now) ||
			((bool)$down_date && strtotime($down_date->format('Y-m-d H:i:s', true)) < $now))
		{
			return false;
		}

		return true;

	}

}
