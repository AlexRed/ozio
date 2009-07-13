<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

$params =& JComponentHelper::getParams('com_oziogallery2');

require_once (JPATH_COMPONENT.DS.'controller.php');

$classname  = 'OzioGalleryController';
$controller = new $classname( );

$controller->execute( JRequest::getVar('task', null, 'default', 'cmd') );

$controller->redirect();
?>