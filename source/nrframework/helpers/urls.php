<?php 

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2015 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

defined('_JEXEC') or die;

class NRURLs {

    private $url;
    private $shortener;
    private $cache;

    function __construct($url)
    {
        $this->set($url);
        $this->setCache(true);
    }

    public function set($url)
    {
        $this->url = trim($url);
    }

    public function setCache($state)
    {
        $this->cache = (bool) $state;
    }

    public function setShortener($service)
    {
        $this->shortener = $service;
    }

    public function get()
    {
        return $this->url;
    }

    public function validate($url_ = null)
    {

        $url = isset($url_) ? $url_ : $this->url;

        if (!$url)
        {
            return false;
        }

        // Remove all illegal characters from the URL
        $url = filter_var($url, FILTER_SANITIZE_URL);

        // Validate URL
        if (!filter_var($url, FILTER_VALIDATE_URL) === false) {
            return true;
        }

        return false;
    }

    public function getShort()
    {
        if (!$this->validate())
        {
            return false;
        }

        if (!function_exists('curl_version'))
        {
            return false;
        }

        if (!isset($this->shortener))
        {
            return false;
        }

        // If cached is enabled and the URL is already cached return it
        if ($this->cache && $cached = $this->load()) {
            return $cached;
        }

        // URL is not cached. Let's create it.
        $ch = curl_init();

        // Bitly Service
        if ($this->shortener->name == "bitly")
        {
            if (!isset($this->shortener->login) || !isset($this->shortener->api))
            {
                return false;
            }

            $baseURL = 'http://api.bit.ly/v3/shorten?login='.$this->shortener->login.'&apiKey='.$this->shortener->api.'&format=txt&uri='.urlencode($this->url);
        }

        // TinyURL Service
        if ($this->shortener->name == "tinyurl")
        {
            $baseURL = "http://tinyurl.com/api-create.php?url=".urlencode($this->url);
        }

        // Google Service
        if ($this->shortener->name == "google")
        {
            if (!isset($this->shortener->api))
            {
                return false;
            }

            $baseURL = "https://www.googleapis.com/urlshortener/v1/url?key=".$this->shortener->api;

            $data = array( 'longUrl' => $this->url );
            $data_string = '{ "longUrl": "'.$this->url.'" }';

            curl_setopt($ch, CURLOPT_POST, count($data));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        }

        if (!$baseURL)
        {
            return false;
        }
        
        curl_setopt($ch, CURLOPT_URL, $baseURL);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);     
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
        
        $data = curl_exec($ch);

        // Custom check for Google Shortener Service
        if ($this->shortener->name == "google")
        {
            $data = json_decode($data);

            if (!isset($data->id))
            {
                return false;
            }

            $data = $data->id;
        }

        curl_close($ch);

        // Return the original URL if we don't have a valid short URL
        if (!$this->validate($data))
        {  
            return false;
        }

        // Save shorten URL to cache
        if ($this->cache)
        {
            $this->save($data);
        }

        return $data;
    }

    protected function getCacheFile()
    {
        return JPATH_SITE."/cache/nr_url_".$this->shortener->name."_".MD5($this->url).".txt";
    }

    private function save($content)
    {
        JFile::write($this->getCacheFile(), $content);
    }

    private function load()
    {
        $file = $this->getCacheFile();

        if (!JFile::exists($file))
        {
            return false;
        }

        $url = JFile::read($file);

        if (!$this->validate($url)) {
            return false;
        }

        return $url;
    }
}

?>