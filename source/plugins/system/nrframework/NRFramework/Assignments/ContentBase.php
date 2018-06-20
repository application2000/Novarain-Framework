<?php

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2018 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

namespace NRFramework\Assignments;

defined('_JEXEC') or die;

use NRFramework\Assignment;

class ContentBase extends Assignment
{
	/**
	 *  Request information
	 * 
	 *  @var object
	 */
	protected $request = null;

	/**
	 *  Class constructor
	 *
	 *  @param  object  $assignment
	 *  @param  object  $factory
	 */
	public function __construct($assignment, $factory)
	{
		parent::__construct($assignment, $factory);

		$request = new \stdClass;
		$request->view   = $this->app->input->get("view");
		$request->option = $this->app->input->get("option");
		$request->id     = $this->app->input->get("id");
		$this->request = $request;
	}
}
