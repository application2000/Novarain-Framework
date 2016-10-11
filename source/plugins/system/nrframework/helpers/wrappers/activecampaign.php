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

class NR_ActiveCampaign extends NR_Wrapper
{
	/**
	 * Create a new instance
	 * @param string $api_key Your ActiveCampaign API key
	 * @param string $url The personal endpoint URL
	 * @throws \Exception
	 */
	public function __construct($api_key, $url)
	{
		parent::__construct();
		$this->setApiKey($api_key);
		$this->setApiEndpoint($url);
		$this->options->set('headers.Accept', 'application/json; charset=utf-8');
		$this->options->set('headers.Content-Type', 'application/x-www-form-urlencoded');
		$this->options->set('follow_location', true);
	}

	/**
	 * Setter method for the API Endpoint
	 * @param string $url The URL which is set in the account's developer settings
	 * @throws \Exception 
	 */
	public function setApiEndpoint($url)
	{
		if (!empty($url))
		{
			$query              = http_build_query(array('api_key' => $this->api_key, 'api_output' => 'json'));
			$this->api_endpoint = $url . '/admin/api.php?' . $query;
		}
		else
		{
			throw new \Exception("Invalid ActiveCampaign URL `{$url}` supplied.");
		}
	}
}
