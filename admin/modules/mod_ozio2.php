<?php
//no direct access
defined('_JEXEC') or die('Direct Access to this location is not allowed.');
 
require_once(dirname(__FILE__).DS.'helper.php');

$output = PluginModuleHelper::LoadPluginModule($params);
  
require(JModuleHelper::getLayoutPath('mod_ozio2'));
?>