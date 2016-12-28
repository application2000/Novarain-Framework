<?php

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2015 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

// No direct access to this file
defined('_JEXEC') or die;
JHtml::_('bootstrap.popover');
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
        return '<a style="float:none;" class="btn btn-danger ' . $this->get("class") . '" href="' . $this->get("url") . '" target="_blank"><span class="icon-lock"></span> '. $this->prepareText($this->get("link", "NR_UPGRADE_TO_PRO_TO_UNLOCK")) .'</a>';
    }
}