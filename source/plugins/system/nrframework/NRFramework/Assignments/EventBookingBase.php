<?php

/**
 * @author          Tassos.gr
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2018 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace NRFramework\Assignments;

defined('_JEXEC') or die;

use NRFramework\Assignment;
use NRFramework\Cache;

abstract class EventBookingBase extends Assignment
{
    /**
     *  Returns an EventBooking item from the database
     *
     *  @return object
     */
    protected function getItem()
    {
        $hash = md5('eventbookingssitem');

        if (Cache::has($hash))
        {
            return Cache::get($hash);
        }

        require_once JPATH_COMPONENT . '/helper/database.php';

        $item = \EventbookingHelperDatabase::getEvent($this->request->id);

        return Cache::set($hash, $item);
    }   

    /**
     *  Indicates whether the page is a category page
     *
     *  @return  boolean
     */
    protected function isCategory()
    {
        return ($this->request->view == 'category');
    }

    /**
     *  Indicates whether the page is a single page
     *
     *  @return  boolean
     */
    protected function isItem()
    {
        return ($this->request->view == 'event' && $this->request->id);
    }

    /**
     *  Check if we are in correct context
     *
     *  @return bool
     */
    protected function passContext()
    {
        return ($this->request->option == 'com_eventbooking');
    }    

    /**
     * Get all event assosiated categories
     *
     * @param   Integer  The Event id
     * @return  void
     */
	protected function getEventCategories($id)
	{
        $db = \JFactory::getDbo();
        
        $query = $db->getQuery(true)
            ->select('category_id')
            ->from('#__eb_event_categories')
            ->where($db->quoteName('event_id') . '=' . $id);

        $db->setQuery($query);

		return $db->loadColumn();
	}
}
