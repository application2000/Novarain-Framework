<?php

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2017 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace NRFramework\Helpers\Assignments;

defined('_JEXEC') or die;

use NRFramework\Helpers\Assignment as NRAssignment;

class ConvertForms extends NRAssignment
{
	/**
	 *  Checks if user has subscribed to certain Convert Forms campaigns
	 *
	 *  @return  bool
	 */
	function passConvertForms()
	{
    	return $this->passSimple($this->getCampaigns(), $this->selection);
	}

    /**
     *  Returns campaigns list visitor is subscribed to
     *  If the user is logged in, we try to get the campaigns by user's ID
     *  Otherwise, the visitor cookie ID will be used instead
     *
     *  @return  array  List of campaign IDs
     */
	private function getCampaigns()
	{
		if (!@include_once(JPATH_ADMINISTRATOR . '/components/com_convertforms/helpers/convertforms.php'))
		{
			return false;
		}

		return \ConvertFormsHelper::getVisitorCampaigns();
	}

}
