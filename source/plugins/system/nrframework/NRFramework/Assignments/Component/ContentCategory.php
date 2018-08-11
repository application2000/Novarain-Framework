<?php

/**
 * @author          Tassos.gr
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2018 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace NRFramework\Assignments\Component;

defined('_JEXEC') or die;

class ContentCategory extends ContentBase
{
    /**
     *  Pass check
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

        return $this->passCategories();
	}
}