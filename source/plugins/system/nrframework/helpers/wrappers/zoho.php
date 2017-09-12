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

class NR_ZoHo extends NR_Wrapper
{
	/**
	 * Create a new instance
	 *
	 * @param array $campaignData The Campaign's Data
	 * @throws \Exception
	 */
	public function __construct($campaignData)
	{
		parent::__construct();
		$this->setKey($campaignData['api']);
		$this->endpoint = 'https://campaigns.zoho.com/api';
	}

	/**
	 *  Subscribe user to ZoHo
	 *
	 *  https://www.zoho.com/campaigns/help/api/contact-subscribe.html
	 *
	 *  @param   string   $email         	  User's email address
	 *  @param   string   $list          	  The ZoHo list unique ID
	 *  @param   Object   $customFields  	  Collection of custom fields
	 *
	 *  @return  void
	 */
	public function subscribe($email, $list, $customFields = array())
	{

		$contactinfo = json_encode(array_merge(array("Contact Email" => $email), $customFields));

		$data = array(
			"authtoken" => $this->key,
			"scope" => "CampaignsAPI",
			"version" => "1",
			"resfmt" => "JSON",
			"listkey" => $list,
			"contactinfo" => $contactinfo
		);

		$this->get('json/listsubscribe', $data);

		return true;
	}

	/**
	 *  Returns all available ZoHo lists
	 *
	 *  https://www.zoho.com/campaigns/help/api/get-mailing-lists.html
	 *
	 *  @return  array
	 */
	public function getLists()
	{
		if (!$this->key)
		{
			return;
		}

		$data = array(
			'authtoken' => $this->key,
			'scope'     => 'CampaignsAPI',
			'sort'      => 'asc',
			'resfmt'    => 'JSON',
			'range'     => '1000' //ambiguously large range of total results to overwrite the default range which is 20
		);

		$data = $this->get("getmailinglists", $data);

		if (!$this->success())
		{
			throw new Exception($this->getLastError());
		}

		$lists = array();

		if (!isset($data["list_of_details"]) || !is_array($data["list_of_details"]))
		{
			return $lists;
		}

		foreach ($data["list_of_details"] as $key => $list)
		{
			$lists[] = array(
				"id"   => $list["listkey"],
				"name" => $list["listname"]
			);
		}

		return $lists;
	}

	/**
	 *  Get the last error returned by either the network transport, or by the API.
	 *
	 *  @return  string
	 */
	public function getLastError()
	{
		$body = $this->last_response['body'];

		if (isset($body['status']) && $body['status'] == 'error')
		{
			return $body['message'];
		}
	}

	/**
	 * Check if the response was successful or a failure. If it failed, store the error.
	 * 
	 * @return bool     If the request was successful
	 */
	protected function determineSuccess()
	{
		$status = $this->findHTTPStatus();

		// check if the status is equal to the arbitrary success codes of ZoHo
		if (in_array($status, array(0,200,6101,6201)))
		{
			return ($this->request_successful = true);
		}

		$this->last_error = 'Unknown error, call getLastResponse() to find out what happened.';
		return false;
	}

	/**
	 * Find the HTTP status code from the headers or API response body
	 * 
	 * @return int  HTTP status code
	 */
	protected function findHTTPStatus()
	{
		// First check for common network problems
		if (parent::findHTTPStatus() == 418) 
		{
			return 418;
		}

		// ZoHo sometimes uses "Code" instead of "code"
		// also they don't use HTTP status codes
		// instead they store their own status code inside the response body

		$data = array_change_key_case($this->last_response['body']);
		
		if (isset($data['code']))
		{
			return (int) $data['code'];
		}

		return 418;
	}
}
