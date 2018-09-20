<?php
/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2018 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

// No direct access to this file
defined('_JEXEC') or die;

JFormHelper::loadFieldClass('checkbox');

/**
 * Pure CSS iOS-like Toggle Button based on the Checkbox field.
 * 
 * This field also fixes the Unchecked checkbox value using a hidden field.
 * Credits: http://mistercameron.com/2008/01/unchecked-checkbox-values/
 */
class JFormFieldNRToggle extends JFormFieldCheckbox
{
	/**
	 * On state value
	 *
	 * @var int
	 */
	protected $on_value = 1;

	/**
	 * Off state value
	 *
	 * @var int
	 */
	protected $off_value = 0;

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string
	 */
	public function getInput()
	{
		JHtml::stylesheet('plg_system_nrframework/toggle.css', ['relative' => true, 'version' => 'auto']);

		$required = $this->required ? ' required aria-required="true"' : '';
		$checked  = $this->checked ? ' checked' : '';

		// Fix bug inherited from the Checkbox field where the input remains checked even if save it unchecked.
		if ($this->checked && (string) $this->value == (string) $this->off_value)
		{
			$checked = '';
		}

		return '
			<span class="nrtoggle">
				<input type="hidden" name="' . $this->name . '" value="' . $this->off_value . '">
				<input type="checkbox" name="' . $this->name . '" id="' . $this->id . '" value="'
				. htmlspecialchars($this->on_value, ENT_COMPAT, 'UTF-8') . '"' . $checked . $required . ' />
				<label for="' . $this->id . '"></label>
			</span>
		';
	}
}