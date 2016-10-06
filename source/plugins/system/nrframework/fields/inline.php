<?php

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2015 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

// No direct access to this file
defined('_JEXEC') or die;

require_once dirname(__DIR__) . '/helpers/field.php';

class JFormFieldNR_Inline extends NRFormField
{
	/**
	 * The field type.
	 *
	 * @var         string
	 */
	public $type = 'nr_inline';

	protected function getLabel()
	{
		return '';
	}

	/**
	 *  Method to render the input field
	 *
	 *  @return  string
	 */
	protected function getInput()
	{

		JFactory::getDocument()->addStylesheet(JURI::root(true) . "/plugins/system/nrframework/assets/css/fields.css");

		$title     = $this->get('label');
		$class     = $this->get('class');
		$start     = $this->get('start', 0);
		$end       = $this->get('end', 0);

		$html = array();

		if ($start || !$end)
		{
			$html[] = '</div>';
			$html[] = '<div class="inline-control-group' . $class . '">';
			$html[] = '<div><div>';
		}

		if (!$start && !$end)
		{
			$html[] = '</div>';
		}

		return '</div>' . implode('', $html);
	}
}