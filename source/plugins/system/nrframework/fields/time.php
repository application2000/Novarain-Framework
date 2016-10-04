<?php
/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2016 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

// No direct access to this file
defined('_JEXEC') or die;

require_once dirname(__DIR__) . '/helpers/field.php';

class JFormFieldNR_Time extends NRFormField
{

	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	public $type = 'nr_time';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 */
	public function getInput()
	{

		// Setup properties
		$this->hint = $this->get('hint', '00:00');

		// Add styles and scripts to DOM
		$this->doc->addStyleSheet(JURI::root(true).'/plugins/system/nrframework/fields/assets/jquery-clockpicker.min.css');
		JHtml::_('jquery.framework');
		$this->doc->addScript(JURI::root(true) . '/plugins/system/nrframework/fields/assets/jquery-clockpicker.min.js');
		
		static $run;
		// Run once to initialize it
		if (!$run)
		{
			$this->doc->addScriptDeclaration('
				jQuery(function($) {
					$(".clockpicker").clockpicker({
						default: \'now\',
						placement: \'top\',
						align: \'left\',
						autoclose: true
					});
				});
        	');
			$run = true;
		}

		return '<div class="input-group clockpicker"><input name="' . $this->name . '" type="text" class="form-control" value="' . $this->value . '"></div>'; }
}