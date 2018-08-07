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

class ContentArticle extends ContentBase
{
    /**
	 *  Pass check for Joomla! Articles
	 *
	 *  @return  bool
	 */
	public function pass()
	{
        if (!$this->isItem())
        {
            return false;
        }

        if (!is_array($this->selection))
        {
            $this->selection = $this->splitKeywords($this->selection);
        }
        
        return parent::pass();
    }
    
    /**
     *  Returns the assignment's value
     * 
     *  @return int Article ID
     */
    public function value()
    {
        return $this->request->id;
    }
}