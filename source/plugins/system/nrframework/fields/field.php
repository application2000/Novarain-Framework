<?php

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2016 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

defined('_JEXEC') or die;

class NRField extends JFormField
{
	public $type = 'Field';
	public $db = null;
	public $params = null;

	public function __construct($form = null)
	{
		$this->db = JFactory::getDbo();

	}

	protected function getInput()
	{
		return false;
	}
}
