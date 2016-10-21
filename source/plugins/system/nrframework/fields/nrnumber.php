<?php

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2015 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

// No direct access to this file
defined('_JEXEC') or die;

require_once JPATH_LIBRARIES . '/joomla/form/fields/number.php';

class JFormFieldNRNumber extends JFormFieldNumber
{
    /**
     *  Method to render the input field
     *
     *  @return  string  
     */
    function getInput()
    {   
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

        return implode(" ", $html);
    }


}
