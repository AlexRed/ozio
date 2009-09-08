<?php
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

class PluginModuleHelper
{
    function LoadPluginModule($params)
    {
		$ozio =JTable::getInstance('content');       
			$ozio->text = '{oziogallery '.$params->get('code_id').'}'; 
			$dispatcher =JDispatcher::getInstance();
			$params =new JParameter('');
			JPluginHelper::importPlugin('content');
			$results = $dispatcher->trigger('onPrepareContent', array ($ozio,$params, 0));
			echo $ozio->text;
    }
}
?>