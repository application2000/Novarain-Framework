<?php

/**
 * @package         @pkg.name@
 * @version         @pkg.version@ @vUf@
 *
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2016 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

// No direct access
defined('_JEXEC') or die;

require_once __DIR__ . '/wrapper.php';

class NR_SendInBlue extends NR_Wrapper
{
	/**
	 * Create a new instance
	 * @param string $key Your SendInBLue API Key
	 * @throws \Exception
	 */
	public function __construct($key)
	{
		parent::__construct();
		$this->setKey($key);
		$this->setEndpoint('https://api.sendinblue.com/v2.0');
		$this->options->set('headers.api-key', $this->key);
	}
}
