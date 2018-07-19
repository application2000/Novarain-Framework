<?php

/**
 * @author          Tassos.gr
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2018 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace NRFramework\Assignments;

defined('_JEXEC') or die;

use NRFramework\Assignments\J2StoreBase;

class J2StoreCategory extends J2StoreBase
{
    /**
     *  Pass check for K2 categories
     *
     *  @return bool
     */
    public function pass()
    {
        if (empty($this->selection) || !$this->passContext())
        {
            return false;
		}

        return $this->passComponentCategories();
	}

	/**
     *  Returns the assignment's value
     * 
     *  @return array Event Booking Ctegory IDs
     */
	public function value()
	{
		return $this->getCategoryIds();
	}
    
    /**
	 *  Returns category IDs based
	 *
	 *  @return  array
	 */
	protected function getCategoryIds()
	{
		if (!$this->isItem())
		{
			return;
		}

		// Load Product
		$product = $this->getItem();

		return $product->source->catid;
	}
}