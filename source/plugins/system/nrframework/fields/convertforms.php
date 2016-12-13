<?php

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2016 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

// No direct access to this file
// 
defined('_JEXEC') or die;
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldConvertForms extends JFormFieldList
{
    /**
     * The field type.
     *
     * @var string
     */
    protected $type = 'convertforms';

    /**
     * Method to get a list of options for a list input.
     *
     * @return      array           An array of JHtml options.
     */
    protected function getOptions() 
    {
        $lists = $this->getList();

        if (!count($lists))
        {
            return;
        }

        $options = array();

        foreach ($lists as $option)
        {
            $options[] = JHTML::_('select.option', $option->id, $option->name);
        }

        $options = array_merge(parent::getOptions(), $options);
        return $options;
    }

    /**
     *  Retrieve all Convert Forms Campaigns
     *
     *  @return  array  Convert Forms Campaigns
     */
    private function getList()
    {
        $helper = JPATH_ADMINISTRATOR . "/components/com_convertforms/helpers/convertforms.php";

        if (!JFile::exists($helper))
        {
            return;
        }

        include_once $helper;

        if (!class_exists("ConvertFormsHelper"))
        {
            return;
        }

        return ConvertFormsHelper::getCampaigns();
    }
}