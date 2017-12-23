<?php

/**
 * @author          Tassos.gr
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2017 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace NRFramework\Assignments;

defined('_JEXEC') or die;

use NRFramework\Assignment;

class K2 extends Assignment
{
    /**
     *  Pass check for K2 items
     *
     *  @return bool
     */
    public function passItems()
    {
        // return false if we are not viewing a K2 item
        if (!$this->request->id || 
            !$this->_passContext() || 
            $this->request->view != 'item')
		{
			return false;
        }
        
        $pass = false;

        // check item's id
        if (!empty($this->selection))
        {
            $pass = $this->passSimple($this->request->id, $this->selection);
        }

        // check items's text
        if (!empty($this->params->assign_k2_items_param_cont_keywords))
        {
            $keywords = $this->params->assign_k2_items_param_cont_keywords;
            // replace commas with space and convert to array
            if (is_string($keywords))
            {
                $keywords = str_replace(',', ' ', $keywords);
            }
            $keywords = $this->makeArray($keywords);
                
            $pass = $this->_passContentKeywords($keywords);
        }
        // check item's metakeywords
        if (!empty($this->params->assign_k2_items_param_meta_keywords))
        {
            $meta = $this->params->assign_k2_items_param_meta_keywords;
            // replace commas with space and convert to array
            if (is_string($meta))
            {
                $meta = str_replace(',', ' ', $meta);
            }
            $meta = $this->makeArray($meta);

            $pass = $this->_passMetaKeywords($meta);
        }


        return $pass;
    }

    /**
     *  Pass check for K2 categories
     *
     *  @return bool
     */
    public function passCategories()
    {
        if(!$this->_passContext())
        {
            return false;
        }

        // Check if we have a valid selection
		if (empty($this->selection))
		{
			return false;
		}

		$is_category = ($this->request->view == 'itemlist' && $this->request->layout == 'category') || $this->request->view == 'latest';
		$is_item     = $this->request->view == 'item';


		$inc_categories = false;
		$inc_items      = false;
		$inc_children   = $this->params->assign_k2_cats_param_inc_children;

		if (isset($this->params->assign_k2_cats_param_inc) && is_array($this->params->assign_k2_cats_param_inc))
		{
			$inc_categories = in_array('inc_categories', $this->params->assign_k2_cats_param_inc);
			$inc_items      = in_array('inc_items', $this->params->assign_k2_cats_param_inc);
		}

		// Check if we are in a valid context
		if (!($inc_categories && $is_category) && !($inc_items && $is_item))
		{
			return false;
		}

		$pass = false;

		$catids = $this->getCategoryIds($is_category);

		foreach ($catids as $catid)
		{
			if (!$catid)
			{
				continue;
			}

			$pass = in_array($catid, $this->selection);

			// Pass check on child items only
			if ($pass && $this->params->assign_k2_cats_param_inc_children == 2)
			{
				$pass = false;
				continue;
			}

			// Pass check for child items
			if (!$pass && $this->params->assign_k2_cats_param_inc_children)
			{
				$parent_ids = $this->getParentIDs($catid, 'k2_categories', 'parent');
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

    /**
     *  Pass check for K2 page types
     *
     *  @return bool
     */
    public function passPagetypes()
    {
        if (empty($this->selection) || !$this->_passContext())
        {
            return false;
        }

        $pagetype = $this->request->view . '_' . $this->request->layout;
        return $this->passSimple($pagetype, $this->selection);
    }

    /**
     *  Pass check for K2 Tags
     *
     *  @return bool
     */
    public function passTags()
    {
        if (empty($this->selection) || !$this->_passContext() || $this->request->view != 'item')
        {
            return false;
        }

        $id     = (int) $this->request->id;
        $q      = $this->db->getQuery(true);

        $q = $this->db->getQuery(true)
            ->select('t.id')
            ->from('#__k2_tags_xref AS tx')
            ->join('LEFT', '#__k2_tags AS t ON t.id = tx.tagID')
            ->where('tx.itemID = ' . $id)
            ->where('t.published = 1');
		$this->db->setQuery($q);
        $tags = $this->db->loadColumn();
        
        return $this->passSimple($tags, $this->selection);
    }

    /**
     *  Returns a K2 item
     *
     *  @param  array|string   Selected fields from the item
     *  @return object
     */
    protected function getK2Item($fields)
    {
        if (empty($fields))
        {
            return null;
        }

        if (is_array($fields))
        {
            $fields = array_map(function($el){
                return 'i.' . $el;
            }, $fields);
        }

        if (is_string($fields))
        {
            $fields = 'i.' . $fields;
        }

        $id     = (int) $this->request->id;
        $q      = $this->db->getQuery(true);

        $q->select($fields)
            ->from('#__k2_items as i')
            ->where('id = ' . $id);
        $this->db->setQuery($q);

        return $this->db->loadObject();
    }

    /**
	 *  Returns category IDs based on the active K2 view
	 *
	 *  @param   boolean  $is_category  The current view is a category view
	 *
	 *  @return  array                  The IDs
	 */
	private function getCategoryIds($is_category = false)
	{
		// If we are in category view return category's id
		if ($is_category)
		{
			return (array) $this->request->id;
		}

		// If we are in article view return article's category id
		if ($this->request->view == 'item')
		{
            $res = $this->getK2Item('catid');
            return (array) $res->catid;
		}

		return false;
    }
    
    /**
     *  Check if we are in correct context
     *
     *  @return bool
     */
    protected function _passContext()
    {
        if ($this->request->option != 'com_k2')
		{
			return false;
        }
        
        return true;
    }

    /**
     *  Checks item's content for keywords.
     *  Used by passItems
     *
     *  @return bool
     */
    protected function _passContentKeywords($keywords)
    {
        $fields = ['introtext', 'fulltext'];
        $item = $this->getK2Item($fields);
        if (!$item)
        {
            return false;
        }

        $text = '';
        foreach ($fields as $field)
        {
            if (!isset($item->{$field}))
            {
                return false;
            }
            $text = trim($text . ' ' . $item->{$field});
        }

        if (empty($text))
        {
            return false;
        }

        foreach ($keywords as $k)
        {
            $regex = '/'. preg_quote($k) .'/';
            if (!preg_match($regex, $text))
            {
                continue;
            }
            return true;
        }

        return false;
    }

    /**
     *  Checks item's meta keyywords.
     *  Used by passItems
     *
     *  @return bool
     */
    protected function _passMetaKeywords($param_keywords)
    {
        // get current item's meta keywords
        $item = $this->getK2Item('metakey');
        if (!isset($item->metakey) || empty($item->metakey))
        {
            return false;
        }
        $keywords = $item->metakey;

        if (!is_string($keywords))
        {
            return false;
        }

        foreach($param_keywords as $pk)
        {
            if (empty($pk))
            {
                continue;
            }
            $regex = '/'. preg_quote($pk) .'/';
            if (!preg_match($regex, $keywords))
            {
                continue;
            }
            return true;
        }
        return false;
    }
}