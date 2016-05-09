<?php

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2015 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

defined('_JEXEC') or die;

class PlgSystemNRFrameworkInstallerScriptHelper
{
	public $name = '';
	public $alias = '';
	public $extname = '';
	public $extension_type = '';
	public $plugin_folder = 'system';
	public $module_position = 'status';
	public $client_id = 1;
	public $install_type = 'install';
	public $show_message = true;
	public $db = null;

	public function __construct(&$params)
	{
		$this->extname = $this->extname ?: $this->alias;
		$this->db = JFactory::getDbo();
	}

	public function preflight($route, JAdapterInstance $adapter)
	{
		if (!in_array($route, array('install', 'update')))
		{
			return;
		}

		JFactory::getLanguage()->load('plg_system_novaraininstaller', JPATH_PLUGINS . '/system/novaraininstaller');

		if ($this->show_message && $this->isInstalled())
		{
			$this->install_type = 'update';
		}

		if ($this->onBeforeInstall() === false)
		{
			return false;
		}
	}

	public function postflight($route, JAdapterInstance $adapter)
	{
		$this->removeGlobalLanguageFiles();
		$this->removeUnusedLanguageFiles();

		JFactory::getLanguage()->load($this->getPrefix() . '_' . $this->extname, $this->getMainFolder());

		if (!in_array($route, array('install', 'update')))
		{
			return;
		}

		$this->updateDownloadKey();

		if ($this->onAfterInstall() === false)
		{
			return false;
		}

		if ($route == 'install')
		{
			$this->publishExtension();
		}

		if ($this->show_message)
		{
			$this->addInstalledMessage();
		}
	}

	public function isInstalled()
	{
		if (!is_file($this->getInstalledXMLFile()))
		{
			return false;
		}

		$query = $this->db->getQuery(true)
			->select('extension_id')
			->from('#__extensions')
			->where($this->db->quoteName('type') . ' = ' . $this->db->quote($this->extension_type))
			->where($this->db->quoteName('element') . ' = ' . $this->db->quote($this->getElementName()));
		$this->db->setQuery($query, 0, 1);
		$result = $this->db->loadResult();

		return empty($result) ? false : true;
	}

	public function getMainFolder()
	{
		switch ($this->extension_type)
		{
			case 'plugin' :
				return JPATH_SITE . '/plugins/' . $this->plugin_folder . '/' . $this->extname;

			case 'component' :
				return JPATH_ADMINISTRATOR . '/components/com_' . $this->extname;

			case 'module' :
				return JPATH_ADMINISTRATOR . '/modules/mod_' . $this->extname;

			case 'library' :
				return JPATH_SITE . '/libraries/' . $this->extname;
		}
	}

	public function getInstalledXMLFile()
	{
		return $this->getXMLFile($this->getMainFolder());
	}

	public function getCurrentXMLFile()
	{
		return $this->getXMLFile(__DIR__);
	}

	public function getXMLFile($folder)
	{
		switch ($this->extension_type)
		{
			case 'module' :
				return $folder . '/mod_' . $this->extname . '.xml';

			default :
				return $folder . '/' . $this->extname . '.xml';
		}
	}

	public function foldersExist($folders = array())
	{
		foreach ($folders as $folder)
		{
			if (is_dir($folder))
			{
				return true;
			}
		}

		return false;
	}

	public function publishExtension()
	{
		switch ($this->extension_type)
		{
			case 'plugin' :
				$this->publishPlugin();

			case 'module' :
				$this->publishModule();
		}
	}

	public function publishPlugin()
	{
		$query = $this->db->getQuery(true)
			->update('#__extensions')
			->set($this->db->quoteName('enabled') . ' = 1')
			->where($this->db->quoteName('type') . ' = ' . $this->db->quote('plugin'))
			->where($this->db->quoteName('element') . ' = ' . $this->db->quote($this->extname))
			->where($this->db->quoteName('folder') . ' = ' . $this->db->quote($this->plugin_folder));
		$this->db->setQuery($query);
		$this->db->execute();
	}

	public function publishModule()
	{
		// Get module id
		$query = $this->db->getQuery(true)
			->select('id')
			->from('#__modules')
			->where($this->db->quoteName('module') . ' = ' . $this->db->quote('mod_' . $this->extname))
			->where($this->db->quoteName('client_id') . ' = ' . (int) $this->client_id);
		$this->db->setQuery($query, 0, 1);
		$id = $this->db->loadResult();

		if (!$id)
		{
			return;
		}

		// check if module is already in the modules_menu table (meaning is is already saved)
		$query->clear()
			->select('moduleid')
			->from('#__modules_menu')
			->where($this->db->quoteName('moduleid') . ' = ' . (int) $id);
		$this->db->setQuery($query, 0, 1);
		$exists = $this->db->loadResult();

		if ($exists)
		{
			return;
		}

		// Get highest ordering number in position
		$query->clear()
			->select('ordering')
			->from('#__modules')
			->where($this->db->quoteName('position') . ' = ' . $this->db->quote($this->module_position))
			->where($this->db->quoteName('client_id') . ' = ' . (int) $this->client_id)
			->order('ordering DESC');
		$this->db->setQuery($query, 0, 1);
		$ordering = $this->db->loadResult();
		$ordering++;

		// publish module and set ordering number
		$query->clear()
			->update('#__modules')
			->set($this->db->quoteName('published') . ' = 1')
			->set($this->db->quoteName('ordering') . ' = ' . (int) $ordering)
			->set($this->db->quoteName('position') . ' = ' . $this->db->quote($this->module_position))
			->where($this->db->quoteName('id') . ' = ' . (int) $id);
		$this->db->setQuery($query);
		$this->db->execute();

		// add module to the modules_menu table
		$query->clear()
			->insert('#__modules_menu')
			->columns(array($this->db->quoteName('moduleid'), $this->db->quoteName('menuid')))
			->values((int) $id . ', 0');
		$this->db->setQuery($query);
		$this->db->execute();
	}

	public function addInstalledMessage()
	{
		JFactory::getApplication()->enqueueMessage(
			JText::sprintf(
				JText::_($this->install_type == 'update' ? 'NRI_THE_EXTENSION_HAS_BEEN_UPDATED_SUCCESSFULLY' : 'NRI_THE_EXTENSION_HAS_BEEN_INSTALLED_SUCCESSFULLY'),
				'<strong>' . JText::_($this->name) . '</strong>',
				'<strong>' . $this->getVersion() . '</strong>',
				$this->getFullType()
			)
		);
	}

	public function getPrefix()
	{
		switch ($this->extension_type)
		{
			case 'plugin';
				return JText::_('plg_' . strtolower($this->plugin_folder));

			case 'component':
				return JText::_('com');

			case 'module':
				return JText::_('mod');

			case 'library':
				return JText::_('lib');

			default:
				return $this->extension_type;
		}
	}

	public function getElementName($type = null, $extname = null)
	{
		$type = is_null($type) ? $this->extension_type : $type;
		$extname = is_null($extname) ? $this->extname : $extname;

		switch ($type)
		{
			case 'component' :
				return 'com_' . $extname;

			case 'module' :
				return 'mod_' . $extname;

			case 'plugin' :
			default:
				return $extname;
		}
	}

	public function getFullType()
	{
		return JText::_('NRI_' . strtoupper($this->getPrefix()));
	}

	public function getVersion($file = '')
	{
		$file = $file ?: $this->getCurrentXMLFile();

		if (!is_file($file))
		{
			return '';
		}

		$xml = JApplicationHelper::parseXMLInstallFile($file);

		if (!$xml || !isset($xml['version']))
		{
			return '';
		}

		return $xml['version'];
	}

	public function isNewer()
	{
		if (!$installed_version = $this->getVersion($this->getInstalledXMLFile()))
		{
			return true;
		}

		$package_version = $this->getVersion();

		return version_compare($installed_version, $package_version, '<=');
	}

	public function onBeforeInstall()
	{
	}

	public function onAfterInstall()
	{
	}

	public function deleteFolders($folders = array())
	{
		foreach ($folders as $folder)
		{
			if (!is_dir($folder))
			{
				continue;
			}

			JFolder::delete($folder);
		}
	}

	// Save the download key from the Novarain Extension Manager config to the update sites
	private function updateDownloadKey()
	{

		// Disable for now
		return false;

		$query = $this->db->getQuery(true)
			->select('e.params')
			->from('#__extensions as e')
			->where('e.element = ' . $this->db->quote('com_novarainmanager'));
		$this->db->setQuery($query);
		$params = $this->db->loadResult();

		if (!$params)
		{
			return;
		}

		$params = json_decode($params);

		if (!isset($params->key))
		{
			return;
		}

		$query->clear()
			->update('#__update_sites')
			->set($this->db->qn('extra_query') . ' = ' . $this->db->q(''))
			->where($this->db->qn('location') . ' LIKE ' . $this->db->q('http://download.novarain.com%'));
		$this->db->setQuery($query);
		$this->db->execute();

		$query->clear()
			->update('#__update_sites')
			->set($this->db->qn('extra_query') . ' = ' . $this->db->q('k=' . $params->key))
			->where($this->db->qn('location') . ' LIKE ' . $this->db->q('http://download.novarain.com%'))
			->where($this->db->qn('location') . ' LIKE ' . $this->db->q('%&pro=1%'));
		$this->db->setQuery($query);
		$this->db->execute();
	}

	private function removeGlobalLanguageFiles()
	{
		if ($this->extension_type == 'library')
		{
			return;
		}

		$language_files = JFolder::files(JPATH_ADMINISTRATOR . '/language', '\.' . $this->getPrefix() . '_' . $this->extname . '\.', true, true);

		// Remove override files
		foreach ($language_files as $i => $language_file)
		{
			if (strpos($language_file, '/overrides/') === false)
			{
				continue;
			}

			unset($language_files[$i]);
		}

		if (empty($language_files))
		{
			return;
		}

		JFile::delete($language_files);
	}

	private function removeUnusedLanguageFiles()
	{
		if ($this->extension_type == 'library')
		{
			return;
		}

		$installed_languages = array_merge(
			JFolder::folders(JPATH_SITE . '/language'),
			JFolder::folders(JPATH_ADMINISTRATOR . '/language')
		);

		$languages = array_diff(
			JFolder::folders(__DIR__ . '/language'),
			$installed_languages
		);

		$delete_languages = array();

		foreach ($languages as $language)
		{
			$delete_languages[] = $this->getMainFolder() . '/language/' . $language;
		}

		if (empty($delete_languages))
		{
			return;
		}

		// Remove folders
		$this->deleteFolders($delete_languages);
	}
}
