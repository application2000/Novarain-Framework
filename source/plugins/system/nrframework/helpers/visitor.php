<?php 

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2016 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

defined('_JEXEC') or die('Restricted access');

class NRVisitor
{
   /**
     *  Class instance
     *
     *  @var  object
     */
    private static $instance;

	/**
	 *  Cookie Name
	 *
	 *  @var  string
	 */
	private $cookieName = "nrid";

	/**
	 *  Maximum age of visitors token id
	 *
	 *  @var  Integer
	 */
	private $expire = 90000000;

    /**
     *  Cookies Object
     *
     *  @var  object
     */
    private $cookies;

    /**
     *  Class constructor
     */
    private function __construct()
    {
        $this->cookies = JFactory::getApplication()->input->cookie;

        $token = $this->cookies->get($this->cookieName, null);

        if ($token === null)
        {
            $this->saveToken($this->createToken());
        }
    }

    /**
     *  Returns class instance
     *
     *  @return  object
     */
    public static function getInstance()
    {
        if (is_null(self::$instance))
        {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     *  Get a visitor's unique token id, if a token isn't set yet one will be generated.
     *
     *  @param   boolean   $forceNew  If true, force a new token to be created
     *  
     *  @return  string    The session token
     */
    public function getToken($forceNew = false)
    {
        return $this->cookies->get($this->cookieName);
    }

    /**
     *  Create a token-string
     *
     *  @param   integer $length  Length of string
     *
     *  @return  string  Generated token
     */
    private function createToken($length = 8)
    {
        return bin2hex(JCrypt::genRandomBytes($length));
    }

    /**
     *  Saves the cookie to the visitor's browser
     *
     *  @param   string  $value  Cookie Value
     *
     *  @return  void
     */
    private function saveToken($value)
    {
        $this->cookies->set($this->cookieName, $value, time() + $this->expire, "/");
    }
}

?>