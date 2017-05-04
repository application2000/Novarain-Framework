<?php

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2017 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

defined('JPATH_PLATFORM') or die;

require_once JPATH_PLUGINS . '/system/nrframework/fields/assignmentselection.php';

class JFormFieldAssignmentSelection2 extends JFormFieldAssignmentSelection
{
    /**
     * Layout to render the form field
     *
     * @var  string
     */
    protected $renderLayout = 'well';

    /**
     * Override renderer include path
     *
     * @return  array
     */
    protected function getLayoutPaths()
    {
        return JPATH_PLUGINS . "/system/nrframework/layouts/";
    }

    /**
     * Method to get a control group with label and input.
     *
     * @param   array  $options  Options to be passed into the rendering of the field
     *
     * @return  string  A string containing the html for the control group
     *
     * @since   3.2
     */
    public function renderField($options = array())
    {
    	$options["class"] = "well-assign";
    	return parent::renderField($options);
    }

}
