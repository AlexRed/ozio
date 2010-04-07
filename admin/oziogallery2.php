<?php
/**
* This file is part of Ozio Gallery 2.
*
* Ozio Gallery 2 is free software: you can redistribute it and/or modify
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

defined( '_JEXEC' ) or die( 'Restricted access' );

$params =& JComponentHelper::getParams('com_oziogallery2');

require_once (JPATH_COMPONENT.DS.'controller.php');
require_once (JPATH_COMPONENT.DS.'classes'.DS.'ozio.helper.php');

if( $controller = JRequest::getWord('controller') ) : 
	$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
	if (file_exists($path)) :
			require_once $path;
		else:
			$controller = '';
	endif;
endif;

$classname  = 'OzioController'.$controller;
$controller = new $classname( );

$controller->execute( JRequest::getWord('task'));
$controller->redirect();

		switch($task)
		{
			case 'accordion'			: ozio_helper::accordion(); 			break;
			case 'carousel'				: ozio_helper::carousel(); 				break;
			case 'flashgallery'			: ozio_helper::flashgallery(); 			break;
			case 'imagerotator'			: ozio_helper::imagerotator(); 			break;			
			case 'tilt'					: ozio_helper::tilt(); 					break;
			case 'mediagallery'			: ozio_helper::mediagallery(); 			break;
			case 'resetImg' 			: ozio_helper::resetImg(); 				break;
			case 'resetAcc' 			: ozio_helper::resetAcc(); 				break;
			case 'resetCar' 			: ozio_helper::resetCar(); 				break;
			case 'resetFLG' 			: ozio_helper::resetFLG(); 				break;
			case 'resetTilt' 			: ozio_helper::resetTilt(); 			break;
			case 'resetmediagallery' 	: ozio_helper::resetmediagallery(); 	break;			
		}

?>