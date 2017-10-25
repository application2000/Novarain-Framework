<?php

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2017 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace NRFramework\Assignments;

defined('_JEXEC') or die;

use NRFramework\Assignment;

class GeoIP extends Assignment
{
    /**
     *  GeoIP Class
     *
     *  @var  class
     */
    private $geo;

    /**
     *  Class constructor
     *
     *  @param  object  $assignment
     *  @param  object  $request     
     *  @param  object  $date        
     */
    public function __construct($assignment, $request = null, $date = null)
    {
        $this->loadGeo();

        if (!$this->geo)
        {
            return false;
        }

        parent::__construct($assignment, $request, $date);
    }

    /**
     *  Load GeoIP Classes
     *
     *  @return  void
     */
    private function loadGeo()
    {
        if (!class_exists('TGeoIP'))
        {
            $path = JPATH_PLUGINS . '/system/tgeoip';

            if (@file_exists($path . '/helper/tgeoip.php'))
            {
                if (@include_once($path . '/vendor/autoload.php'))
                {
                    @include_once $path . '/helper/tgeoip.php';
                }
            }
        }

        $this->geo = new \TGeoIP();
    }

    /**
     * Pass Countries
     */
    public function passCountries()
    {
        return $this->passSimple($this->geo->getCountryCode(), $this->selection);
    }

    /**
     * Pass Continents
     */
    public function passContinents()
    {
        return $this->passSimple($this->geo->getContinentCode(), $this->selection);
    }

}
