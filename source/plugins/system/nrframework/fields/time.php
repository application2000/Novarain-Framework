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
		$this->hint      = $this->get('hint', '00:00');
		$this->class     = $this->get('class', "input-mini");
		$this->placement = $this->get('placement', "top");
		$this->align     = $this->get('align', "left");
		$this->autoclose = $this->get('autoclose', "true");
		$this->default   = $this->get('default', "now");
		$this->donetext  = $this->get('donetext', "Done");

		// Add styles and scripts to DOM
		JHtml::_('jquery.framework');
		$this->doc->addStyleSheet(JURI::root(true).'/plugins/system/nrframework/fields/assets/jquery-clockpicker.min.css');
		$this->doc->addScript(JURI::root(true) . '/plugins/system/nrframework/fields/assets/jquery-clockpicker.min.js');
		
		static $run;
		// Run once to initialize it
		if (!$run)
		{
			$this->doc->addScriptDeclaration('
				jQuery(function($) {
					$(".clockpicker").clockpicker();
				});
        	');
			$run = true;
		}

		return '<div class="input-group input-append clockpicker" data-donetext="'.$this->donetext.'" data-default="'.$this->default.'" data-placement="'.$this->placement.'" data-align="'.$this->align.'" data-autoclose="'.$this->autoclose.'"><input class="'.$this->class.'" placeholder="'.$this->hint.'" name="' . $this->name . '" type="text" class="form-control" value="' . $this->value . '"><span class="add-on"><span class="icon-clock">&nbsp;</span></span></div>';
	}
}