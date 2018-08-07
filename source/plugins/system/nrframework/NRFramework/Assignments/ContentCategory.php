<?php

/**
 * @author          Tassos.gr <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2018 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

namespace NRFramework\Assignments;

defined('_JEXEC') or die;

use NRFramework\Assignments\ContentBase;

class ContentCategory extends ContentBase
{
    /**
     *  Pass check for Joomla! Categories
     *
     *  @return bool
     */
    public function pass()
    {
		// Rename inc_articles to inc_items
		if (in_array('inc_articles', $this->params->inc))
		{
			$this->params->inc[] = 'inc_items';
		}

        return $this->passComponentCategories();
	}

	/**
     *  Returns the assignment's value
     * 
     *  @return array Joomla! Article Category IDs
     */
	public function value()
	{
		return $this->getCategoryIds();
	}
    
    /**
	 *  Returns category IDs based on active view
	 *
	 *  @param   boolean  $is_category  The current view is a category view
	 *
	 *  @return  array                  The IDs
	 */
	protected function getCategoryIds()
	{
		// If we are in category view return category's id
		if ($this->isCategory())
		{
			return (array) $this->request->id;
		}

		// If we are in article view return article's category id
		if ($this->isItem())
		{
			$item = $this->getItem();
			return (array) $item->catid;
		}

		return false;
	}
}