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

class Users extends Assignment 
{
	/**
	 *  Pass Check User Group Levels
	 *
	 *  @return  bool
	 */
	function passGroupLevels()
	{
		$groups = !empty($this->user->groups) ? array_values($this->user->groups) : $this->user->getAuthorisedGroups();
    	return $this->passSimple($groups, $this->selection); 
	}

	/**
	 *  Pass Check User's Time on Site
	 *
	 *  @return  bool
	 */
	function passTimeOnSite()
	{
		$pass = false;

		$sessionStartTime = strtotime($this->SessionStartTime());

		if (!$sessionStartTime)
		{
			return $pass;
		}

		$dateTimeNow = strtotime(\NRFrameworkFunctions::dateTimeNow());
		$diffInSeconds = $dateTimeNow - $sessionStartTime;

		if (intval($this->selection) <= $diffInSeconds)
		{
			$pass = true;
		}

		return $pass;
	}

    private static function SessionStartTime()
    {
        $session = \JFactory::getSession();
        
        $var = 'starttime';
        $sessionStartTime = $session->get($var);

        if (!$sessionStartTime)
        {
            $date = \NRFrameworkFunctions::dateTimeNow();
            $session->set($var, $date);
        }

        return $session->get($var);
    }
}
