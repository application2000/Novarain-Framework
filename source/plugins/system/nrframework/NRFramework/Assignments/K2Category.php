<?php

/**
 * @author          Tassos.gr
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2017 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace NRFramework\Assignments;

defined('_JEXEC') or die;

use NRFramework\Assignment;
use NRFramework\Assignments\K2;

class K2Category extends K2
{
    /**
     *  Pass check for K2 categories
     *
     *  @return bool
     */
    public function passK2Category()
    {
        if(!$this->passContext())
        {
            return false;
        }

        // Check if we have a valid selection
		if (empty($this->selection))
		{
			return false;
		}

		$is_category = ($this->request->view == 'itemlist' && $this->request->layout == 'category') || $this->request->view == 'latest';
		$is_item     = $this->request->view == 'item';


		$inc_categories = false;
		$inc_items      = false;
		$inc_children   = $this->params->assign_k2_cats_param_inc_children;

		if (isset($this->params->assign_k2_cats_param_inc) && is_array($this->params->assign_k2_cats_param_inc))
		{
			$inc_categories = in_array('inc_categories', $this->params->assign_k2_cats_param_inc);
			$inc_items      = in_array('inc_items', $this->params->assign_k2_cats_param_inc);
		}

		// Check if we are in a valid context
		if (!($inc_categories && $is_category) && !($inc_items && $is_item))
		{
			return false;
		}

		$pass = false;

		$catids = $this->getCategoryIds($is_category);

		foreach ($catids as $catid)
		{
			if (!$catid)
			{
				continue;
			}

			$pass = in_array($catid, $this->selection);

			// Pass check on child items only
			if ($pass && $this->params->assign_k2_cats_param_inc_children == 2)
			{
				$pass = false;
				continue;
			}

			// Pass check for child items
			if (!$pass && $this->params->assign_k2_cats_param_inc_children)
			{
				$parent_ids = $this->getParentIDs($catid, 'k2_categories', 'parent');
				foreach ($parent_ids as $id)
				{
					if (in_array($id, $this->selection))
					{
						$pass = true;
						break;
					}
				}
				unset($parent_ids);
			}
		}

		return $pass;

    }

    /**
	 *  Returns category IDs based on the active K2 view
	 *
	 *  @param   boolean  $is_category  The current view is a category view
	 *
	 *  @return  array                  The IDs
	 */
	protected function getCategoryIds($is_category = false)
	{
		// If we are in category view return category's id
		if ($is_category)
		{
			return (array) $this->getItemID();
		}

		// If we are in article view return article's category id
		if ($this->request->view == 'item')
		{
            $res = $this->getK2Item();
            if ($res && isset($res->catid))
            {
                return (array) $res->catid;
            }
		}

		return false;
    }
}
