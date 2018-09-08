<?php
/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2018 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

defined('_JEXEC') or die('Restricted access');

use NRFramework\Extension;

JFormHelper::loadFieldClass('groupedlist');

class JFormFieldNRConditions extends JFormFieldGroupedList
{
	/**
     * List of available conditions
     *
     * @var array
     */
    public static $conditions = [
		'Datetime' => [
			'date'    => 'Date',
			'weekday' => 'Day of Week',
			'month'   => 'Month',
			'time'    => 'Time',
		],
		'Joomla' => [
			'userid'    => 'User ID',
			'usergroup' => 'User Group',
			'menu'      => 'Menu',
			'component' => 'Component',
			'language'  => 'Language'
		],
		'Integrations' => [
			'com_content\article' => 'Joomla! Articles',
			'com_content\category' => 'Joomla! Categories',
			'com_k2\k2item' => 'K2 Item',
			'com_k2\k2category' => 'K2 Category',
			'com_k2\k2tag' => 'K2 Tags',
			'com_acymailing\acymailing' => 'AcyMailing List',
			'com_convertforms\convertforms'=> 'Convert Forms Campaign',
			'com_akeebasubs\akeebasubs' => 'AkeebaSubs Level',
		],
		'Geolocation' => [
			'country'   => 'Country',
			'city'      => 'City',
			'region'    => 'Region',
			'continent' => 'Continent',
		],
		'Advanced' => [
			'url'       => 'URL',
			'device'    => 'Device',
			'ip'        => 'IP Address',
			'os'        => 'Operating System',
			'browser'   => 'Browser',
			'referrer'  => 'Referrer',
			'pageviews' => 'Page Views',
			'cookie'    => 'Cookie',
			'php' 		=> 'PHP'
		]
	];

	/**
	 * Method to get the field option groups.
	 *
	 * @return  array  The field option objects as a nested array in groups.
	 *
	 * @since   1.6
	 */
	protected function getGroups()
	{
 		$conditions_list = is_null($this->element['conditions_list']) ? null : explode(',', $this->element['conditions_list']);
 
		$groups[''][] = JHtml::_('select.option', '- Select Condition -', '');

		foreach (self::$conditions as $conditionGroup => $conditions)
		{
			foreach ($conditions as $conditionName => $condition)
			{
				// Should check every Integration-based condition, if the respective component is installed and enabled
				if ($conditionGroup == 'Integrations')
				{
					$conditionNameParts = explode('\\', $conditionName);

					if (!Extension::isEnabled($conditionNameParts[0]))
					{
						continue;
					}

					$conditionName = $conditionNameParts[1];
				}

				// In case this condition is not available in the conditions list passed by the component, disable it.
 				$disabled = false;

				if (!empty($conditions_list) && !in_array($conditionName, $conditions_list))
				{
					$disabled = true;
				}

				$groups[$conditionGroup][] = JHtml::_('select.option', $conditionName, $condition, 'value', 'text', $disabled);
			}
		}

		// Merge any additional groups in the XML definition.
		return array_merge(parent::getGroups(), $groups);
	}
}