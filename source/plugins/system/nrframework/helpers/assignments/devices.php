<?php

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2015 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

defined('_JEXEC') or die;

class nrFrameworkAssignmentsDevices extends nrFrameworkAssignmentsHelper
{
	private $selection;

	function __construct($assignment) {
    	$this->selection = $assignment->selection;

        if (!class_exists('Mobile_Detect')) {
            require_once(JPATH_PLUGINS."/system/nrframework/helpers/vendors/Mobile_Detect.php");
        }
   	}

	function passDevices()
	{
        if (class_exists('Mobile_Detect')) {
            $detect = new Mobile_Detect;
            $detectDeviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'mobile') : 'desktop');

    		return $this->passSimple($detectDeviceType, $this->selection); 
        }
	}
}
