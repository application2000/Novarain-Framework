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

class Menu extends Assignment 
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
