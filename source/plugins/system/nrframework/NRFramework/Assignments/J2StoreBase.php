<?php

/**
 * @author          Tassos.gr
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2018 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace NRFramework\Assignments;

defined('_JEXEC') or die;

use NRFramework\Assignment;

abstract class J2StoreBase extends Assignment
{
    /**
     *  Request information
     */
    protected $request = null;

    public function __construct($options, $factory)
	{
		parent::__construct($options, $factory);
        
        $request = new \stdClass;

        $request->view   = $this->app->input->get('view');
        $request->task   = $this->app->input->get('task');
        $request->option = $this->app->input->get('option');
        $request->id     = $this->app->input->get('id');

        $this->request = $request;
    }

    /**
     *  Returns a J2Store item from the database
     *
     *  @return object
     */
    protected function getItem()
    {
        $hash  = md5('j2storeitem');
        $cache = $this->factory->getCache(); 

        if ($cache->has($hash))
        {
            return $cache->get($hash);
        }

		// Get product information
        require_once JPATH_ADMINISTRATOR . '/components/com_j2store/helpers/product.php';

		// Make sure J2Store is loaded
		if (!class_exists('J2Product'))
		{
			return;
        }

		$item = \J2Product::getInstance()->setId($this->request->id)->getProduct();

		if (!is_object($item) || !isset($item->source))
		{
			return;
		}

        return $cache->set($hash, $item);
    }   

    /**
     *  Indicates whether the page is a category page
     *
     *  @return  boolean
     */
    protected function isCategory()
    {
        return ($this->request->view == 'products' && is_null($this->app->input->get('task')));
    }

    /**
     *  Indicates whether the page is a single page
     *
     *  @return  boolean
     */
    protected function isItem()
    {
        return ($this->request->task == 'view');
    }

    /**
     *  Check if we are in correct context
     *
     *  @return bool
     */
    protected function passContext()
    {
        return ($this->request->option == 'com_j2store');
    }  
}
