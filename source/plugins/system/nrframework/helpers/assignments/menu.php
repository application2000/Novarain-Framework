<?php

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2015 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

defined('_JEXEC') or die;

require_once dirname(__DIR__) . '/assignment.php';

class nrFrameworkAssignmentsMenu extends NRAssignment 
{
	/**
	 *  Pass check for menu items
	 *
	 *  @return  bool
	 */
	function passMenu()
	{
    	$includeNoItemID = isset($this->params->assign_menu_param_noitem) ? $this->params->assign_menu_param_noitem : false;

	    if (!$this->request->Itemid)
        {
        	return $includeNoItemID;
        }

		return $this->passSimple($this->request->Itemid, $this->selection); 
	}
}
