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
use NRFramework\Assignments\K2;

class K2Tag extends K2
{
    /**
     *  Pass check for K2 Tags
     *
     *  @return bool
     */
    public function pass()
    {
        // Check we are on the right context and we have a valid Item ID
        if (empty($this->selection) || !$this->passContext() || !$this->getItemID())
        {
            return false;
        }

        return $this->passSimple($this->value(), $this->selection);
    }

    /**
     *  Returns the assignment's value
     * 
     *  @return array K2 item tags
     */
	public function value()
	{
		return $this->getK2tags($this->getItemID());
	}

    /**
     *  Return tags of a K2 item
     * 
     *  @param int $id K2 item ID
     * 
     *  @return array
     */
    public function getK2tags($id)
    {
        $q = $this->db->getQuery(true)
            ->select('t.id')
            ->from('#__k2_tags_xref AS tx')
            ->join('LEFT', '#__k2_tags AS t ON t.id = tx.tagID')
            ->where('tx.itemID = ' . $id)
            ->where('t.published = 1');

		$this->db->setQuery($q);
        return $this->db->loadColumn();
    }
}
