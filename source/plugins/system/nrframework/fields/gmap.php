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

class JFormFieldNR_Gmap extends NRFormField
{

	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	public $type = 'nr_gmap';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 */
	public function getInput()
	{

		// Setup properties
		$this->width    = $this->get('width', '500px');
		$this->height   = $this->get('height', '400px');
		$this->zoom     = $this->get('zoom', '10');
		$this->margin   = $this->get('margin', '0 0 10px 0');
		$this->readonly = $this->get('readonly', false) ? 'readonly' : '';
		$this->value    = $this->checkCoordinates($this->value, null) ? $this->value : $this->get('default', '36.892587, 27.287793');
		$this->hint     = $this->prepareText($this->get('hint', 'NR_ENTER_COORDINATES'));

		// Add scripts to DOM
		JHtml::_('jquery.framework');
		Jtext::script('NR_WRONG_COORDINATES');

		$this->doc->addScript('//maps.googleapis.com/maps/api/js?key=AIzaSyDsY82z3zz1V81XLG0AnL_TbSXGxJ4n1cw','text/javascript', false, true);
		$this->doc->addScript(JURI::root(true) . '/plugins/system/nrframework/fields/assets/gmap.init.js','text/javascript',true,true);

		// Add styles to DOM
		$style = '#' . $this->id . '_map { '
			. 'height: ' . $this->height . ';'
			. 'width: ' .  $this->width . ';'
			. 'margin: ' . $this->margin . ';'
		. '}';

		$this->doc->addStyleDeclaration($style);

		return '<div id="' . $this->id . '_map"></div><input type="text" name="' . $this->name . '" class="' . $this->class . ' nr_gmap input-xlarge" id="' . $this->id . '" value="' . $this->value . '" placeholder="' . $this->hint . '" data-zoom="' . $this->zoom . '" ' . $this->readonly . '/>';
	}

	/**
	 * Checks the validity of the coordinates
	 */
	private function checkCoordinates($coordinates)
	{
		return (preg_match("/^[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?),\s*[-+]?(180(\.0+)?|((1[0-7]\d)|([1-9]?\d))(\.\d+)?)$/", $coordinates));
	}
}