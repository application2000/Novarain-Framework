<?php

/**
 * @author          Tassos.gr
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2017 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace NRFramework\Assignments;

defined('_JEXEC') or die;

use NRFramework\Assignment;

abstract class K2 extends Assignment
{

    /**
     * K2 model object
     *
     * @var object
     */
    protected $model;

    /**
	 *  Class constructor
	 *
	 *  @param  object  $assignment
	 */
	public function __construct($assignment)
	{
		parent::__construct($assignment);
		$this->model = \JModelLegacy::getInstance('Item', 'K2Model');
    }
    
    /**
     *  Gets K2 item's id using K2Model
     *
     *  @return int|null
     */
    protected function getItemID()
    {
        $item  = $this->getK2Item();
        if (is_object($item) && isset($item->id))
		{
			return (int) $item->id;
        }

        return null;
    }

    /**
     *  Returns a K2 item
     *
     *  @return object|null
     */
    protected function getK2Item()
    {
        return $this->model->getData();
    }   
    
    /**
     *  Check if we are in correct context
     *
     *  @return bool
     */
    protected function passContext()
    {
        if ($this->request->option != 'com_k2')
		{
			return false;
        }
        
        return true;
    }    
}
