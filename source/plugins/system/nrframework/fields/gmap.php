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

	public $custom = array();

	public function __construct()
	{
		parent::__construct();

		// this class has the following custom properties
		// which we will be checking if they are explicitly set
		// through the xml via the setCustomProperties method later
		$this->custom['width'] = '500px';

		$this->custom['height'] = '400px';

		$this->custom['zoom'] = '10';

		$this->custom['marginBottom'] = '10px';

		$this->custom['readonly'] = '';
		
		// these default coordinates represent the center of the map
		$this->custom['coordinates'] = array('19.189444', '-31.113281');
	}

	public function getInput()
	{
		$this->setCustomProperties();

		$hint = $this->translateHint ? JText::_($this->element['hint']) : $this->element['hint'];

		$mapDivHTML = '<div id="' . $this->id . '_map"></div>';

		JHtml::_('jquery.framework');

		$doc = JFactory::getDocument();
		Jtext::script('NR_WRONG_COORDINATES');
		$doc->addScript('//maps.googleapis.com/maps/api/js?key=AIzaSyDsY82z3zz1V81XLG0AnL_TbSXGxJ4n1cw', true, true);
		$doc->addScript(JURI::root(true) . '/plugins/system/nrframework/fields/assets/gmap.init.js');

		$style = '#' . $this->id . '_map {'
		. 'height: ' . $this->custom['height'] . ';'
		. 'width: ' . $this->custom['width'] . ';'
		. 'margin-bottom: ' . $this->custom['marginBottom'] . ';'
			. '}';

		$doc->addStyleDeclaration($style);
		return $mapDivHTML .
		'<input type="text" name="' . $this->name . '" class="' . $this->class . ' nr_gmap input-xlarge" id="' . $this->id . '" value="' . $this->value . '" placeholder="' . $hint . '" data-coordinates="' . $this->checkCoordinates() . '" data-zoom="' . $this->custom['zoom'] . '" ' . $this->custom['readonly'] . '/>';
	}

	/**
	 * Checks the validity of the coordinates given through the XML
	 */
	public function checkCoordinates()
	{
		$coordinates = implode(',', $this->custom['coordinates']);
		return (preg_match("/^[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?),\s*[-+]?(180(\.0+)?|((1[0-7]\d)|([1-9]?\d))(\.\d+)?)$/", $coordinates)) ? $coordinates : '19.189444,-31.113281';
	}

	/**
	 * Cycles through the attributes which were given via XML
	 * and when one of them matches this class' custom properties, set them.
	 */
	public function setCustomProperties()
	{
		// since $this->element is a SimpleXMLObject, we need its variables in an array to iterate through
		$elements = get_object_vars($this->element);

		foreach ($elements['@attributes'] as $key => $value)
		{
			if (array_key_exists($key, $this->custom))
			{
				$this->custom[$key] = (string) $value;
			}
		}
	}
}