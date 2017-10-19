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

class NR_ZohoCRM extends NR_Wrapper
{
	/**
	 * Create a new instance
	 *
	 * @param array $options The service's required options
	 * @throws \Exception
	 */
	public function __construct($options)
	{
		parent::__construct();

		$this->options->set('headers.Accept', 'text/xml;charset=utf-8');
		$this->options->set('headers.Content-Type', 'text/xml;charset=utf-8');
		$this->response_type = 'xml';

		if (!empty($options['authenticationToken']))
		{
			$this->setKey($options['authenticationToken']);
		}

		if (empty($options['authenticationToken']) && !empty($options['userid']) && !empty($options['password']))
		{
			$this->userid   = $options['userid'];
			$this->password = $options['password'];
			$this->setAuthenticationToken();
		}

		if (empty($this->key))
		{
			throw new \Exception("The Zoho CRM Authentication Token could not be retrieved.");
		}

	}

	/**
	 *  Subscribe user to ZohoCRM
	 *
	 *  @param   string   $email         	  User's email address
	 *  @param   Object   $fields  	          Available form fields
	 *  @param   boolean  $update_existing	  Update existing user
	 *
	 *  @return  void
	 */
	public function subscribe($email, $fields = array(), $update_existing = true)
	{

		$xml = new SimpleXMLElement('<Leads/>');
		$row = $xml->addChild('row');
		$row->addAttribute('no', '1');

		// add mandatory email in XML
		$xmlField = $row->addChild('FL', $email);
		$xmlField->addAttribute('val', 'Email');

		if (is_array($fields) && count($fields))
		{
			foreach ($fields as $field_key => $field_value)
			{
				$xmlField = $row->addChild('FL', $field_value);
				$xmlField->addAttribute('val', $field_key);
			}

			// check for mandatory Last Name
			if (!array_key_exists('Last Name', $fields))
			{
				throw new \Exception("The Lead could not be inserted without a name field");
			}

			// check for mandatory company
			// we can take it upon ourselves and send 'Not Available' if there isn't any
			if (!array_key_exists('Company', $fields))
			{
				$xmlField = $row->addChild('FL', 'Not Available');
				$xmlField->addAttribute('val', 'Company');
			}
		}

		$xmlData = $xml->asXML();

		$data = array(
			'authtoken'      => $this->getAuthenticationToken(),
			'scope'          => 'crmapi',
			'xmlData'        => $xmlData,
			'version'        => '4',
			'duplicateCheck' => '1'
		);

		if ($update_existing)
		{
			$data['duplicateCheck'] = '2';
		}

		$this->endpoint = 'https://crm.zoho.eu/crm/private/xml/Leads/insertRecords?' . http_build_query($data);

		$this->post('');

		return true;
	}

	/**
	 *  Get the last error returned by either the network transport, or by the API.
	 *
	 *  @return  string
	 */
	public function getLastError()
	{
		$body = $this->last_response['body'];

		if (isset($body->error))
		{
			return $body->error->message;
		}

		if (isset($body->result->row->error)) 
		{
			return $body->result->row->error->details;
		}

		return 'Unknown error';

	}

	/**
	 *  Set the API Key
	 *
	 *  @param  string
	 */
	public function setKey($key)
	{
		if (!empty($key))
		{
			$this->key = $key;
		}
		else
		{
			throw new \Exception("Invalid ZohoCRM key `{$key}` supplied.");
		}
	}

	/**
	 *  Sets the Authentication Token from Zoho CRM
	 *
	 *  @return  string
	 */
	public function setAuthenticationToken()
	{
		$urlParams = array(
			'SCOPE'        => 'ZohoCRM/crmapi',
			'EMAIL_ID'     => $this->userid,
			'PASSWORD'     => $this->password,
			'DISPLAY_NAME' => 'convertforms'
		);

		$this->endpoint = 'https://accounts.zoho.eu/apiauthtoken/nb/create?' . http_build_query($urlParams);

		// temporarily switch the response to text
		$this->response_type = 'text';
		$result              = $this->post('');

		// switch back to xml
		$this->response_type = 'xml';

		$textInArray = explode("\n", $result);
		$authToken   = explode("=", $textInArray['2']);
		$cmp         = strcmp($authToken['0'], "AUTHTOKEN");

		if ($cmp == 0)
		{
			$this->setKey($authToken['1']);
		}
		else
		{
			throw new \Exception("The Zoho CRM Authentication Token could not be retrieved.");
		}
	}

	/**
	 *  Get the Zoho CRM Authentication Token
	 *
	 *  @return  string
	 */
	public function getAuthenticationToken()
	{
		if (empty($this->key))
		{
			$this->setAuthenticationToken();
		}

		return $this->key;
	}

	/**
	 * Check if the response was successful or a failure. If it failed, store the error.
	 * 
	 * @return bool     If the request was successful
	 */
	public function determineSuccess()
	{
		$status = $this->findHTTPStatus();
		$success = ($status >= 200 && $status <= 299) ? true : false;

		if (!$success) 
		{
			return false;
		}

		$body = $this->last_response['body'];

		if (!isset($body->result->row->success)) 
		{
			return false;
		}
		
		$this->request_successful = true;

		return $this->request_successful;
	}
}
