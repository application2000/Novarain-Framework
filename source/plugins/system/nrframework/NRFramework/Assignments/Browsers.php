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

class Browsers extends Assignment
{
    /**
     *  Check the client's browser
     *
     *  @return bool
     */
    function passBrowsers()
    {
      // get the JApplicationWebClient instance
      $client = $this->app->client;

      // get the client's browser constant value (Integer)
      // see https://api.joomla.org/cms-3/classes/Joomla.Application.Web.WebClient.html
      $browserInt = $client->browser;

      // get the constants from JApplicationWebClient as an array using the Reflection API
      $r = new \ReflectionClass('JApplicationWebClient');
      $constantsArray = $r->getConstants();

      // flip the associative array to do a lookup based on $browserInt
      $constantsArray = array_flip($constantsArray);


      if (isset($constantsArray[$browserInt]))
      {
        // get the browser name (lowercase)
        $browserStr = strtolower($constantsArray[$browserInt]);
        return $this->passSimple($browserStr, $this->selection);
      }
      
      return false;
    }  
}
