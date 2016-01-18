<?php

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2015 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

defined('_JEXEC') or die;

class nrFrameworkAssignmentsMenu extends nrFrameworkAssignmentsHelper 
{

	private $selection;
	private $includeNoItemID;

	function __construct($assignment) {
    	$this->selection = $assignment->selection;
    	$this->includeNoItemID = isset($assignment->params->assign_menu_param_noitem) ? $assignment->params->assign_menu_param_noitem : false;
   	}

	function passMenu()
	{
        $menuActive = JFactory::getApplication()->getMenu()->getActive();

        if (!$menuActive || empty($this->selection)) {
        	return $this->pass($this->includeNoItemID);
        }

		return $this->passSimple($menuActive->id, $this->selection); 
	}
}
