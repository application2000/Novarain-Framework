<?php

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2015 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

defined('_JEXEC') or die;

require_once dirname(__DIR__) . '/assignment.php';

class nrFrameworkAssignmentsURLs extends NRAssignment 
{
   	/**
   	 *  Pass Check Domain Referrer
   	 *
   	 *  @return  bool
   	 */
   	function passReferrer()
   	{
   		// Check if we have valid selection list
		$referrers = array_filter(array_unique(explode("\n", $this->selection)));

		if (!is_array($referrers) || !count($referrers))
		{
			return false;
		}

		// Remove new line characters
		foreach ($referrers as $key => $referrer)
		{ 
			$referrer = str_replace(array("\n", "\r"), "", $referrer); 
			$referrers[$key] = $referrer; 
		}

		// Check if we have a valid referer
		$referer = isset($_SERVER['HTTP_REFERER']) ? parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) : false;

		if (!$referer)
		{
			return false;
		}

		$domainReferrer = str_ireplace("www.", "", $referer); 

		return in_array($domainReferrer, $referrers);
   	}

   	/**
   	 *  Pass Check URLs
   	 *
   	 *  @return  bool
   	 */
	function passURLs()
	{
		$regex = isset($this->params->assign_urls_param_regex) ? $this->params->assign_urls_param_regex : true;

		if (!is_array($this->selection))
		{
			$this->selection = explode("\n", $this->selection);
		}

		if (count($this->selection) == 1)
		{
			$this->selection = explode("\n", $this->selection['0']);
		}

		$url = JURI::getInstance();
		$url = $url->toString();

		$urls = array(
			html_entity_decode(urldecode($url), ENT_COMPAT, 'UTF-8'),
			urldecode($url),
			html_entity_decode($url, ENT_COMPAT, 'UTF-8'),
			$url
		);
		$urls = array_unique($urls);

		$pass = false;
		foreach ($urls as $url)
		{
			foreach ($this->selection as $s)
			{
				$s = trim($s);
				if ($s == '')
				{
					continue;
				}

				if ($regex)
				{
					$url_part = str_replace(array('#', '&amp;'), array('\#', '(&amp;|&)'), $s);
					$s = '#' . $url_part . '#si';
					if (@preg_match($s . 'u', $url) || @preg_match($s, $url))
					{
						$pass = true;
						break;
					}

					continue;
				}

				if (strpos($url, $s) !== false)
				{
					$pass = true;
					break;
				}
			}

			if ($pass)
			{
				break;
			}
		}

		return $pass;
	}
}
