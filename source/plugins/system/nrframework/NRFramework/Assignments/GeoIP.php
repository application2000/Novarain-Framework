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

/**
 *  IP addresses sample
 *
 *  Greece / Dodecanese:  94.67.238.3
 *  Belgium / Flanders:   37.62.255.255
 *  USA / New York:       72.229.28.185
 */
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
        // Load Geo Class
        $ip = isset($assignment->params->ip) ? $assignment->ip : null;
        $this->loadGeo($ip);

        if (!$this->geo)
        {
            return false;
        }

        parent::__construct($assignment, $request, $date);

        // Convert a comma/newline separated selection string into an array
        $selection = is_array($this->selection) ? $this->selection[0] : $this->selection;
        $this->selection = $this->splitKeywords($selection);
    }

    /**
     *  Pass Countries
     * 
     *  @return bool
     */
    public function passCountries()
    {
        // try to convert country names to codes
        $this->selection = array_map(function($c) {
            if (strlen($c) > 2)
            {
                $c = \NRFramework\Countries::getCode($c);
            }
            return $c;
        }, $this->selection);

        return $this->passSimple($this->geo->getCountryCode(), $this->selection);
    }

    /**
     *  Pass Continents
     * 
     *  @return bool
     */
    public function passContinents()
    {
        // try to convert continent names to codes
        $this->selection = array_map(function($c) {
            if (strlen($c) > 2)
            {
                $c = \NRFramework\Continents::getCode($c);
            }
            return $c;
        }, $this->selection);

        return $this->passSimple($this->geo->getContinentCode(), $this->selection);
    }

    /**
     * Pass City Name
     *
     * @return bool
     */
    public function passCities()
    {
        return $this->passSimple($this->geo->getCity(), $this->selection);
    }

    /**
     *  Pass Region Code
     *
     *  Input($this->selection) should be a comma/newline separated list of ISO 3611 country-region codes, i.e.GR-I (Greece - Attica)
     * 
     *  @return bool
     */
    public function passRegions()
    {
        return array_intersect($this->selection, $this->getRegionCodes());
    }

    /**
     *  Get list of all ISO 3611 Country Region Codes
     *
     *  @return array
     */
    private function getRegionCodes()
    {
        $regionCodes = [];
		$record = $this->geo->getRecord();

		if ($record === false || is_null($record))
		{
			return $regionCodes;
		}

        // Skip if no regions found
        if (!$regions = $record->subdivisions)
        {
            return $regionCodes;
        }
        
        foreach ($regions as $key => $region)
        {
            // Prepend country isocode to the region code
            $regionCodes[] = $record->country->isoCode . '-' . $region->isoCode;
        }

        return $regionCodes;
    }

    /**
     *  Load GeoIP Classes
     *
     *  @return  void
     */
    private function loadGeo($ip)
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

        $this->geo = new \TGeoIP($ip);
    }
}
