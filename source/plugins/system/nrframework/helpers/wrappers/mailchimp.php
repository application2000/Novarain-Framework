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

class NR_MailChimp extends NR_Wrapper
{

	/**
	 * Create a new instance
	 * 
	 * @param string $key Your MailChimp API key
	 * @throws \Exception
	 */
	public function __construct($key)
	{
		parent::__construct();
		$this->setKey($key);
		$this->endpoint = 'https://<dc>.api.mailchimp.com/3.0';
		list(, $data_center) = explode('-', $this->key);
		$this->endpoint  = str_replace('<dc>', $data_center, $this->endpoint);
		$this->options->set('headers.Accept', 'application/vnd.api+json');
		$this->options->set('headers.Content-Type', 'application/vnd.api+json');
		$this->options->set('headers.Authorization', 'apikey ' . $this->key);
	}

	/**
	 * Get the last error returned by either the network transport, or by the API.
	 * If something didn't work, this should contain the string describing the problem.
	 * 
	 * @return  array|false  describing the error
	 */
	public function getLastError()
	{
		
		$response = json_decode($this->last_response['body'], true);

		if (isset($response["errors"]))
		{
			$error = $response["errors"][0];
			$this->last_error .= " - " . $error["field"] . ": " . $error["message"];
		}

		return $this->last_error;
	}

	public function setKey($key)
	{
		if ((!empty($key)) && (!strpos($key, '-') === false)) {
			$this->key = $key;
		} else {
			throw new \Exception("Invalid MailChimp key `{$key}` supplied.");
		}
	}
}
