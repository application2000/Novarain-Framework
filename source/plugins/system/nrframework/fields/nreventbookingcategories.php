<?php
/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2018 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

defined('_JEXEC') or die;

class JFormFieldNREventBookingCategories extends JFormField
{
	/**
	 * Output the HTML for the field
	 */
	protected function getInput()
	{
		return \NRFramework\HTML::treeselect($this->getCategories(), $this->name, $this->value, $this->id, $size);
	}

	/**
	 * Get a list of all EventBooking Categories
	 *
	 * @return void
	 */
	public function getCategories()
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select('id as value, name as text, level, IF (published=1, 0, 1) as disable')
			->from('#__eb_categories');
			
		$db->setQuery($query);

		return $db->loadObjectList();
	}
}
