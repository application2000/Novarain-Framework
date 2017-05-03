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
	 * @param string $organizationID Your SalesForce Organization ID
	 * @throws \Exception
	 */
	public function __construct($organizationID)
	{
		parent::__construct();
		$this->setKey($organizationID);
		$this->endpoint = 'https://webto.salesforce.com/servlet/servlet.WebToLead?encoding=UTF-8';
		$this->options->set('headers.Content-Type', 'application/x-www-form-urlencoded');
		$this->encode = false;
	}

	/**
	 *  Subscribe user to MailChimp
	 *
	 *  API References:
	 *  https://developer.salesforce.com/page/Wordpress-to-lead
	 *
	 *  @param   string   $email         	  User's email address
	 *  @param   array    $params  			  All the form fields
	 *
	 *  @return  void
	 */
	public function subscribe($email, $params)
	{
		$data = array(
			"email" => $email,
			"oid"   => $this->key
		);

		if (is_array($params) && count($params))
		{
			$data = array_merge($data, $params);
		}

		$this->post('', $data);

		return true;
	}

}
