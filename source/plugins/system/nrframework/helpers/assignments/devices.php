<?php

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2015 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

defined('_JEXEC') or die;

require_once dirname(__DIR__) . '/assignment.php';

class nrFrameworkAssignmentsDevices extends NRAssignment
{
    /**
     *  Checks visitor's device
     *
     *  @return  bool
     */
	function passDevices()
	{
        if (!class_exists('Mobile_Detect'))
        {
            require_once(JPATH_PLUGINS . "/system/nrframework/helpers/vendors/Mobile_Detect.php");
        }

        $detect = new Mobile_Detect;
        $detectDeviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'mobile') : 'desktop');

    	return $this->passSimple($detectDeviceType, $this->selection); 
	}
}
