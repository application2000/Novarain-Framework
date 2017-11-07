<?php

/**
 * @author          Tassos.gr <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2017 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/
namespace NRFramework\Assignments;

defined('_JEXEC') or die;

use NRFramework\Assignment;

class Cookies extends Assignment
{
    public function passName()
    {
        $pass = false;
        $input_cookie       = $this->app->input->cookie;
        $cookie_data        = $input_cookie->get($this->params->assign_cookiename_param_name);
        $user_content       = empty($this->params->assign_cookiename_param_content) ? 
                                '' : 
                                $this->params->assign_cookiename_param_content;
        $starts_ends_content = empty($this->params->assign_cookiename_param_starts_ends) ? 
                                '' : 
                                $this->params->assign_cookiename_param_starts_ends; 

        // return false if the cookie is not found
        if($cookie_data === null)
        {
            return false;
        }

        // return true if the user selected the 'exists' option
        if ($this->selection === 'exists')
        {
            return true;
        }

        switch ($this->selection)
        {
            case 'equal':
                $pass = $user_content === $cookie_data;
                break;
            case 'contains':
                if ($user_content !== '' && strpos($cookie_data, $user_content) !== FALSE)
                {
                    $pass = true;
                }
                break;
            case 'starts':
                if ($starts_ends_content !== '' && substr($cookie_data, 0, strlen($starts_ends_content)) === $starts_ends_content )
                {
                    $pass = true;
                }
                break;
            case 'ends':
                if ($starts_ends_content !== '' && substr($cookie_data, -strlen($starts_ends_content)) === $starts_ends_content )
                {
                    $pass = true;
                }
                break;
        }

        return $pass;
    }
}
