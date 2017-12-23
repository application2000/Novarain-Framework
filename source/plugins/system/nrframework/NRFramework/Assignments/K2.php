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

abstract class K2 extends Assignment
{
    /**
     * Gets K2 item's id using K2Model
     *
     * @return int|null
     */
    protected function getItemID()
    {
        $model = \JModelLegacy::getInstance('Item', 'K2Model');
        $item  = $model->getData();
        if (is_object($item) && $item->id)
		{
			return (int) $item->id;
        }

        return null;
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

        $id     = $this->getItemID();
        if (!$id)
        {
            return null;
        }
        $q      = $this->db->getQuery(true);

        $q->select($fields)
            ->from('#__k2_items as i')
            ->where('id = ' . $id);
        $this->db->setQuery($q);

        return $this->db->loadObject();
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
}
