<?php

require_once realpath(__DIR__ . '/../../unit-tests/bootstrap-joomla.php');
JLoader::registerNamespace('NRFramework', realpath(__DIR__ . '/../source/plugins/system/nrframework'));

jimport( 'joomla.filesystem.file' );
// require custom TestCase classes
