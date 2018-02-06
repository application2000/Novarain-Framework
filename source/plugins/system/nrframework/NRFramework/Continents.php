<?php
/**
 *  @author          Tassos.gr <info@tassos.gr>
 *  @link            http://www.tassos.gr
 *  @copyright       Copyright © 2017 Tassos Marinos All Rights Reserved
 *  @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

namespace NRFramework;

defined('_JEXEC') or die('Restricted access');

/**
 *  Helper class to work with continent names/codes
 */
class Continents
{
    /**
     *  Return a continent code from it's name
     *
     *  @param  string $cont
     *  @return string|void
     */
    static public function getCode($cont)
    {
        $cont = \ucwords(strtolower($cont));
        foreach (self::MAP as $key => $value)
        {
            if (strpos($value, $cont) !== false)
            {
                return $key;
            }
        }
        return null;
    }

    /**
	 *  Continents List
	 *
	 *  @var  array
	 */
	const MAP = [
		'AF' => 'Africa',
		'AS' => 'Asia',
		'EU' => 'Europe',
		'NA' => 'North America',
		'SA' => 'South America',
		'OC' => 'Oceania',
		'AN' => 'Antarctica',
    ];
}
