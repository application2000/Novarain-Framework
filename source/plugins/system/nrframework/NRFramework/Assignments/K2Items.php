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
use NRFramework\Assignments\K2;

class K2Items extends K2
{
    /**
     *  Pass check for K2 items
     *
     *  @return bool
     */
    public function passK2Items()
    {
        // return false if we are not viewing a K2 item
        if (!$this->request->id || 
            !$this->_passContext() || 
            $this->request->view != 'item')
		{
			return false;
        }

        $id = $this->getItemID();
        if (!$id)
        {
            return false;
        }
        
        $pass = false;

        // check item's id
        if (!empty($this->selection))
        {
            $pass = $this->passSimple($id, $this->selection);
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
