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

class NR_iContact extends NR_Wrapper
{
	/**
	 * Create a new instance
	 * @param string $appId    The AppId provided by iContact
	 * @param string $url      The Personal URL provided by iContact
	 * @param string $username The username for iContact
	 * @param string $password The password set for the App created in iContact
	 */
	public function __construct($appId, $url, $username, $password)
	{
		parent::__construct();
		$this->setEndpoint($url);
		$this->options->set('headers.Accept', 'application/json; charset=utf-8');
		$this->options->set('headers.Content-Type', 'application/json; charset=utf-8');
		$this->options->set('header.API-Version','2.2');
		$this->options->set('header.API-AppId',$appId);
		$this->options->set('header.API-Username',$username);
		$this->options->set('header.API-Password',$password);
	}
}