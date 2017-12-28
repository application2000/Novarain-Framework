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

class K2Item extends K2
{
    /**
     *  Pass check for K2 items
     *
     *  @return bool
     */
    public function passK2Item()
    {
        // return false if we are not viewing a K2 item
        if (!$this->request->id || 
            !$this->passContext() || 
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
            $keywords = $this->splitKeywords($this->params->assign_k2_items_param_cont_keywords);                
            $pass     = $this->passContentKeywords($keywords);
        }
        // check item's metakeywords
        if (!empty($this->params->assign_k2_items_param_meta_keywords))
        {
            $meta = $this->splitKeywords($this->params->assign_k2_items_param_meta_keywords);
            $pass = $this->passMetaKeywords($meta);
        }

        return $pass;
    }

    /**
     *  Checks item's content for keywords.
     *  Used by passItems
     *  
     *  @param  string $keywords
     *  @return bool
     */
    protected function passContentKeywords($keywords)
    {
        $fields = ['introtext', 'fulltext'];
        $item = $this->getK2Item();
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
     *  @param  string $param_keywords
     *  @return bool
     */
    protected function passMetaKeywords($param_keywords)
    {
        // get current item's meta keywords
        $item = $this->getK2Item();
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


    /**
     *  Splits a keyword string on commas and newlines
     *
     * @param string $keywords
     * @return array
     */
    protected function splitKeywords($keywords)
    {
        if (empty($keywords) || !is_string($keywords))
        {
            return [];
        }

        // replace newlines with commas
        $keywords = str_replace("\r\n", ',', $keywords);

        // split keywords on commas
        $keywords = explode(',', $keywords);
        
        // trim entries
        $keywords = array_map(function($str)
        {
            return trim($str);
        }, $keywords);

        // filter out empty strings and return the resulting array
        return array_filter($keywords, function($str)
        {
            return !empty($str);
        });
    }
}
