<?php

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2015 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

defined('_JEXEC') or die;

class NRFormField extends JFormField
{
	public $type = 'Field';

	/**
	 *  Document object
	 *
	 *  @var  [type]
	 */
	public $doc;

	/**
	 *  Class constructor
	 */
	function __construct()
	{
		$this->doc = JFactory::getDocument();
		$this->doc->addStylesheet(JURI::root(true) . "/plugins/system/nrframework/assets/css/fields.css");
	}

	/**
	 * Method to get the field label markup.
	 *
	 * @return  string  The field label markup.
	 */
	protected function getLabel()
	{

		$label = $this->get("label");

		if (empty($label))
		{
			return "";
		}

		return parent::getLabel();
	}

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 */
	protected function getInput()
	{
		return false;
	}

	/**
	 *  Prepares string through JText
	 *
	 *  @param   string  $string
	 *
	 *  @return  string
	 */
	public function prepareText($string = '')
	{
		$string = trim($string);

		if ($string == '')
		{
			return '';
		}

		return JText::_($string);
	}

	public function get($val, $default = '')
	{
		return (isset($this->element[$val]) && (string) $this->element[$val] != '') ? (string) $this->element[$val] : $default;
	}

}
