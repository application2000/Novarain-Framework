<?php

/**
 *  @author          Tassos Marinos <info@tassos.gr>
 *  @link            http://www.tassos.gr
 *  @copyright       Copyright © 2018 Tassos Marinos All Rights Reserved
 *  @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace NRFramework\Assignments;

defined('_JEXEC') or die;

use NRFramework\Assignments\GeoIPBase;

class Region extends GeoIPBase
{
    /**
     *  Region check
     *
     *  Input($this->selection) should be a comma/newline separated list of ISO 3611 country-region codes, i.e.GR-I (Greece - Attica)
     * 
     *  @return bool
     */
    public function pass()
    {
        return array_intersect($this->selection, $this->getRegionCodes());
    }

    /**
     *  Returns the assignment's value
     * 
     *  @return string Region codes
     */
	public function value()
	{
		return $this->geo->getRegionCodes();
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
}