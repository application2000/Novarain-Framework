<?php

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2015 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

defined('_JEXEC') or die;

require_once dirname(__DIR__) . '/assignment.php';

class nrFrameworkAssignmentsLanguages extends NRAssignment
{
	/**
	 *  Pass check language
	 *
	 *  @return  bool
	 */
	function passLanguages()
	{
		return $this->passSimple(JFactory::getLanguage()->getTag(), $this->selection); 
	}
}
