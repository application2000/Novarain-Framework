<?php
/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2017 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

defined('_JEXEC') or die('Restricted access');

require_once JPATH_PLUGINS . '/system/nrframework/helpers/fieldlist.php';

class JFormFieldNROs extends NRFormFieldList
{
	/**
	 *  Browsers List
	 *
	 *  @var  array
	 */
	public $options = array(
        'linux'      => 'NR_LINUX',
        'mac'        => 'NR_MAC',
        'android'    => 'NR_ANDROID',
        'iphone'     => 'NR_IPHONE',
        'ipad'       => 'NR_IPAD',
        'windows'    => 'NR_WINDOWS',
        'blackberry' => 'NR_BLACKBERRY'
	);
	
	protected function getOptions()
	{
		asort($this->options);

		foreach ($this->options as $key => $option)
		{
			$options[] = JHTML::_('select.option', $key, JText::_($option));
		}

		return array_merge(parent::getOptions(), $options);
	}
}