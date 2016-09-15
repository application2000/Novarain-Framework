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

    protected function getLabel()
    {
        $label = $this->get("label", false);

        if ($label)
        {
            return parent::getLabel();
        } 
        else 
        {
            return "";
        }
    }

    /**
     *  Method to render the input field
     *
     *  @return  string  
     */
    protected function getInput()
    {   
        $text   = $this->get("text", "NR_ONLY_AVAILABLE_IN_PRO");
        $url    = $this->get("url");
        $link   = $this->get("link", "NR_UPGRADE_TO_PRO");
        
        $html[] = '<span class="label label-important">' . $this->prepareText($text) . '</span>';

        if (!empty($url))
        {
            $html[] = '<a href="'.$url.'" target="_blank">'. $this->prepareText($link) .'</a>';
        }

        return implode(" ", $html);
    }

}