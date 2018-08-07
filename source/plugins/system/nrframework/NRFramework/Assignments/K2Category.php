<?php

/**
 * @author          Tassos.gr
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2018 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace NRFramework\Assignments;

defined('_JEXEC') or die;

use NRFramework\Assignments\K2;

class K2Category extends K2
{
    /**
     *  Pass check for K2 categories
     *
     *  @return bool
     */
    public function pass()
    {
		return $this->passComponentCategories('k2_categories', 'parent');
	}
	
	/**
     *  Returns the assignment's value
     * 
     *  @return array K2 category IDs
     */
	public function value()
	{
		$ids = $this->getCategoryIds();

		if ($this->params->inc_children)
		{
			foreach ($ids as $catid)
			{
				$ids[] = $this->getParentIDs($catid, 'k2_categories', 'parent');
			}			 
		}
		return $ids;
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
			// Note: If the category alias starts with a number then we end up with a wrong result
			$catid = (int) $this->app->input->get("id");
			return (array) $catid;
		}

		// If we are in article view return article's category id
		if ($item = $this->getK2Item())
		{
            if (isset($item->catid))
            {
                return (array) $item->catid;
            }
		}

		return false;
    }
}
