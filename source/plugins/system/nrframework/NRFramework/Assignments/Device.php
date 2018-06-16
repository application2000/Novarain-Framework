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
use NRFramework\WebClient;

class Device extends Assignment
{
    /**
     *  Checks client's device type
     *
     *  @return  bool
     */
	function pass()
	{
    	return $this->passSimple($this->getDevice(), $this->selection);
    }

    /**
     *  Returns the assignment's value
     * 
     *  @return string Device type
     */
	public function value()
	{
		return $this->getDevice();
	}
    
    /**
     *  Gets client's device type
     * 
     *  @return string
     */
    public function getDevice()
    {
        return WebClient::getDeviceType();
    }
}
