<?php

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2015 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

defined('JPATH_PLATFORM') or die;

jimport('joomla.form.helper');

class JFormFieldAssignmentSelection extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	public $type = 'AssignmentSelection';

	protected function getLabel()
	{
		return '';
	}

	protected function getInput()
	{	

		$assetsDir = JURI::root(true)."/plugins/system/nrframework/fields/assets/";
		$doc = JFactory::getDocument();
        $doc->addScript($assetsDir.'assignmentselection.js');
        $doc->addStyleSheet($assetsDir.'assignmentselection.css');

		$label = JText::_($this->element["label"]);
		$this->value = (int) $this->value;
		$html = array();

		// Start the radio field output.
		$html[] = '<div class="assignmentselection">';
		$html[] = '<div class="control-group">';
		$html[] = '<div class="control-label"><label><strong>' . $label . '</strong></label></div>';
		$html[] = '<div class="controls">';
		$html[] = '<fieldset id="' . $this->id . '"  class="radio btn-group">';
		$html[] = '<input type="radio" id="' . $this->id . '0" name="' . $this->name . '" value="0"' . ((!$this->value) ? ' checked="checked"' : '').'/>';
		$html[] = '<label class="btn_ignore" for="' . $this->id . '0">' . JText::_('NR_IGNORE') . '</label>';
		$html[] = '<input type="radio" id="' . $this->id . '1" name="' . $this->name . '" value="1"' . (($this->value === 1) ? ' checked="checked"' : '').'/>';
		$html[] = '<label class="btn_include" for="' . $this->id . '1">' . JText::_('NR_INCLUDE') . '</label>';
		$html[] = '<input type="radio" id="' . $this->id . '2" name="' . $this->name . '" value="2"' . (($this->value === 2) ? ' checked="checked"' : '').'/>';
		$html[] = '<label class="btn_exclude" for="' . $this->id . '2">' . JText::_('NR_EXCLUDE') . '</label>';
		$html[] = '</fieldset>';
		$html[] = '</div></div></div>';

		return implode($html);
	}
}
