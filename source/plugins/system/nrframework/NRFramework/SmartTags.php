<?php 

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2018 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace NRFramework;

defined('_JEXEC') or die('Restricted access');

use \NRFramework\WebClient;

/**
 *   SmartTags replaces placeholder variables in a string
 */
class SmartTags
{
	/**
	 * Factory Class
	 *
	 * @var object
	 */
	private $factory;

	/**
	 *  Joomla User Object
	 *
	 *  @var  object
	 */
	private $user = null;

	/**
	 *  Tags Array
	 *
	 *  @var  array
	 */
	private $tags = [];

	/**
	 *  Tag placeholder
	 *
	 *  @var  string
	 */
	private $placeholder = '{}';

	/**
	 *  Class constructor
	 */
	public function __construct($options = array(), $factory = null)
	{
		// Set User
		if (isset($options['user']))
		{
			$this->user = $options['user'];
		}

		// Set Factory
        if (!$factory)
        {
            $factory = new \NRFramework\Factory();
        }

		$this->factory = $factory;

		$url = $this->factory->getURI();

		$this->tags = [
			// Server
			'url'			=> $url->toString(),
			'url.encoded'	=> urlencode($url->toString()),
			'url.path'		=> $url::current(),
			'referrer'	    => $this->factory->getApplication()->input->server->get('HTTP_REFERER', 'RAW', ''),
			'ip'			=> $this->factory->getApplication()->input->server->get('REMOTE_ADDR'),

			// Site 
			'site.name'     => \JFactory::getConfig()->get('sitename'),
			'site.email'    => \JFactory::getConfig()->get('mailfrom'),
			'site.url'      => $url::root(),

			// Client
			'client.device'    => WebClient::getDeviceType(),
			'client.os'        => WebClient::getOS(),
			'client.browser'   => WebClient::getBrowser()['name'],
			'client.useragent' => WebClient::getClient()->userAgent,
			
			// Other
			'randomid'		=> bin2hex(\JCrypt::genRandomBytes(8))
		];

		$this->addPageTags();
		$this->addDateTags();
		$this->addQueryStringTags();
		$this->addUserTags();
	}

	/**
	 * Add User based tags
	 *
	 * @return void
	 */
	private function addUserTags()
	{
		$user = $this->factory->getUser($this->user);

		// Proper capitalize name
		$name = ucwords(strtolower($user->name));
		
		// Set First and Last name
    	$nameParts = explode(' ', $name, 2);
    	$firstname = trim($nameParts[0]);
    	$lastname  = isset($nameParts[1]) ? trim($nameParts[1]) : $user->firstname;

		$tags = [
			'id'        => $user->id,
			'name'      => $name,
			'firstname' => $firstname,
			'lastname'  => $lastname,
			'login'     => $user->username,
			'email'     => $user->email,
			'groups'    => implode(',', $user->groups),
		];

		$this->add($tags, 'user.');
	}

	/**
	 * Add Query String Tags to the collection
	 *
	 * @return void
	 */
	private function addQueryStringTags()
	{
		$query = \JUri::getInstance()->getQuery(true);

		if (empty($query))
		{
			return;
		}

		$tags = [];

		foreach ($query as $key => $value)
		{
			$tags[strtolower($key)] = $value;
		}

		$this->add($tags, 'querystring.');
	}

	/**
	 * Add Date-based Tags to the collection
	 *
	 * @return void
	 */
	private function addDateTags()
	{
		$tz   = new \DateTimeZone($this->factory->getApplication()->getCfg('offset', 'GMT'));
		$date = $this->factory->getDate()->setTimezone($tz);

		$tags = [
			'time' => $date->format('H:i', true),
			'date' => $date->format('Y-m-d H:i:s', true)
		];

		$this->add($tags);
	}

	/**
	 * Include Page-related Tags to the collection
	 *
	 * @return void
	 */
	private function addPageTags()
	{
		$doc = $this->factory->getDocument();

		$tags = [
			'title'     => $doc->getTitle(),
			'desc'      => $doc->getMetaData('description'),
			'keywords'  => $doc->getMetaData('keywords'),
			'lang'      => $doc->getLanguage(),
			'generator' => $doc->getGenerator()
		];

		$this->add($tags, 'page.');
	}

	/**
	 *  Returns list of all tags
	 *
	 *  @return  array
	 */
	public function get($prepare = true)
	{
		if ($prepare)
		{
			$this->prepare();
		}

		return $this->tags;
	}

	/**
	 *  Sets the tag placeholder
	 *  For example: {} or [] or {{}} or {[]}
	 *
	 *  @param  string  $placeholder  
	 */
	public function setPlaceholder($placeholder)
	{
		$this->placeholder = $placeholder;
		return $this;
	}

	/**
	 *  Returns placeholder in 2 pieces
	 *
	 *  @return  array
	 */
	private function getPlaceholder()
	{
		return str_split($this->placeholder, strlen($this->placeholder) / 2);
	}

	/**
	 *  Adds Custom Tags to the list
	 *
	 *  @param  Mixed   $tags    Tags list (Array or Object)
	 *  @param  String  $prefix  A string to prefix all keys
	 */
	public function add($tags, $prefix = null)
	{
		// Convert Object to array
		if (is_object($tags))
		{
			$tags = (array) $tags;
		}

		if (!is_array($tags) || !count($tags))
		{
			return;
		}

		// Add Prefix to keys
		if ($prefix)
		{
			foreach ($tags as $key => $value)
			{
		        $newKey = $prefix . $key;
		        $tags[$newKey] = $value;
		        unset($tags[$key]);
			}
		}

		$this->tags = array_merge($this->tags, $tags);
		
		return $this;
	}

    /**
     *  Replace tags in object
     *
     *  @param   mixed  $obj  The data object for search for smarttags
     *
     *  @return  mixed
     */
    public function replace($obj)
    {
    	$this->prepare();

    	// Convert object to array
    	$data = is_object($obj) ? (array) $obj : $obj;

    	// Array case
    	if (is_array($data))
    	{
    		foreach ($data as $key => $value)
    		{
    			if (is_array($value) || is_object($value))
    			{
    				continue;
				}
				
    			$data[$key] = $this->clean(strtr($value, $this->tags));
    		}

			// Revert object back to its original state
			$data = is_object($obj) ? (object) $data : $data;
	   		return $data;
		}
		
    	// String case
    	return $this->clean(strtr($data, $this->tags));
	}

	/**
	 * Remove unreplaced tags from string
	 *
	 * @param  string $data
	 *
	 * @return void
	 */
	private function clean($data)
	{
		if (!is_string($data))
		{
			return $data;
		}

		$data = str_replace('{referrer}', '', $data);

		return preg_replace('#{(querystring|user).(.*?)}#s', '', $data);
	}
	
    /**
     *  Prepares tags by adding the placeholder to each key
     *
     *  @return  void
     */
    private function prepare()
    {
    	$placeholder = $this->getPlaceholder();

    	foreach ($this->tags as $key => $variable)
    	{
    		// Check if tag is already prepared
    		if (substr($key, 0, 1) == $placeholder[0])
			{
				continue;
			}

			// If the object passed to $replace method is in JSON format
			// we need to escape double quotes in the tag value to prevent JSON failure
			if (is_string($variable))
			{
				$this->tags[$placeholder[0] . $key . $placeholder[1]] = addcslashes($variable, '"');
			}

			unset($this->tags[$key]);
    	}
    }
}

?>