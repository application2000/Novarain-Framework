<?php
/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2018 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

defined('_JEXEC') or die('Restricted access');

use NRFramework\ConditionBuilder;

JFormHelper::loadFieldClass('groupedlist');

class JFormFieldNRConditions extends JFormFieldGroupedList
{
	/**
	 * Method to get the field option groups.
	 *
	 * @return  array  The field option objects as a nested array in groups.
	 *
	 * @since   1.6
	 */
	protected function getGroups()
	{
		$groups[''][] = JHtml::_('select.option', '- Select Condition -', '');

		foreach (ConditionBuilder::$conditions as $conditionGroup => $conditions)
		{
			foreach ($conditions as $key => $condition)
			{
				$groups[$conditionGroup][] = JHtml::_('select.option', $key, $condition);
			}
		}

		// Merge any additional groups in the XML definition.
		return array_merge(parent::getGroups(), $groups);
	}
}