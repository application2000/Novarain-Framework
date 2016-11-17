<?php

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2015 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

// No direct access to this file
defined('_JEXEC') or die;

class JFormFieldNRText extends JFormFieldText
{
    /**
     *  Method to render the input field
     *
     *  @return  string  
     */
    function getInput()
    {   
        // Adds an extra info label next to input
        $addon = (string) $this->element['addon'];

        if (!empty($addon))
        {
            $html[] = '<div class="input-append">';
            $html[] = parent::getInput();
            $html[] = '<span class="add-on">'.JText::_($addon).'</span>';
            $html[] = '</div>';
        } else {
            $html[] = parent::getInput();
        }

        // Adds a link next to input
        $url    = $this->element['url'];
        $text   = $this->element['urltext'];
        $target = $this->element['urltarget'] ? $this->element['urltarget'] : "_blank";

        if ($url && $text) {
            $html[] = '<a style="margin-left:10px;" href="' . $url . '" target="' . $target . '">' . JText::_($text) . '</a>';
        }

        return implode(" ", $html);
    }
}