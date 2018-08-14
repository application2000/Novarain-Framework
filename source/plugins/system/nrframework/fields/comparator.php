<?php
/**
 * @author          Tassos.gr <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2018 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

defined('_JEXEC') or die('Restricted access');

JFormHelper::loadFieldClass('list');

class JFormFieldComparator extends JFormFieldList
{
    /**
	 * Method to get the field input markup for a generic list.
	 * Use the multiple attribute to enable multiselect.
	 *
	 * @return  string  The field input markup.
	 */
	protected function getInput()
	{
        $this->class = 'input-medium';
        $this->required = true;

        return parent::getInput();
    }

    protected function getLabel()
    {
        return 'Match';
    }

    protected function getOptions()
    {
        $options = [
            //''            => '- Select -',
            'is'          => 'Is',
            'isnot'       => 'Is Not',
            //'equals'      => 'Equals',
            //'notequal'    => 'Does Not Equal',
            //'contains'    => 'Contains',
            //'notcontains' => 'Does Not Contain',
            //'greater'     => 'Greater Than',
            //'less'        => 'Less Than'
        ];

        return $options;
    }
}
