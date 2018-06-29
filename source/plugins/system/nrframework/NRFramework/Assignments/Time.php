<?php

/**
 * @author          Tassos.gr <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2018 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/
namespace NRFramework\Assignments;

defined('_JEXEC') or die;

use NRFramework\Assignments\DateTimeBase;

class Time extends DateTimeBase
{
    /**
	 * Checks if current time passes the given time range
	 *
	 * @return bool
	 */
	public function pass()
	{
        if (!is_null($this->params->publish_up))
        {
            list($up_hours, $up_mins) = explode(':', $this->params->publish_up);
        }
        
        if (!is_null($this->params->publish_down))
        {
            list($down_hours, $down_mins) = explode(':', $this->params->publish_down);
        }

        // do comparison using time only
		$up   = is_null($this->params->publish_up)   ? null : $this->factory->getDate()->setTimezone($this->tz)->setTime($up_hours, $up_mins);
		$down = is_null($this->params->publish_down) ? null : $this->factory->getDate()->setTimezone($this->tz)->setTime($down_hours, $down_mins);

        return $this->checkRange($up, $down);
    }
    
    /**
     *  Returns the assignment's value
     * 
     *  @return \Date Current date
     */
	public function value()
	{
		return $this->date;
	}
}