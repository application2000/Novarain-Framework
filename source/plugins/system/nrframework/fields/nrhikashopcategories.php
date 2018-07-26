<?php
/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2018 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

defined('_JEXEC') or die;

require_once __DIR__ . '/treeselect.php';

class JFormFieldNRHikaShopCategories extends JFormFieldNRTreeSelect
{
	/**
	 * Get a list of all EventBooking Categories
	 *
	 * @return void
	 */
	protected function getOptions()
	{
		// Get a database object.
        $db = $this->db;
        
		$query = $db->getQuery(true)
			->select('a.category_id as value, a.category_name as text, COUNT(DISTINCT b.category_id) AS level, a.category_parent_id as parent, IF (a.category_published=1, 0, 1) as disable')
			->from('#__hikashop_category as a')
			->join('LEFT', '#__hikashop_category AS b on a.category_left > b.category_left AND a.category_right < b.category_right')
			->group('a.category_id, a.category_name, a.category_left')
			->order('a.category_left ASC');
			
		$db->setQuery($query);

		return $db->loadObjectList();
	}
}
