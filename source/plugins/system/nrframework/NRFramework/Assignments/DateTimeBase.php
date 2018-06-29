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

class DateTimeBase extends Assignment
{
	/**
	 * Server's Timezone
	 *
	 * @var DateTimeZone
	 */
	protected $tz;

	/**
	 *  Class constructor
	 *
	 *  @param  object  $assignment
	 */
	public function __construct($assignment, $factory)
	{
		parent::__construct($assignment, $factory);

		if (property_exists($assignment->params, "timezone"))
		{
			$this->tz =  new \DateTimeZone($assignment->params->timezone);
		}
		else
		{
			$this->tz   = new \DateTimeZone($this->app->getCfg('offset'));
		}
        
        $this->date = $factory->getDate()->setTimeZone($this->tz);
	}

	/**
	 * Checks if the current datetime is between the specified range
	 *
	 * @param JDate &$up_date
	 * @param JDate &$down_date
	 * 
	 * @return bool
	 */
	protected function checkRange(&$up_date, &$down_date)
	{
        if (!$up_date && !$down_date)
        {
            return false;
        }

		$now = $this->date->getTimestamp();

		if (((bool)$up_date   && $up_date->getTimestamp() > $now) ||
			((bool)$down_date && $down_date->getTimestamp() < $now))
		{
			return false;
		}

		return true;
	}
}
