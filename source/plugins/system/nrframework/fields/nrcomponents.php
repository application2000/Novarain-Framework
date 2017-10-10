<?php
/**
 * @author          Tassos.gr <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2017 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

defined('_JEXEC') or die('Restricted access');

require_once JPATH_PLUGINS . '/system/nrframework/helpers/fieldlist.php';

class JFormFieldNRComponents extends NRFormFieldList
{
    protected function getOptions()
    {
        $component_list = $this->getInstalledComponents();
        $options = array_merge(parent::getOptions(), $component_list);
        return $options;
    }

    /**
     * Creates a list of installed components
     * @return array
     */

    protected function getInstalledComponents()
    {
        $db    = $this->db;
        $query = $db->getQuery(true)
            ->select($db->quoteName(array('name', 'manifest_cache', 'type')))
            ->from($db->quoteName('#__extensions'))
            ->where($db->quoteName('type') . ' = ' . $db->quote('component'));
        
        $db->setQuery($query);

        try
		{
            $results = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			JError::raiseWarning(500, $e->getMessage());
			return false;
        }
        
        foreach ($results as $res)
        {
            // Todo: Use a more user-friendly name for the option's key
            $components[] = JHTML::_('select.option', $res->name, $res->name);
        }

        return $components;
    }
}
