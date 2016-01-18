<?php 

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2015 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

defined('_JEXEC') or die;

class NRFrameworkFunctions {

    public static function isFeed()
    {
        return (
            JFactory::getDocument()->getType() == 'feed'
            || JFactory::getApplication()->input->getWord('format') == 'feed'
            || JFactory::getApplication()->input->getWord('type') == 'rss'
            || JFactory::getApplication()->input->getWord('type') == 'atom'
        );
    }

    public static function loadLanguage($extension = 'plg_system_nrframework', $basePath = '')
    {
        if ($basePath && JFactory::getLanguage()->load($extension, $basePath))
        {
            return true;
        }

        $basePath = self::getExtensionPath($extension, $basePath, 'language');

        return JFactory::getLanguage()->load($extension, $basePath);
    }


    public static function getExtensionPath($extension = 'plg_system_nrframework', $basePath = JPATH_ADMINISTRATOR, $check_folder = '')
    {
        if (!in_array($basePath, array('', JPATH_ADMINISTRATOR, JPATH_SITE)))
        {
            return $basePath;
        }

        switch (true)
        {
            case (strpos($extension, 'com_') === 0):
                $path = 'components/' . $extension;
                break;

            case (strpos($extension, 'mod_') === 0):
                $path = 'modules/' . $extension;
                break;

            case (strpos($extension, 'plg_system_') === 0):
                $path = 'plugins/system/' . substr($extension, strlen('plg_system_'));
                break;

            case (strpos($extension, 'plg_editors-xtd_') === 0):
                $path = 'plugins/editors-xtd/' . substr($extension, strlen('plg_editors-xtd_'));
                break;
        }

        $check_folder = $check_folder ? '/' . $check_folder : '';

        if (is_dir($basePath . '/' . $path . $check_folder))
        {
            return $basePath . '/' . $path;
        }

        if (is_dir(JPATH_ADMINISTRATOR . '/' . $path . $check_folder))
        {
            return JPATH_ADMINISTRATOR . '/' . $path;
        }

        if (is_dir(JPATH_SITE . '/' . $path . $check_folder))
        {
            return JPATH_SITE . '/' . $path;
        }

        return $basePath;
    }

    public static function loadModule($id, $moduleStyle = null)
    {  
        // Return if no module id passed
        if (!$id) 
        {
            return;
        }

        // Fetch module from db
        $db = JFactory::getDBO();
        $query = $db->getQuery(true)
            ->select('*')
            ->from('#__modules')
            ->where('id='.$db->q($id)); 

        $db->setQuery($query);

        // Return if no modules found
        if (!$module = $db->loadObject()) 
        {
            return;
        }

        // Success! Return module's html
        return JModuleHelper::renderModule($module, $moduleStyle);
    }

    public static function fixDate(&$date)
    {
        if (!$date)
        {
            $date = null;

            return;
        }

        $date = trim($date);

        // Check if date has correct syntax: 00-00-00 00:00:00
        if (preg_match('#^[0-9]+-[0-9]+-[0-9]+( [0-9][0-9]:[0-9][0-9]:[0-9][0-9])?$#', $date))
        {
            return;
        }

        // Check if date has syntax: 00-00-00 00:00
        // If so, add :00 (seconds)
        if (preg_match('#^[0-9]+-[0-9]+-[0-9]+ [0-9][0-9]:[0-9][0-9]$#', $date))
        {
            $date .= ':00';

            return;
        }

        // Check if date has a prepending date syntax: 00-00-00 ...
        // If so, add :00 (seconds)
        if (preg_match('#^([0-9]+-[0-9]+-[0-9]+)#', $date, $match))
        {
            $date = $match['1'] . ' 00:00:00';

            return;
        }

        // Date format is not correct, so return null
        $date = null;
    }

    public static function fixDateOffset(&$date)
    {
        if ($date <= 0)
        {
            $date = 0;

            return;
        }

        $date = JFactory::getDate($date, JFactory::getUser()->getParam('timezone', JFactory::getConfig()->get('offset')));
        $date->setTimezone(new DateTimeZone('UTC'));

        $date = $date->format('Y-m-d H:i:s', true, false);
    }

    // Text
    public static function clean($string) 
    {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    }

    public static function dateTimeNow() 
    {
        return JFactory::getDate()->format("Y-m-d H:i:s");
    }

}

?>