<?php

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2015 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

defined('_JEXEC') or die;

class nrFrameworkAssignmentsAkeebaSubs extends nrFrameworkAssignmentsHelper
{

	private $selection;

	function __construct($assignment) {
    	$this->selection = $assignment->selection;
   	}

	function passAkeebaSubs()
	{
    	return $this->passSimple($this->getLevels(), $this->selection);
	}

	/**
	 *  Returns all user's active subscriptions
	 *
	 *  @param   int  $userid  User's id
	 *
	 *  @return  array         Akeeba Subscriptions
	 */
	private function getLevels()
	{

		if (JFactory::getUser()->guest)
		{
			return false;
		}

		if (!defined('FOF30_INCLUDED') && !@include_once(JPATH_LIBRARIES . '/fof30/include.php'))
		{
			return false;
		}

		// Get the Akeeba Subscriptions container. Also includes the autoloader.
		$container = FOF30\Container\Container::getInstance('com_akeebasubs');

		$subscriptionsModel = $container->factory->model('Subscriptions')->tmpInstance();

		$items = $subscriptionsModel
			->user_id(JFactory::getUser()->id)
			->enabled(1)
			->get();

		if (!$items->count())
		{
			return false;
		}

		$levels = array();

		foreach ($items as $subscription) {
			$levels[] = $subscription->akeebasubs_level_id;
		}

		return array_unique($levels);
	}

}
