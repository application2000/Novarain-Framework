<?php

/**
 *  @author          Tassos.gr <info@tassos.gr>
 *  @link            http://www.tassos.gr
 *  @copyright       Copyright © 2018 Tassos Marinos All Rights Reserved
 *  @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

 namespace NRFramework;

 defined('_JEXEC') or die;

 /**
  *  Factory
  *  
  *  Used to decouple the framework from it's dependencies and
  *  make unit testing easier.
  */

 class Factory
 {
     public function getDbo()
     {
        return \JFactory::getDbo();
     }

     public function getApplication()
     {
         return \JFactory::getApplication();
     }

     public function getDocument()
     {
        return \JFactory::getDocument();
     }

     public function getUser()
     {
        return \JFactory::getUser();
     }

     public function getCache()
     {
        return \NRFramework\CacheManager::getInstance(\JFactory::getCache('novarain', ''));
     }

     public function getDate($date = 'now', $tz = null)
     {
        return \JFactory::getDate($date, $tz);
     }

     public function getDateFromFormat($format, $date, $timezone)
     {
         return \JDate::createFromFormat($format, $date, $timezone);
     }

     public function getURL()
     {
         return \JURI::getInstance()->toString();
     }

     public function getLanguage()
     {
        return \JFactory::getLanguage();
     }

     public function getSession()
     {
        return \JFactory::getSession();
     }
 }
