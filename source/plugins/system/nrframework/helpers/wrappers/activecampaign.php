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
	 *  Subscribe user to Active Campaign List
	 *
	 *  http://www.activecampaign.com/api/example.php?call=contact_add
	 *
	 *  TODO: Custom Fields, Update existing contact
	 *
	 *  @param   string   $email   	     The name of the Contact
	 *  @param   string   $name          Email of the Contact
	 *  @param   object   $list          List ID
	 *  @param   object   $tags	 		 Tags for this contact (comma-separated). Example: "tag1, tag2, etc"
	 *  @param   object   $customfields	 Custom Fields
	 *
	 *  @return  void
	 */
	public function subscribe($email, $name, $list, $tags = "", $customfields = null)
	{
		$name = explode(" ", $name, 2);

		$data = array(
			"api_action" 		   => "contact_add",
			"email" 			   => $email,
			"first_name"		   => isset($name[0]) ? $name[0] : null,
			"last_name"			   => isset($name[1]) ? $name[1] : null,
			"p[".$list."]" 		   => $list,
			"tags"				   => $tags,
			"status[1]"			   => 1,
			"instantresponders[1]" => 1,
			"ip4" 				   => $_SERVER['REMOTE_ADDR']
		);

		$this->post('', $data);
	}

	/**
	 *  Returns all available lists
	 *
	 *  @return  array
	 */
	public function getLists($fulldata = false)
	{
		$data = $this->get("/lists");
		$lists = array();

		if (!isset($data["lists"]))
		{
			return $lists;
		}

		if ($fulldata)
		{
			return $data;
		}

		foreach ($data["lists"] as $key => $list)
		{
			$lists[] = array(
				"id"   => $list["id"],
				"name" => $list["name"]
			);
		}

		return $lists;
	}

	/**
	 * Check if the response was successful or a failure. If it failed, store the error.
	 * 
	 * @return bool     If the request was successful
	 */
	protected function determineSuccess()
	{
		$serviceStatus = $this->findHTTPStatus();

		// Find Active Campaign true application status
		$body = $this->last_response['body'];
		$applicationStatus = (bool) isset($body['result_code']) ? $body['result_code'] : false;

		if (($serviceStatus >= 200 && $serviceStatus <= 299) && $applicationStatus)
		{
			return ($this->request_successful = true);
		}

		// Request Failed - Set the last error
		$this->last_error = isset($body["result_message"]) ? $body["result_message"] : "";
	}

	/**
	 *  Setter method for the endpoint
	 *
	 *  @param  string  $url  The URL which is set in the account's developer settings
	 */
	public function setEndpoint($url)
	{
		if (!empty($url))
		{
			$query = http_build_query(array('api_key' => $this->key, 'api_output' => 'json'));
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
