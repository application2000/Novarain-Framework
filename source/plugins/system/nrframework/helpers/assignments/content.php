<?php

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2015 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

defined('_JEXEC') or die;

class nrFrameworkAssignmentsContent extends nrFrameworkAssignmentsHelper
{

	private $params;
	private $selection;
	private $request;
	private $article;

	public function __construct($assignment)
	{
		parent::__construct();
		$this->params    = $assignment->params;
		$this->selection = $assignment->selection;

		$this->request         = new stdClass();
		$this->request->view   = $this->app->input->get("view");
		$this->request->option = $this->app->input->get("option");
		$this->request->id     = $this->app->input->get("id");
		$this->request->Itemid = $this->app->input->getInt('Itemid', 0);

		$this->getItem();

	}

	public function passCategories()
	{
		$this->params->inc_categories = false;
		$this->params->inc_articles   = false;
		$this->params->inc_others     = false;
		$this->params->inc_children   = false;

		if ($this->params->assign_contentcats_param_inc && is_array($this->params->assign_contentcats_param_inc))
		{
			$this->params->inc_categories = in_array('inc_categories', $this->params->assign_contentcats_param_inc);
			$this->params->inc_articles   = in_array('inc_articles', $this->params->assign_contentcats_param_inc);
			$this->params->inc_others     = in_array('inc_others', $this->params->assign_contentcats_param_inc);
		}

		if ($this->params->assign_contentcats_param_inc_children)
		{
			$this->params->inc_children = $this->params->assign_contentcats_param_inc_children;
		}

		if ($this->request->option != 'com_content')
		{
			return false;
		}

		if (empty($this->selection))
		{
			return false;
		}

		$is_content  = in_array($this->request->option, array('com_content'));
		$is_category = in_array($this->request->view, array('category'));
		$is_item     = in_array($this->request->view, array('', 'article', 'item', 'form'));

		if (
			!($this->params->inc_categories && $is_content && $is_category)
			&& !($this->params->inc_articles && $is_content && $is_item)
			&& !($this->params->inc_others && !($is_content && ($is_category || $is_item)))
		)
		{
			return false;
		}

		$pass = false;

		if (
			$this->params->inc_others
			&& !($is_content && ($is_category || $is_item))
			&& $this->article
		)
		{
			if (!isset($this->article->id) && isset($this->article->slug))
			{
				$this->article->id = (int) $this->article->slug;
			}

			if (!isset($this->article->catid) && isset($this->article->catslug))
			{
				$this->article->catid = (int) $this->article->catslug;
			}

			$this->request->id   = $this->article->id;
			$this->request->view = 'article';
		}

		$catids = $this->getCategoryIds($is_category);

		foreach ($catids as $catid)
		{
			if (!$catid)
			{
				continue;
			}

			$pass = in_array($catid, $this->selection);

			if ($pass && $this->params->inc_children == 2)
			{
				$pass = false;
				continue;
			}

			if (!$pass && $this->params->inc_children)
			{
				$parent_ids = $this->getCatParentIds($catid);
				$parent_ids = array_diff($parent_ids, array('1'));
				foreach ($parent_ids as $id)
				{
					if (in_array($id, $this->selection))
					{
						$pass = true;
						break;
					}
				}

				unset($parent_ids);
			}
		}

		return $pass;
	}

	private function getCategoryIds($is_category = false)
	{
		if ($is_category)
		{
			return (array) $this->request->id;
		}

		if (!$this->article && $this->request->id)
		{
			$this->article = JTable::getInstance('content');
			$this->article->load($this->request->id);
		}

		if ($this->article && $this->article->catid)
		{
			return (array) $this->article->catid;
		}

		$catid      = $this->app->input->getInt('catid', $this->app->getUserState('com_content.articles.filter.category_id'));
		$menuparams = $this->getMenuItemParams($this->request->Itemid);

		if ($this->request->view == 'featured')
		{
			return isset($menuparams->featured_categories) ? (array) $menuparams->featured_categories : (array) $catid;
		}

		return isset($menuparams->catid) ? (array) $menuparams->catid : (array) $catid;
	}

	public function passArticles()
	{

		if (!$this->request->id || !(($this->request->option == 'com_content' && $this->request->view == 'article')))
		{
			return false;
		}

		if (parent::passByType($this, 'Content.Ids'))
		{
			return true;
		}

		if (parent::passByType($this, 'Content.Authors'))
		{
			return true;
		}

		return false;
	}

	public function passIds()
	{
		if (empty($this->selection))
		{
			return null;
		}

		return in_array($this->request->id, $this->selection);
	}

	public function passAuthors()
	{
		if (isset($this->params->assign_contentarticles_param_authors))
		{
			$this->params->authors = $this->params->assign_contentarticles_param_authors;
		}

		if (empty($this->params->authors))
		{
			return null;
		}

		if (!isset($this->article->{'created_by'}))
		{
			return false;
		}

		$author = $this->article->{'created_by'};

		if (empty($author))
		{
			return false;
		}

		$this->params->authors = $this->makeArray($this->params->authors);

		return in_array($author, $this->params->authors);
	}

	public function getItem()
	{
		if ($this->article)
		{
			return $this->article;
		}

		if ($this->request->option != 'com_content')
		{
			return false;
		}

		if (!$this->request->id)
		{
			return false;
		}

		if (!class_exists('ContentModelArticle'))
		{
			require_once JPATH_SITE . '/components/com_content/models/article.php';
		}

		$model = JModelLegacy::getInstance('article', 'contentModel');

		if (!method_exists($model, 'getItem'))
		{
			return null;
		}

		try {
			$this->article = $model->getItem($this->request->id);
		}
		catch (JException $e)
		{
			return null;
		}

		return $this->article;
	}

	public function getCatParentIds($id = 0, $table = 'categories', $parent = 'parent_id', $child = 'id')
	{
		if (!$id)
		{
			return array();
		}

		$hash = md5('getParentIds_' . $id . '_' . $table . '_' . $parent . '_' . $child);

		if (NRCache::has($hash))
		{
			return NRCache::get($hash);
		}

		$parent_ids = array();

		while ($id)
		{
			$query = $this->db->getQuery(true)
				->select('t.' . $parent)
				->from('#__' . $table . ' as t')
				->where('t.' . $child . ' = ' . (int) $id);
			$this->db->setQuery($query);
			$id = $this->db->loadResult();

			if (!$id || in_array($id, $parent_ids))
			{
				break;
			}

			$parent_ids[] = $id;
		}
		require_once __DIR__ . '/../cache.php';
		return NRCache::set(
			$hash,
			$parent_ids
		);
	}

	public function getMenuItemParams($id = 0)
	{
		$query = $this->db->getQuery(true)
			->select('m.params')
			->from('#__menu AS m')
			->where('m.id = ' . (int) $id);
		$this->db->setQuery($query);
		$params = $this->db->loadResult();
		
		return json_decode($params);
	}
}
