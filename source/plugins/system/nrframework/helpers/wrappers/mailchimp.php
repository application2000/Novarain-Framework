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
	 *  Subscribe user to MailChimp
	 *
	 *  API References:
	 *  https://developer.mailchimp.com/documentation/mailchimp/reference/lists/members/#edit-put_lists_list_id_members_subscriber_hash
	 *  https://developer.mailchimp.com/documentation/mailchimp/reference/lists/members/#create-post_lists_list_id_members
	 *
	 *  @param   string   $email         	  User's email address
	 *  @param   string   $list          	  The MailChimp list unique ID
	 *  @param   Object   $merge_fields  	  Merge Fields
	 *  @param   boolean  $update_existing	  Update existing user
	 *  @param   boolean  $double_optin  	  Send MailChimp confirmation email?
	 *
	 *  @return  void
	 */
	public function subscribe($email, $list, $merge_fields = array(), $update_existing = true, $double_optin = false)
	{
		$data = array(
			"email_address" => $email,
			"status" 		=> $double_optin ? "pending" : "subscribed"
		);

		if (is_array($merge_fields) && count($merge_fields))
		{
			$data["merge_fields"] = $merge_fields;
		}

		if ($update_existing)
		{
			$subscriberHash = md5(strtolower($email));
			$this->put('lists/' . $list . '/members/' . $subscriberHash, $data);
		} else 
		{
			$this->post('lists/' . $list . '/members', $data);
		}

		return true;
	}

	/**
	 *  Returns all available MailChimp lists
	 *
	 *  https://developer.mailchimp.com/documentation/mailchimp/reference/lists/#read-get_lists
	 *
	 *  @return  array
	 */
	public function getLists()
	{
		$data = $this->get("/lists");

		if (!$this->success())
		{
			throw new Exception($this->getLastError());
		}

		$lists = array();

		if (!isset($data["lists"]) || !is_array($data["lists"]))
		{
			return $lists;
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
	 *  Get the last error returned by either the network transport, or by the API.
	 *
	 *  @return  string
	 */
	public function getLastError()
	{
		$body = $this->last_response['body'];

		if (isset($body["errors"]))
		{
			$error = $body["errors"][0];
			return $error["field"] . ": " . $error["message"];
		}

		if (isset($body["detail"]))
		{
			return $body["detail"];
		}
	}

	/**
	 *  Set the API Key
	 *
	 *  @param  string
	 */
	public function setKey($key)
	{
		if ((!empty($key)) && (!strpos($key, '-') === false))
		{
			$this->key = $key;
		} else
		{
			throw new \Exception("Invalid MailChimp key `{$key}` supplied.");
		}
	}
}
