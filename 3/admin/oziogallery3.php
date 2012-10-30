<?php
/**
* This file is part of Ozio Gallery 3
*
* Ozio Gallery 3 is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 2 of the License, or
* (at your option) any later version.
*
* Foobar is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Foobar.  If not, see <http://www.gnu.org/licenses/>.
*
* @copyright Copyright (C) 2010 Open Source Solutions S.L.U. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see RT-LICENSE.php
*/

defined('_JEXEC') or die;

// Access check
if (!JFactory::getUser()->authorise("core.manage", "com_oziogallery3"))
{
	return JFactory::getApplication()->enqueueMessage(JText::_("JERROR_ALERTNOAUTHOR"), "error");
}

// Include dependencies
require_once JPATH_COMPONENT_SITE . "/oziogallery.inc";
jimport('joomla.application.component.controller');
require_once (JPATH_COMPONENT . '/classes/ozio.helper.php');
$controller	= JController::getInstance('Ozio');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();
$task = JRequest::getCmd('task');

// 12/01/2011 DP Removed switch construct
if (strpos($task, "reset") === 0)
	ozio_helper::$task();

/*
switch($task)
{
case 'accordion'			: ozio_helper::accordion($dir); 		break;
case 'carousel'				: ozio_helper::carousel($dir); 			break;
case 'flashgallery'			: ozio_helper::flashgallery($dir); 		break;
case 'imagerotator'			: ozio_helper::imagerotator($dir); 		break;
case 'tilt'					: ozio_helper::tilt($dir); 				break;
case 'mediagallery'			: ozio_helper::mediagallery($dir); 		break;
case 'futura'				: ozio_helper::futura($dir); 			break;
case 'cooliris'				: ozio_helper::cooliris($dir); 			break;
case 'resetImg' 			: ozio_helper::resetImg(); 				break;
case 'resetAcc' 			: ozio_helper::resetAcc(); 				break;
case 'resetCar' 			: ozio_helper::resetCar(); 				break;
case 'resetFLG' 			: ozio_helper::resetFLG(); 				break;
case 'resetTilt' 			: ozio_helper::resetTilt(); 			break;
case 'resetmediagallery' 	: ozio_helper::resetmediagallery(); 	break;
case 'resetfutura'		 	: ozio_helper::resetfutura(); 			break;
case 'resetcooliris' 		: ozio_helper::resetcooliris(); 		break;
}
*/
