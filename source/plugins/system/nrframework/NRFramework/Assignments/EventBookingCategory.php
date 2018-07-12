<?php

/**
 * @author          Tassos.gr
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2018 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace NRFramework\Assignments;

defined('_JEXEC') or die;

use NRFramework\Assignments\EventBookingBase;

class EventBookingCategory extends EventBookingBase
{
    /**
     *  Pass check for K2 categories
     *
     *  @return bool
     */
    public function passEventBookingCategory()
    {
        if (empty($this->selection) || !$this->passContext())
        {
            return false;
		}

        return $this->passComponentCategories('eb_categories', false);
	}
	
    /**
	 *  Returns category IDs based on the active K2 view
	 *
	 *  @return  array                  The IDs
	 */
	protected function getCategoryIds()
	{
		// If we are in category view return category's id
		if ($this->isCategory())
		{
			$catid = (int) $this->request->id;
			return (array) $catid;
		}

		// If we are in article view return article's category id
		if ($this->isItem() && $item = $this->getItem())
		{
            return $this->getEventCategories($item->id);
		}

		return false;
    }
}