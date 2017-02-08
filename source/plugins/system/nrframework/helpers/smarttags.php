<?php 

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2016 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

defined('_JEXEC') or die('Restricted access');

require_once __DIR__ . "/cache.php";

/**
 *   SmartTags replaces placeholder variables in a string
 */
class NRSmartTags
{
	/**
	 *  Tags Array
	 *
	 *  @var  array
	 */
	private $tags = array();

	/**
	 *  Tag placeholder
	 *
	 *  @var  string
	 */
	private $placeholder = "{}";

	/**
	 *  Class constructor
	 */
	function __construct()
	{
		$user = JFactory::getUser();

		$this->tags = array(
			// Server
			'url'			=> JURI::getInstance()->toString(),
			'url.path'		=> JURI::current(),
			'referrer'	    => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null,
			'ip'			=> isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null,

			 // Site 
			'site.name'     => JFactory::getConfig()->get('sitename'),
			'site.url'      => JURI::root(),

			// Joomla User
			'user.id'       => $user->id,
			'user.name'     => $user->name,
			'user.username' => $user->username,
			'user.email'    => $user->email,
			
			// Other
			'date'			=> JFactory::getDate()->format('Y-m-d H:i:s')
		);
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
	 *  Adds custom tags to list
	 *
	 *  @param  array  $array  Tags array in key value pairs
	 */
	public function add($array)
	{
		if (!is_array($array) || !count($array))
		{
			return;
		}

		$this->tags = array_merge($this->tags, $array);
		return $this;
	}

    /**
     *  Replace tags in object
     *
     *  @param   mixed  $data  The data object with tags placeholders
     *
     *  @return  mixed
     */
    public function replace($data)
    {
		$hash = md5('smartTags' . serialize($data));

		if (NRCache::has($hash))
		{
			return NRCache::get($hash);
		}

    	$this->prepare();

    	// $data is array
    	if (is_array($data))
    	{
    		foreach ($data as $key => $value)
    		{
    			if (is_array($value) || is_object($value))
    			{
    				continue;
    			}

    			$data[$key] = strtr($value, $this->tags);
    		}

    		return NRCache::set($hash, $data);
    	}

    	// $data is tring
    	return NRCache::set($hash, strtr($data, $this->tags));
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
    		$this->tags[$placeholder[0] . $key . $placeholder[1]] = $variable;
			unset($this->tags[$key]);
    	}
    }
}

?>