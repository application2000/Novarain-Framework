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

class K2Pagetype extends K2
{
    /**
     *  Pass check for K2 page types
     *
     *  @return bool
     */
    public function passK2Pagetype()
    {
        $view   = $this->app->input->get("view");
        $layout = $this->app->input->get('layout', '', 'string');

        if (empty($this->selection) || !$this->passContext())
        {
            return false;
        }

        $pagetype = $view . '_' . ($layout !== '' ? $layout : $view);
        return $this->passSimple($pagetype, $this->selection);
    }
}
