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
	 * @param string $api_key Your MailChimp API key
	 * @throws \Exception
	 */
	public function __construct($api_key)
	{
		parent::__construct();
		$this->setApiKey($api_key);
		$this->api_endpoint = 'https://<dc>.api.mailchimp.com/3.0';
		list(, $data_center) = explode('-', $this->api_key);
		$this->api_endpoint  = str_replace('<dc>', $data_center, $this->api_endpoint);
		$this->options->set('headers.Accept', 'application/vnd.api+json');
		$this->options->set('headers.Content-Type', 'application/vnd.api+json');
		$this->options->set('headers.Authorization', 'apikey ' . $this->api_key);
	}

	public function setApiKey($api_key)
	{
		if ((!empty($api_key)) && (!strpos($api_key, '-') === false)) {
			$this->api_key = $api_key;
		} else {
			throw new \Exception("Invalid MailChimp API key `{$api_key}` supplied.");
		}
	}
}
