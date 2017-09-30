<?php

/**
 * @author          Tassos.gr
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2017 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * 
 */


// Assignment related class aliases
JLoader::registerAlias('NRAssignment',                        '\\NRFramework\\Assignment');
JLoader::registerAlias('nrFrameworkAssignmentsHelper',        '\\NRFramework\\Assignments');
JLoader::registerAlias('nrFrameworkAssignmentsAcyMailing',    '\\NRFramework\\Assignments\\AcyMailing');
JLoader::registerAlias('nrFrameworkAssignmentsAkeebaSubs',    '\\NRFramework\\Assignments\\AkeebaSubs');
JLoader::registerAlias('nrFrameworkAssignmentsContent',       '\\NRFramework\\Assignments\\Content');
JLoader::registerAlias('nrFrameworkAssignmentsConvertForms',  '\\NRFramework\\Assignments\\ConvertForms');
JLoader::registerAlias('nrFrameworkAssignmentsDateTime',      '\\NRFramework\\Assignments\\DateTime');
JLoader::registerAlias('nrFrameworkAssignmentsDevices',       '\\NRFramework\\Assignments\\Devices');
JLoader::registerAlias('nrFrameworkAssignmentsGeoIP',         '\\NRFramework\\Assignments\\GeoIP');
JLoader::registerAlias('nrFrameworkAssignmentsLanguages',     '\\NRFramework\\Assignments\\Languages');
JLoader::registerAlias('nrFrameworkAssignmentsMenu',          '\\NRFramework\\Assignments\\Menu');
JLoader::registerAlias('nrFrameworkAssignmentsPHP',           '\\NRFramework\\Assignments\\PHP');
JLoader::registerAlias('nrFrameworkAssignmentsURLs',          '\\NRFramework\\Assignments\\URLs');
JLoader::registerAlias('nrFrameworkAssignmentsUsers',         '\\NRFramework\\Assignments\\Users');
JLoader::registerAlias('nrFrameworkAssignmentsOS',            '\\NRFramework\\Assignments\\OS');
JLoader::registerAlias('nrFrameworkAssignmentsBrowsers',      '\\NRFramework\\Assignments\\Browsers');

// helper class aliases
JLoader::registerAlias('NRCache',     '\\NRFramework\\Cache');
JLoader::registerAlias('NRHTML',      '\\NRFramework\\HTML');
