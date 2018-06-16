<?php

/**
 * @author          Tassos.gr <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2018 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/
namespace NRFramework\Assignments;

defined('_JEXEC') or die;

use NRFramework\Assignments\DateTimebase;

class Date extends DateTimeBase
{
    /**
	 *  Checks if current date passes the given date range
	 *
	 *  @return  bool
	 */
	function pass()
	{
        // No valid dates
		if (!$this->params->publish_up && !$this->params->publish_down)
		{
			return false;
        }

        $format = 'Y-m-d H:i:s';
		$up     = $this->params->publish_up;
		$down   = $this->params->publish_down;		

        // fix the date string
		\NRFramework\Functions::fixDate($up);
		\NRFramework\Functions::fixDate($down);

		$up   = $up   ? $this->factory->getDateFromformat($format, $up, $this->tz) : null;
		$down = $down ? $this->factory->getDateFromformat($format, $down, $this->tz) : null;

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