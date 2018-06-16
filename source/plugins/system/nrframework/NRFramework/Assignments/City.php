<?php

/**
 *  @author          Tassos Marinos <info@tassos.gr>
 *  @link            http://www.tassos.gr
 *  @copyright       Copyright © 2018 Tassos Marinos All Rights Reserved
 *  @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace NRFramework\Assignments;

defined('_JEXEC') or die;

use NRFramework\Assignments\GeoIPBase;

class City extends GeoIPBase
{
    /**
     * City Check
     *
     * @return bool
     */
    public function pass()
    {
        return $this->passSimple($this->geo->getCity(), $this->selection);
    }

    /**
     *  Returns the assignment's value
     * 
     *  @return string City name
     */
	public function value()
	{
		return $this->geo->getCity();
	}
}