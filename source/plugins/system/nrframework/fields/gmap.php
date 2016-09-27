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
	public $type = 'nr_gmap';

	public function getInput()
	{

		$hint = $this->translateHint ? JText::_($this->element['hint']) : $this->element['hint'];

		$mapDivHTML = '<div id="' . $this->id . '_map"></div>';

		JHtml::_('jquery.framework');

		$doc = JFactory::getDocument();

		$doc->addScript('//maps.googleapis.com/maps/api/js?key=AIzaSyDsY82z3zz1V81XLG0AnL_TbSXGxJ4n1cw', true, true);
		$doc->addScript(JURI::root(true) . '/plugins/system/nrframework/fields/assets/gmap.init.js');

		$style = '#' . $this->id . '_map {'
			. 'height: 400px;' .
			'width: 50%;' .
			'margin-bottom: 20px;' .
			'}';

		$doc->addStyleDeclaration($style);

		return $mapDivHTML . '<input type="text" name="' . $this->name . '" class="' . $this->class . ' nr_gmap" id="' . $this->id . '" value="' . $this->value . '" placeholder="' . $hint . '" readonly/>';
	}
}