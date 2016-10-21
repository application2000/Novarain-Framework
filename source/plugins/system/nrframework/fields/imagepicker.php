<?php

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2015 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

defined('JPATH_PLATFORM') or die;
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldImagePicker extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	public $type = 'ImagePicker';

	protected function getInput()
	{	
		$params = (array) $this->element->attributes();
		$params = new JRegistry($params["@attributes"]);

		$showlabels = $params->get("showlabels", "true");
		$hideselect = $params->get("hideselect", "true");
		
		$assetsDir = JURI::root(true)."/plugins/system/nrframework/fields/assets/";

		$doc = JFactory::getDocument();
        $doc->addScript($assetsDir.'image-picker.min.js');
        $doc->addStyleSheet($assetsDir.'image-picker.css');
        $doc->addScriptDeclaration('
			jQuery(function($) {
				obj = $("#'.$this->id.'");
				obj.imagepicker({
					show_label:  '.(string) $showlabels.',
					hide_select: '.(string) $hideselect.',
					initialized: function() {
						classes = obj.attr("class");
						obj.next().addClass(classes);
					}
				});
			});
        ');

        if ($hideselect == "true")
        {
	        $doc->addStyleDeclaration('
				#'.$this->id.'_chzn {
					display:none !important;
	    		}
	        ');      	
        }

		$input = parent::getInput();
		$input = str_replace('onclick="', 'data-img-src="'.JURI::root().'', $input);

		return $input;
	}
}
