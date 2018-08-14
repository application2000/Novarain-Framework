<?php

/**
 * @package         @pkg.name@
 * @version         @pkg.version@ @vUf@
 * 
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2018 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

defined('_JEXEC') or die('Restricted access');

JHtml::stylesheet('plg_system_nrframework/conditionbuilder.css', ['relative' => true, 'version' => 'auto']);
JHtml::script('plg_system_nrframework/helper.js', ['relative' => true, 'version' => 'auto']);
JHtml::script('plg_system_nrframework/conditionbuilder.js', ['relative' => true, 'version' => 'auto']);

extract($displayData);

use NRFramework\ConditionBuilder;

?> 

<div class="cb" data-control-group="<?php echo $id; ?>" data-max-index="<?php echo $maxIndex; ?>">
    <div class="cb-items">
        <?php foreach ($data as $groupKey => $groupConditions) { 
                $maxIndex_ = max(array_keys($groupConditions));
            ?>
            <div class="cb-group" data-key="<?php echo $groupKey ?>" data-max-index="<?php echo $maxIndex_; ?>">
                <?php
                    foreach ($groupConditions as $conditionKey => $condition)
                    { 
                        echo ConditionBuilder::add($id, $groupKey, $conditionKey, $condition); 
                    }
                ?>
            </div>
        <?php } ?>
    </div>
    <div class="text-center" style="margin-top:20px;">
        <a class="btn addCondition" href="#"><b><span class="icon-plus"></b> AND</a>
    </div>
</div>