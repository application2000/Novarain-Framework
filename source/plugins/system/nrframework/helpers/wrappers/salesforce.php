<?php

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2017 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

// No direct access
defined('_JEXEC') or die;

require_once __DIR__ . '/wrapper.php';

class NR_SalesForce extends NR_Wrapper
{
	/**
	 * Create a new instance
	 * @param string $key Your SalesForce Access Token
	 * @throws \Exception
	 */
	public function __construct($key, $instance_name)
	{
		parent::__construct();
		$this->setKey($key);
		$this->setEndpoint($instance_name);
		$this->options->set('headers.Authorization', 'Bearer ' . $this->key);
	}

	/**
	 * Setter method for the endpoint
	 * @param string $instance_name The URL which is set in the account's developer settings
	 * @throws \Exception
	 */
	public function setEndpoint($instance_name)
	{
		if (!empty($instance_name))
		{
			$this->endpoint = 'https://' . $instance_name . '.salesforce.com/services/data';
		}
		else
		{
			throw new \Exception("Invalid SalesForce Instance Name `{$instance_name}` supplied.");
		}
	}
}
