<?php
/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2018 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

use \NRFramework\HTML;

defined('_JEXEC') or die;

abstract class JFormFieldNRTreeSelect extends JFormField
{
	/**
	 * Database object
	 *
	 * @var object
	 */
	public $db;

	/**
	 * Indicates whether the options array should be sorted before render.
	 *
	 * @var boolean
	 */
	protected $sortTree = false;

	/**
	 * Output the HTML for the field
	 */
	protected function getInput()
	{
		$this->db = JFactory::getDbo();

		$options = $this->getOptions();

		if ($this->sortTree)
		{
			$options = $this->sortTreeSelectOptions($options);
		}

		return HTML::treeselect($options, $this->name, $this->value, $this->id);
	}

	/**
     *  Sorts treeselect options
     * 
     *  @param  array $options
     *  @param  int   $parent_id
     * 
     *  @return array
     */
    protected function sortTreeSelectOptions($options, $parent_id = 0)
    {
        if (empty($options))
        {
            return [];
        }

        $result = [];

        $sub_options = array_filter($options, function($option) use($parent_id)
        {
            return $option->parent == $parent_id;
        });

        foreach ($sub_options as $option)
        {
            $result[] = $option;
            $result = array_merge($result, $this->sortTreeSelectOptions($options, $option->value));
        }

        return $result;
    }

	/**
	 * Get tree options as an Array of objects
	 * Each object should have the attributes: value, text, parent, level, disable
	 *
	 * @return object
	 */
	abstract protected function getOptions();
}
