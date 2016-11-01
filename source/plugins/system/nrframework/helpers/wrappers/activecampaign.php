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
	 * @param string $key Your ActiveCampaign key
	 * @param string $url The personal endpoint URL
	 * @throws \Exception
	 */
	public function __construct($key, $url)
	{
		parent::__construct();
		$this->setKey($key);
		$this->setEndpoint($url);
		$this->options->set('headers.Content-Type', 'application/x-www-form-urlencoded');
		$this->options->set('follow_location', true);
	}

	/**
	 * Setter method for the endpoint
	 * @param string $url The URL which is set in the account's developer settings
	 * @throws \Exception 
	 */
	public function setEndpoint($url)
	{
		if (!empty($url))
		{
			$query              = http_build_query(array('api_key' => $this->key, 'api_output' => 'json'));
			$this->endpoint = $url . '/admin/api.php?' . $query;
		}
		else
		{
			throw new \Exception("Invalid ActiveCampaign URL `{$url}` supplied.");
		}
	}

	/**
	 * Encode the data and attach it to the request
	 * @param   array $data Assoc array of data to attach
	 */
	protected function attachRequestPayload($data)
	{
		$this->last_request['body'] = http_build_query($data);
	}
}
