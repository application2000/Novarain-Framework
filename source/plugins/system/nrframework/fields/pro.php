<?php

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2015 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

// No direct access to this file
defined('_JEXEC') or die;

require_once dirname(__DIR__) . '/helpers/field.php';

class JFormFieldNR_PRO extends NRFormField
{
    /**
     * The field type.
     *
     * @var         string
     */
    public $type = 'pro';

    /**
     *  Method to render the input field
     *
     *  @return  string  
     */
    protected function getInput()
    {   
        return '<a class="btn btn-danger" href="' . $this->get("url") . '" target="_blank"><span class="icon-lock"></span> '. $this->prepareText($this->get("link", "NR_UPGRADE_TO_PRO")) .'</a>';
    }
}