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

class NR_GetResponse extends NR_Wrapper
{

	/**
	 * Create a new instance
	 * @param string $api_key Your GetResponse API key
	 * @throws \Exception
	 */
	public function __construct($api_key)
	{
		parent::__construct();
		$this->setApiKey($api_key);
		$this->api_endpoint = 'https://api.getresponse.com/v3';
		$this->options->set('headers.Content-Type', 'application/json');
		$this->options->set('headers.X-Auth-Token', 'api-key ' . $this->api_key);
		$this->options->set('headers.Accept-Encoding', 'gzip,deflate');
		$this->options->set('userAgent', 'PHP GetResponse Client for Joomla');
		$this->options->set('timeout', $this->timeout);

	}
}