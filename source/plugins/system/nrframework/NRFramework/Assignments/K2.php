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

abstract class K2 extends Assignment
{
    /**
     *  Request information
     */
    protected $request = null;

    public function __construct($options, $factory)
	{
		parent::__construct($options, $factory);
        
        $request = new \stdClass;

        $request->view   = $this->app->input->get("view");
        $request->task   = $this->app->input->get("task");
        $request->option = $this->app->input->get("option");
        $request->layout = $this->app->input->get('layout', '', 'string');
        $request->id     = $this->app->input->get("id");

        $this->request = $request;
    }
    
    /**
     *  Gets K2 item's id using K2Model
     *
     *  @return int|null
     */
    protected function getItemID()
    {
        if (!$this->isItem())
        {
            return;
        }

        $item = $this->getK2Item();

        if (is_object($item) && isset($item->id))
		{
			return (int) $item->id;
        }
    }

    /**
     *  Returns a K2 item
     *
     *  @return object|null
     */
    protected function getK2Item()
    {
        $cache = $this->factory->getcache();
        $hash  = md5('k2assitem');

        if ($cache->has($hash))
        {
            return $cache->get($hash);
        }

        return $cache->set($hash, \JModelLegacy::getInstance('Item', 'K2Model')->getData());
    }   

    /**
     *  Indicates whether the page is a K2 Category page
     *
     *  @return  boolean
     */
    protected function isCategory()
    {
        return ($this->request->layout == 'category' || $this->request->task == 'category' || $this->request->view == 'latest');
    }

    /**
     *  Indicates whether the page is a K2 Category page
     *
     *  @return  boolean
     */
    protected function isItem()
    {
        return ($this->request->view == 'item' && $this->request->id);
    }

    /**
     *  Check if we are in correct context
     *
     *  @return bool
     */
    protected function passContext()
    {
        if ($this->request->option != 'com_k2')
		{
			return false;
        }
        
        return true;
    }    
}
