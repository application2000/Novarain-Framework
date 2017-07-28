<?php

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2017 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace NRFramework\Helpers\Assignments;

defined('_JEXEC') or die;

use NRFramework\Helpers\Assignment as NRAssignment;

class Menu extends NRAssignment 
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
