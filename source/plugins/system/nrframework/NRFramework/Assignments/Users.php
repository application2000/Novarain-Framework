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

		$dateTimeNow = strtotime(\NRFramework\Functions::dateTimeNow());
		$diffInSeconds = $dateTimeNow - $sessionStartTime;

		if (intval($this->selection) <= $diffInSeconds)
		{
			$pass = true;
		}

		return $pass;
	}

	/**
	 * Check the number of pageviews
	 *
	 * @return bool
	 */
	public function passPageviews()
	{
		$pass = false;

		$session = \JFactory::getSession();
		$visits = $session->get('session.counter', 0);

		$pageviews_param = intval($this->params->assign_pageviews_param_views);

		switch ($this->selection)
		{
			case 'exactly':
				$pass = $visits === $pageviews_param;
				break;
			case 'fewer':
				$pass = $visits < $pageviews_param;
				break;
			case 'greater':
				$pass = $visits > $pageviews_param;
				break;
		}

		return $pass;
	}


	/**
	 * Check User ID
	 *
	 * @return bool
	 */
	public function passIDs()
	{
		// prepare an array(of ints) from the supplied IDs(string)		
		$ids = array_map('intval', array_map('trim', explode(',', $this->selection)));

		if (in_array($this->user->id, $ids))
		{
			return true;
		}

		return false;
	}

    private static function SessionStartTime()
    {
        $session = \JFactory::getSession();
        
        $var = 'starttime';
        $sessionStartTime = $session->get($var);

        if (!$sessionStartTime)
        {
            $date = \NRFramework\Functions::dateTimeNow();
            $session->set($var, $date);
        }

        return $session->get($var);
    }
}
