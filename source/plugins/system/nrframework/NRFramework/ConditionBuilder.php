<?php

/**
 *  @author          Tassos.gr <info@tassos.gr>
 *  @link            http://www.tassos.gr
 *  @copyright       Copyright © 2018 Tassos Marinos All Rights Reserved
 *  @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace NRFramework;

defined('_JEXEC') or die;

class ConditionBuilder
{    
    /**
     * List of available conditions
     *
     * @var array
     */
    public static $conditions = [
		'Datetime' => [
			'date'  => 'Date',
			'day'   => 'Day of Week',
			'month' => 'Month',
			'time'  => 'Time',
		],
		'Joomla' => [
			'url'       => 'URL',
			'userid'    => 'User ID',
			'usergroup' => 'User Group',
			'menu'      => 'Menu',
			'component' => 'Component',
			'language'  => 'Language'
		],
		'Integrations' => [
			'article'      => 'Joomla! Articles',
			'category'     => 'Joomla! Categories',
			'k2item'       => 'K2 Item',
			'k2category'   => 'K2 Category',
			'k2tag'        => 'K2 Tags',
			'acymailing'   => 'AcyMailing List',
			'convertforms' => 'Convert Forms Campaign',
			'akeebasubs'   => 'AkeebaSubs Level',
		],
		'Visitor' => [
			'country'   => 'Country',
			'city'      => 'City',
			'region'    => 'Region',
			'continent' => 'Continent',
			'device'    => 'Device',
			'ip'        => 'IP Address',
			'os'        => 'Operating System',
			'browser'   => 'Browser',
			'referrer'  => 'Referrer',
			'pageviews' => 'Page Views',
			'cookie'    => 'Cookie'
		],
		'Other' => [
			'php' => 'PHP'
		]
	];

    public static function render($id, $loadData = array())
    {
        // Initialize a new empty condition
        if (empty($loadData))
        {
            $loadData = [0 => ['']];
        } else 
        {
            // Fix indexes
            $loadData = array_values($loadData);
        }

        $options = [
            'id'       => $id,
            'data'     => $loadData,
            'maxIndex' => count($loadData) - 1
        ];

        $layout = self::getLayout('conditionbuilder', $options);
        return $layout;
    }

    public static function add($controlGroup, $groupKey, $conditionKey, $condition = null)
    {
        $controlGroup_ = $controlGroup . "[$groupKey][$conditionKey]";
        $form = self::getForm('/conditionbuilder/base.xml', $controlGroup_, $condition);

        $options = [
            'toolbar'      => $form,
            'conditionKey' => $conditionKey,
            'options'      => ''
        ];

        if (isset($condition['name']))
        {
            $optionsHTML = self::renderOptions($condition['name'], $controlGroup_, $condition);
            $options['options'] = $optionsHTML;
        }

		$layout = self::getLayout('conditionbuilder_row', $options);
        return $layout;
    }

    public static function renderOptions($name, $controlGroup = null, $formData = null)
    {
        $form = self::getForm('/conditions/' . $name . '.xml', $controlGroup, $formData);

        // Make sure value attribute is required
        // $form->setFieldAttribute('value', 'required', true);

        //var_dump($form->getFieldsets()['general']->class);

        return $form->renderFieldset('general');
    }

    private static function getLayout($name, $data)
    {
        $layout = new \JLayoutFile($name, JPATH_PLUGINS . '/system/nrframework/layouts');
        return $layout->render($data);
    }

    private static function getForm($name, $controlGroup, $data = null)
    {
        $form = new \JForm('cb', ['control' => $controlGroup]);

        $form->addFieldPath(JPATH_PLUGINS . '/system/nrframework/fields');
        $form->loadFile(JPATH_PLUGINS . '/system/nrframework/xml/' . $name);

        if (!is_null($data))
        {
            $form->bind($data);
        }

        return $form;
    }
}