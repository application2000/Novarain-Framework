<?php

/**
 * @package         @pkg.name@
 * @version         @pkg.version@ @vUf@
 *
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2016 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

// No direct access
defined('_JEXEC') or die;

require_once __DIR__ . '/wrapper.php';

class NR_ConvertKit extends NR_Wrapper
{
	/**
	 * Create a new instance
	 * @param string $api_secret Your ConvertKit API Secret
	 * @throws \Exception
	 */
	public function __construct($api_secret)
	{
		parent::__construct();
		$this->setKey($api_secret);
		$this->endpoint = 'https://api.convertkit.com/v3';
		$this->options->set('headers.Accept', 'application/json; charset=utf-8');
		$this->options->set('headers.Content-Type', 'application/json');
	}

	/**
	 * Setter method for the API Endpoint
	 * @param string $url The URL which is set in the account's developer settings
	 * @throws \Exception 
	 */
	public function setEndpoint($url)
	{
		if (!empty($url))
		{
			$query              = http_build_query(array('key' => $this->key));
			$this->endpoint = $url . '?' . $query;
		}
		else
		{
			throw new \Exception("Invalid ConvertKit URL `{$url}` supplied.");
		}
	}
}
