<?php

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2018 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

namespace NRFramework\Assignments;

defined('_JEXEC') or die;

use NRFramework\Assignment;

class ContentBase extends Assignment
{
	/**
	 *  Request information
	 * 
	 *  @var object
	 */
	protected $request = null;

	/**
	 *  Class constructor
	 *
	 *  @param  object  $assignment
	 *  @param  object  $factory
	 */
	public function __construct($assignment, $factory)
	{
		parent::__construct($assignment, $factory);

		$request = new \stdClass;
		$request->view   = $this->app->input->get('view');
		$request->option = $this->app->input->get('option');
		$request->id     = $this->app->input->get('id');
		$this->request = $request;
	}

	/**
     *  Check if we are in correct context
     *
     *  @return bool
     */
    protected function passContext()
    {
        return ($this->request->option == 'com_content');
    }  

    /**
     *  Indicates whether the page is a K2 Category page
     *
     *  @return  boolean
     */
    protected function isCategory()
    {
        return ($this->request->view == 'category');
    }

    /**
     *  Indicates whether the page is a K2 Category page
     *
     *  @return  boolean
     */
    protected function isItem()
    {
        return ($this->request->view == 'article' && $this->request->id);
	}
	
	/**
	 *  Get current Joomla article object
	 *
	 *  @return  object
	 */
	public function getItem()
	{
        $hash  = md5('contentItem');
        $cache = $this->factory->getCache(); 

        if ($cache->has($hash))
        {
            return $cache->get($hash);
        }

		// Setup model
		if (defined('nrJ4'))
		{	
			$model = new \Joomla\Component\Content\Site\Model\ArticleModel(['ignore_request' => true]);
			$model->setState('article.id', $this->request->id);
			$model->setState('params', $this->app->getParams());
		} else 
		{
			require_once JPATH_SITE . '/components/com_content/models/article.php';
			$model = \JModelLegacy::getInstance('Article', 'ContentModel');
		}

		try
		{
			$item = $model->getItem($this->request->id);
			return $cache->set($hash, $item);
		}
		catch (\JException $e)
		{
			return null;
		}
	}
}
