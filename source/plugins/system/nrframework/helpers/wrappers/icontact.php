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
	public $accountID;

	public $clientFolderID;

	/**
	 * Create a new instance
	 * @param string $appId    			The AppId provided by iContact
	 * @param string $username 			The username for iContact
	 * @param string $password 			The password set for the App created in iContact
	 * @param string $accountID 		The AccountID obtained only through the API
	 * @param string $clientFolderID 	The ClientFolderID obtained only through the API
	 */
	public function __construct($appId, $username, $password, $accountID = false, $clientFolderID = false)
	{
		parent::__construct();
		$this->endpoint = 'https://app.icontact.com/icp/a';
		$this->options->set('headers.API-Version', '2.2');
		$this->options->set('headers.API-AppId', $appId);
		$this->options->set('headers.API-Username', $username);
		$this->options->set('headers.API-Password', $password);
		$this->accountID      = $this->setAccountID($accountID);
		$this->clientFolderID = $this->setClientFolderID($clientFolderID);
	}

	/**
	 *  Finds and sets the iContact AccountID
	 *
	 *  @param  mixed  $accountID
	 */
	public function setAccountID($accountID = false)
	{
		if ($accountID)
		{
			return $this->accountID = $accountID;
		}
		
		$accounts = $this->get('');

		// Make sure the account is active
		if (intval($accounts['accounts'][0]['enabled']) === 1)
		{
			return (integer) $accounts['accounts'][0]['accountId'];
		}
		else
		{
			throw new Exception(JText::_('NR_ICONTACT_ACCOUNTID_ERROR'), 1);
		}
	
		return;
	}

	/**
	 *  Finds and sets the iContact ClientFolderID
	 *
	 *  @param  mixed  $clientFolderID
	 */
	public function setClientFolderID($clientFolderID = false)
	{
		if ($clientFolderID)
		{
			return $clientFolderID;
		}

		// We need an existant accountID
		if (empty($this->accountID))
		{
			try
			{
				$this->accountID = $this->setAccountID();
			}
			catch (Exception $e)
			{
				throw $e;
			}
		}

		if ($clientFolder = $this->get($this->accountID . '/c/'))
		{
			return $clientFolder['clientfolders'][0]['clientFolderId'];
		}

		return;
	}

	/**
	 *  Subscribes a user to an iContact List
	 *
	 *  API REFERENCE
	 *  https://www.icontact.com/developerportal/documentation/contacts
	 *
	 *  @param   string   $email
	 *  @param   object   $params  The extra form fields
	 *  @param   mixed    $listID  The iContact List ID
	 *
	 *  @return  boolean            
	 */
	public function subscribe($email, $params, $listID)
	{
		$data = array('contact' => array_merge(array('email' => $email, 'status' => 'normal'), (array) $params));
		
		try 
		{
			$contact = $this->post($this->accountID .'/c/' . $this->clientFolderID . '/contacts', $data);
		}
		catch (Exception $e) 
		{
			throw $e;	
		}
		
		if ((isset($contact['contacts'])) && (is_array($contact['contacts'])) && (count($contact['contacts']) > 0)) 
		{
			$this->addToList($listID, $contact['contacts'][0]['contactId']);
		}

		return true;
	}

	/**
	 *  Adds a contact to an iContact List
	 *
	 *  API REFERENCE
	 *  https://www.icontact.com/developerportal/documentation/subscriptions
	 *
	 *  @param  string  $listID     
	 *  @param  string  $contactID  
	 */
	public function addToList($listID, $contactID)
	{
		$data = array(
			array(
				'contactId' => $contactID,
				'listId' => $listID,
				'status' => 'normal'
				)
			);
		$this->post($this->accountID .'/c/' . $this->clientFolderID . '/subscriptions',$data);
	}


	/**
	 * Get the last error returned by either the network transport, or by the API.
	 * If something didn't work, this should contain the string describing the problem.
	 * 
	 * @return  string  describing the error
	 */
	public function getLastError()
	{
		$body = $this->last_response['body'];

		$message = '';

		if (isset($body['errors']))
		{
			foreach ($body['errors'] as $error) {
				$message .= $error . ' ';
			}
		}

		return trim($message);
	}
}