<?php

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2015 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

defined('_JEXEC') or die;

class nrFrameworkAssignmentsPHP 
{
	private $selection;

	function __construct($assignment) {
    	$this->selection = $assignment->selection;
   	}

	function passPHP()
	{

		if (!is_array($this->selection))
		{
			$this->selection = array($this->selection);
		}

		$pass = false;
		foreach ($this->selection as $php)
		{
			// replace \n with newline and other fix stuff
			$php = str_replace('\|', '|', $php);
			$php = preg_replace('#(?<!\\\)\\\n#', "\n", $php);
			$php = trim(str_replace('[:REGEX_ENTER:]', '\n', $php));

			if ($php == '')
			{
				$pass = true;
				break;
			}

			if (!isset($Itemid))
			{
				$Itemid = JFactory::getApplication()->input->getInt('Itemid', 0);
			}
			if (!isset($mainframe))
			{
				$mainframe = JFactory::getApplication();
			}
			if (!isset($app))
			{
				$app = JFactory::getApplication();
			}
			if (!isset($document))
			{
				$document = JFactory::getDocument();
			}
			if (!isset($doc))
			{
				$doc = JFactory::getDocument();
			}
			if (!isset($database))
			{
				$database = JFactory::getDBO();
			}
			if (!isset($db))
			{
				$db = JFactory::getDBO();
			}
			if (!isset($user))
			{
				$user = JFactory::getUser();
			}
			$php .= ';return true;';

			$temp_PHP_func = create_function('&$Itemid, &$mainframe, &$app, &$document, &$doc, &$database, &$db, &$user', $php);

			// evaluate the script
			ob_start();
			$pass = (bool) $temp_PHP_func($Itemid, $mainframe, $app, $document, $doc, $database, $db, $user);
			unset($temp_PHP_func);
			ob_end_clean();

			if ($pass)
			{
				break;
			}
		}

		return $pass;
	}
}
