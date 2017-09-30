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

class OS extends Assignment
{
    /**
     *  Check the client's operating system
     *
     *  @return bool
     */
    function passOS()
    {
      //-- TODO: factor out common code with Browsers Assignment!
      $platformInt = $this->app->client->platform;

      // get the constants from JApplicationWebClient as an array using the Reflection API
      $r = new \ReflectionClass('JApplicationWebClient');
      $constantsArray = $r->getConstants();

      // flip the associative array to do a lookup based on $platformInt
      $constantsArray = array_flip($constantsArray);
      //--

      if (isset($constantsArray[$platformInt]))
      {
        $platformStr = strtolower($constantsArray[$platformInt]);
        return $this->passSimple($platformStr, $this->selection);
      }
      
      return false;      
    }
}
