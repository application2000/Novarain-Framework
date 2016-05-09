<?php

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2015 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

defined('_JEXEC') or die;

class nrFrameworkAssignmentsUsers extends nrFrameworkAssignmentsHelper 
{

	private $session;
	private $selection;

	function __construct($assignment) {
    	$this->selection = $assignment->selection;
    	$this->session = JFactory::getSession();
   	}

	function passGroupLevels()
	{
		$user = JFactory::getUser();

		if (!empty($user->groups))
		{
			$groups = array_values($user->groups);
		}
		else
		{
			$groups = $user->getAuthorisedGroups();
		}

    	return $this->passSimple($groups, $this->selection); 
	}

	function passTimeOnSite()
	{
		$pass = false;

		$sessionStartTime = strtotime($this->SessionStartTime());

		if (!$sessionStartTime) {
			return $pass;
		}

		$dateTimeNow = strtotime(NRFrameworkFunctions::dateTimeNow());
		$diffInSeconds = $dateTimeNow - $sessionStartTime;

		if (intval($this->selection) <= $diffInSeconds) {
			$pass = true;
		}

		return $pass;
	}

    private static function SessionStartTime() {
        $session = JFactory::getSession();
        
        $var = 'starttime';
        $sessionStartTime = $session->get($var);

        if (!$sessionStartTime) {
            $date = NRFrameworkFunctions::dateTimeNow();
            $session->set($var, $date);
        }

        return $session->get($var);
    }


}
