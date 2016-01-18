<?php

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2015 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
define('NR_FRAMEWORK_PATH', JPATH_PLUGINS . '/system/nrframework/');

if (JFactory::getApplication()->isAdmin())
{
    require_once NR_FRAMEWORK_PATH . 'helpers/functions.php';
    NRFrameworkFunctions::loadLanguage('plg_system_nrframework');
}

jimport('joomla.filesystem.file');

class plgSystemNRFramework extends JPlugin
{
    public function onAfterRoute()
    {
        // Include the Helper
        require_once NR_FRAMEWORK_PATH . 'helper.php';
    }

	public function onExtensionAfterSave($context, $table, $isNew)
	{
		if (
			JFactory::getApplication()->isSite()
			|| $context != 'com_plugins.plugin'
			|| $table->element != 'nrframework'
			|| !isset($table->params)
		)
		{
			return;
		}

		$params = json_decode($table->params);

		if (!isset($params->key)) 
		{
			return;
		}

		$this->updateDownloadKey($params->key);
	}

	function updateDownloadKey($key)
	{
		if (!isset($key))
		{
			return;
		}

		$key = trim($key);

		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->update('#__update_sites')
			->set($db->qn('extra_query') . ' = ' . $db->q('dlid=' . $key))
			->where($db->qn('location') . ' LIKE ' . $db->q('%tassos.gr%'));

		$db->setQuery($query);
		$db->execute();
	}
}
