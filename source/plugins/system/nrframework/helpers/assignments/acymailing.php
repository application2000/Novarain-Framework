<?php

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2015 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

defined('_JEXEC') or die;

class nrFrameworkAssignmentsAcyMailing extends nrFrameworkAssignmentsHelper
{

	private $selection;

	function __construct($assignment) {
    	$this->selection = $assignment->selection;
   	}

	function passAcyMailing()
	{
    	return $this->passSimple($this->getSubscribedLists(JFactory::getUser()->id), $this->selection);
	}

	/**
	 *  Returns all AcyMailing lists the user is subscribed to
	 *
	 *  @param   int  $userid  User's id
	 *
	 *  @return  array         AcyMailing lists
	 */
	private function getSubscribedLists($userid)
	{

		if (!$userid)
		{
			return false;
		}

		// Get a db connection.
		$db = JFactory::getDbo();
		 
		// Create a new query object.
		$query = $db->getQuery(true);

		$query
			->select(array('list.listid'))
			->from($db->quoteName('#__acymailing_listsub', 'list'))
			->join('INNER', $db->quoteName('#__acymailing_subscriber', 'sub') . ' ON (' . $db->quoteName('list.subid') . '=' . $db->quoteName('sub.subid') . ')')
			->where($db->quoteName('list.status') . ' = 1')
			->where($db->quoteName('sub.userid') . ' = ' . $userid)
			->where($db->quoteName('sub.confirmed') . ' = 1')
			->where($db->quoteName('sub.enabled') . ' = 1');
		 
		// Reset the query using our newly populated query object.
		$db->setQuery($query);

		return $db->loadColumn();
	}

}
