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

class NR_CampaignMonitor extends NR_Wrapper
{
	/**
	 * Create a new instance
	 * @param string $api_key Your CampaignMonitor API key
	 * @throws \Exception
	 */
	public function __construct($api_key)
	{
		parent::__construct();
		$this->setApiKey($api_key);
		$this->api_endpoint = 'https://api.createsend.com/api/v3.1';
		$this->options->set('headers.Accept', 'application/json; charset=utf-8');
		$this->options->set('headers.Content-Type', 'application/json; charset=utf-8');
		$this->options->set('userauth',$this->api_key);
		$this->options->set('passwordauth','nopass');
	}
}
