<?php
/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2016 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

defined('_JEXEC') or die;

require_once dirname(__DIR__) . '/helpers/field.php';
require_once dirname(__DIR__) . '/helpers/html.php';

class JFormFieldNRGroupLevel extends NRFormField
{
	/**
	 * Output the HTML for the field
	 * Example of usage: <field name="field_name" type="nrgrouplevel" label="NR_SELECTION" show_all="0" size="300" use_names="0"/>
	 * @return string The HTML for the groupfield
	 */
	protected function getInput()
	{
		$this->params = $this->element->attributes();

		$size    = $this->get('size', 300);
		$show_all  = $this->get('show_all');
		$use_names = $this->get('use_names');

		$options = $this->getUserGroups($use_names);

		if ($show_all)
		{
			$option          = new stdClass;
			$option->value   = -1;
			$option->text    = '- ' . JText::_('JALL') . ' -';
			$option->disable = '';
			array_unshift($options, $option);
		}

		return NRHTML::treeselect($options, $this->name, $this->value, $this->id, $size);
	}

	/**
	 * A helper to get the list of user groups.
	 * Login from administrator\components\com_config\model\field\filters.php@getUserGroups
	 * @param boolen $useNames Whether to use the names or the IDs
	 * @return	array
	 */
	protected function getUserGroups($useNames = false)
	{
		$value = $useNames ? 'a.title' : 'a.id';

		// Get a database object.
		$db = JFactory::getDbo();

		// Get the user groups from the database.
		$query = $db->getQuery(true);
		$query->select($value . ' AS value, a.title AS text, COUNT(DISTINCT b.id) AS level');
		$query->from('#__usergroups AS a');
		$query->join('LEFT', '#__usergroups AS b on a.lft > b.lft AND a.rgt < b.rgt');
		$query->group('a.id');
		$query->order('a.lft ASC');
		$db->setQuery($query);
		$options = $db->loadObjectList();

		return $options;
	}
}