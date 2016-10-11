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

class NR_Wrapper
{
	protected $api_key;
	protected $api_endpoint;
	protected $request_successful = false;
	protected $last_error         = '';
	protected $last_response      = array();
	protected $last_request       = array();
	protected $timeout            = 60;
	protected $options;

	public function __construct()
	{
		$this->options       = new JRegistry;
		$this->last_response = array('headers' => null, 'body' => null);
		$this->options->set('timeout', $this->timeout);
	}

	/**
	 * Setter method for the API Key
	 * @param string $api_key 
	 * @throws \Exception 
	 */
	public function setApiKey($api_key)
	{
		if (!empty($api_key))
		{
			$this->api_key = $api_key;
		}
		else
		{
			throw new \Exception("Invalid API key `{$api_key}` supplied.");
		}
	}

	/**
	 * Was the last request successful?
	 * @return bool  True for success, false for failure
	 */
	public function success()
	{
		return $this->request_successful;
	}

	/**
	 * Get the last error returned by either the network transport, or by the API.
	 * If something didn't work, this should contain the string describing the problem.
	 * @return  array|false  describing the error
	 */
	public function getLastError()
	{
		return $this->last_error ?: false;
	}

	/**
	 * Get an array containing the HTTP headers and the body of the API response.
	 * @return array  Assoc array with keys 'headers' and 'body'
	 */
	public function getLastResponse()
	{
		return $this->last_response;
	}

	/**
	 * Get an array containing the HTTP headers and the body of the API request.
	 * @return array  Assoc array
	 */
	public function getLastRequest()
	{
		return $this->last_request;
	}

	/**
	 * Make an HTTP DELETE request - for deleting data
	 * @param   string $method URL of the API request method
	 * @param   array $args Assoc array of arguments (if any)
	 * @return  array|false   Assoc array of API response, decoded from JSON
	 */
	public function delete($method, $args = array())
	{
		return $this->makeRequest('delete', $method, $args);
	}

	/**
	 * Make an HTTP GET request - for retrieving data
	 * @param   string $method URL of the API request method
	 * @param   array $args Assoc array of arguments (usually your data)
	 * @return  array|false   Assoc array of API response, decoded from JSON
	 */
	public function get($method, $args = array())
	{
		return $this->makeRequest('get', $method, $args);
	}

	/**
	 * Make an HTTP PATCH request - for performing partial updates
	 * @param   string $method URL of the API request method
	 * @param   array $args Assoc array of arguments (usually your data)
	 * @return  array|false   Assoc array of API response, decoded from JSON
	 */
	public function patch($method, $args = array())
	{
		return $this->makeRequest('patch', $method, $args);
	}

	/**
	 * Make an HTTP POST request - for creating and updating items
	 * @param   string $method URL of the API request method
	 * @param   array $args Assoc array of arguments (usually your data)
	 * @return  array|false   Assoc array of API response, decoded from JSON
	 */
	public function post($method, $args = array())
	{
		return $this->makeRequest('post', $method, $args);
	}

	/**
	 * Make an HTTP PUT request - for creating new items
	 * @param   string $method URL of the API request method
	 * @param   array $args Assoc array of arguments (usually your data)
	 * @return  array|false   Assoc array of API response, decoded from JSON
	 */
	public function put($method, $args = array())
	{
		return $this->makeRequest('put', $method, $args);
	}

	/**
	 * Performs the underlying HTTP request. Not very exciting.
	 * @param  string $http_verb The HTTP verb to use: get, post, put, patch, delete
	 * @param  string $method The API method to be called
	 * @param  array $args Assoc array of parameters to be passed
	 * @return array|false Assoc array of decoded result
	 * @throws \Exception
	 */
	private function makeRequest($http_verb, $method, $args = array())
	{

		// check to see if the api_endpoint already has GET variables
		$method = (strpos($this->api_endpoint, '?') === false) ?  '/' . $method : $method;

		$url = $this->api_endpoint . $method;

		$this->last_error         = '';
		$this->request_successful = false;
		$response                 = array('headers' => null, 'body' => null);
		$this->last_response      = $response;

		$this->last_request = array(
			'method'  => $http_verb,
			'path'    => $method,
			'url'     => $url,
			'body'    => '',
			'timeout' => $this->timeout,
		);

		$http = JHttpFactory::getHttp($this->options);

		switch ($http_verb)
		{
		case 'post':
			$this->attachRequestPayload($args);
			$response = $http->post($url, $this->last_request['body']);
			break;

		case 'get':
			$query = http_build_query($args, '', '&');
			$response = (strpos($url,'?') !== false) ? $http->get($url . '&' . $query) : $http->get($url . '?' . $query);
			break;

		case 'delete':
			$response = $http->delete($url);
			break;

		case 'patch':
			$this->attachRequestPayload($args);
			$response = $http->patch($url, $this->last_request['body']);
			break;

		case 'put':
			$this->attachRequestPayload($args);
			$response = $http->put($url, $this->last_request['body']);
			break;
		}

		$responseData['body']    = $response->body;
		$responseData['headers'] = $response->headers;
		$responseData['code']    = $response->code;

		if (isset($responseData['headers']['request_header']))
		{
			$this->last_request['headers'] = $responseData['headers']['request_header'];
		}

		if ($responseData['body'] === false)
		{
			$this->last_error = "Fatal Error Code:" . $response->code;
		}

		$formattedResponse = $this->formatResponse($responseData);

		$this->determineSuccess($responseData, $formattedResponse);

		return $formattedResponse;
	}

	/**
	 * Encode the data and attach it to the request
	 * @param   array $data Assoc array of data to attach
	 */
	private function attachRequestPayload($data)
	{
		$encoded                    = json_encode($data);
		$this->last_request['body'] = $encoded;
	}

	/**
	 * Decode the response and format any error messages for debugging
	 * @param array $response The response from the JHTTP request
	 * @return array|false    The JSON decoded into an array
	 */
	private function formatResponse($response)
	{
		$this->last_response = $response;

		if (!empty($response['body']))
		{
			return json_decode($response['body'], true);
		}

		return false;
	}

	/**
	 * Check if the response was successful or a failure. If it failed, store the error.
	 * @param array $response The response from the JHTTP request
	 * @param array|false $formattedResponse The response body payload from the JHTTP request
	 * @return bool     If the request was successful
	 */
	private function determineSuccess($response, $formattedResponse)
	{
		$status = $this->findHTTPStatus($response, $formattedResponse);

		if ($status >= 200 && $status <= 299)
		{
			$this->request_successful = true;
			return true;
		}

		if (isset($formattedResponse['detail']))
		{
			$this->last_error = sprintf('%d: %s', $formattedResponse['status'], $formattedResponse['detail']);
			return false;
		}

		$this->last_error = 'Unknown error, call getLastResponse() to find out what happened.';
		return false;
	}

	/**
	 * Find the HTTP status code from the headers or API response body
	 * @param array $response The response from the JHTTP request
	 * @param array|false $formattedResponse The response body payload from the JHTTP request
	 * @return int  HTTP status code
	 */
	private function findHTTPStatus($response, $formattedResponse)
	{
		if (!empty($response['code']) && isset($response['code']))
		{
			return (int) $response['code'];
		}

		return 418;
	}
}