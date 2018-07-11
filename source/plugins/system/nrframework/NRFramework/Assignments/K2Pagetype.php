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

class K2Pagetype extends K2
{
    /**
     *  Pass check for K2 page types
     *
     *  @return bool
     */
    public function pass()
    {
        if (empty($this->selection) || !$this->passContext())
        {
            return false;
        }

        return $this->passSimple($this->getPagetype(), $this->selection);
    }

    /**
     *  Returns the assignment's value
     * 
     *  @return string Pagetype
     */
	public function value()
	{
		return $this->getPagetype();
    }
    
    public function getPagetype()
    {
        $view   = $this->app->input->get("view");
        $layout = $this->app->input->get('layout', '', 'string');
        
        return $view . '_' . ($layout !== '' ? $layout : $view);
    }
}
