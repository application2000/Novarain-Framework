<?php

/**
 *  @author          Tassos.gr <info@tassos.gr>
 *  @link            http://www.tassos.gr
 *  @copyright       Copyright © 2018 Tassos Marinos All Rights Reserved
 *  @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

 namespace NRFramework;

 defined('_JEXEC') or die;

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

     }

     public function getDate()
     {
        return \JFactory::getDate();
     }
 }
