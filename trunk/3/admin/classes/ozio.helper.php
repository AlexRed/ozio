<?php
/**
* This file is part of Ozio Gallery 3.
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

// Todo: DP Function Svuota() doesn't exist. It causes errors if subdirectories of xml are present.

defined( '_JEXEC' ) or die( 'Restricted access' );

class ozio_helper
{	

		function imagerotator($dir)
		{
				$app = JFactory::getApplication('administrator');
		    	$VAMBpathAssoluto = JPATH_SITE;
				$VAMBpathAssoluto = str_replace("\\", "/" , $VAMBpathAssoluto);	
				$dir = $VAMBpathAssoluto.'/components/com_oziogallery3/skin/imagerotator';
					if($objs = @glob($dir.'/xml/*'))
			{
		        foreach($objs as $obj) 
				{
					@is_dir($obj)? Svuota($obj) : @unlink($obj);
				}
			}
				@rmdir($dir);
				//copio file index.html nella cartella temporanea
				$file 	= 'index.html';	
				$source = JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery3';	
				$dest 	= JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery3' . DS . 'skin' . DS . 'imagerotator' . DS . 'xml' . DS;	
				@copy($source. DS .$file,$dest. DS .$file);
		 
				$message = JText::_('Cartella XML').' Imagerotator '.JText::_('svuotata correttamente');
				$link = 'index.php?option=com_oziogallery3&view=reset';
				$app->redirect( $link, $message);
		}

		
		function accordion($dir)
		{
				$app = JFactory::getApplication('administrator');
		    	$VAMBpathAssoluto = JPATH_SITE;
				$VAMBpathAssoluto = str_replace("\\", "/" , $VAMBpathAssoluto);	
				$dir = $VAMBpathAssoluto.'/components/com_oziogallery3/skin/accordion';
					if($objs = @glob($dir.'/xml/*'))
			{
		        foreach($objs as $obj) 
				{
					@is_dir($obj)? Svuota($obj) : @unlink($obj);
				}
			}
				@rmdir($dir);
				//copio file index.html nella cartella temporanea
				$file 	= 'index.html';	
				$source = JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery3';	
				$dest 	= JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery3' . DS . 'skin' . DS . 'accordion' . DS . 'xml' . DS;	
				@copy($source. DS .$file,$dest. DS .$file);
		 
				$message = JText::_('Cartella XML').' Accordion '.JText::_('svuotata correttamente');
				$link = 'index.php?option=com_oziogallery3&view=reset';
				$app->redirect( $link, $message);
		}		

		function carousel($dir)
		{
				$app = JFactory::getApplication('administrator');
		    	$VAMBpathAssoluto = JPATH_SITE;
				$VAMBpathAssoluto = str_replace("\\", "/" , $VAMBpathAssoluto);	
				$dir = $VAMBpathAssoluto.'/components/com_oziogallery3/skin/carousel';
					if($objs = @glob($dir.'/xml/*'))
			{
		        foreach($objs as $obj) 
				{
					@is_dir($obj)? Svuota($obj) : @unlink($obj);
				}
			}
				@rmdir($dir);
				//copio file index.html nella cartella temporanea
				$file 	= 'index.html';	
				$source = JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery3';	
				$dest 	= JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery3' . DS . 'skin' . DS . 'carousel' . DS . 'xml' . DS;	
				@copy($source. DS .$file,$dest. DS .$file);
		 
				$message = JText::_('Cartella XML').' Carousel '.JText::_('svuotata correttamente');
				$link = 'index.php?option=com_oziogallery3&view=reset';
				$app->redirect( $link, $message);
		}

		function flashgallery($dir)
		{
				$app = JFactory::getApplication('administrator');
		    	$VAMBpathAssoluto = JPATH_SITE;
				$VAMBpathAssoluto = str_replace("\\", "/" , $VAMBpathAssoluto);	
				$dir = $VAMBpathAssoluto.'/components/com_oziogallery3/skin/flashgallery';
					if($objs = @glob($dir.'/xml/*'))
			{
		        foreach($objs as $obj) 
				{
					@is_dir($obj)? Svuota($obj) : @unlink($obj);
				}
			}
				@rmdir($dir);
				//copio file index.html nella cartella temporanea
				$file 	= 'index.html';	
				$source = JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery3';	
				$dest 	= JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery3' . DS . 'skin' . DS . 'flashgallery' . DS . 'xml' . DS;	
				@copy($source. DS .$file,$dest. DS .$file);
		 
				$message = JText::_('Cartella XML').' FlashGallery '.JText::_('svuotata correttamente');
				$link = 'index.php?option=com_oziogallery3&view=reset';
				$app->redirect( $link, $message);
		}	

		function tilt($dir)
		{
				$app = JFactory::getApplication('administrator');
		    	$VAMBpathAssoluto = JPATH_SITE;
				$VAMBpathAssoluto = str_replace("\\", "/" , $VAMBpathAssoluto);	
				$dir = $VAMBpathAssoluto.'/components/com_oziogallery3/skin/tiltviewer';
					if($objs = @glob($dir.'/xml/*'))
			{
		        foreach($objs as $obj) 
				{
					@is_dir($obj)? Svuota($obj) : @unlink($obj);
				}
			}
				@rmdir($dir);
				//copio file index.html nella cartella temporanea
				$file 	= 'index.html';	
				$source = JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery3';	
				$dest 	= JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery3' . DS . 'skin' . DS . 'tiltviewer' . DS . 'xml' . DS;	
				@copy($source. DS .$file,$dest. DS .$file);
		 
				$message = JText::_('Cartella XML').' Tilt 3D '.JText::_('svuotata correttamente');
				$link = 'index.php?option=com_oziogallery3&view=reset';
				$app->redirect( $link, $message);
		}			

		function resetImg()
		{
				$app = JFactory::getApplication('administrator');
		    	$VAMBpathAssoluto = JPATH_SITE;
				$VAMBpathAssoluto = str_replace("\\", "/" , $VAMBpathAssoluto);	
				$dir = $VAMBpathAssoluto.'/components/com_oziogallery3/skin/imagerotator';
					if($objs = @glob($dir.'/xml/*'))
			{
		        foreach($objs as $obj) 
				{
					@is_dir($obj)? Svuota($obj) : @unlink($obj);
				}
			}
				@rmdir($dir);
				//copio file index.html nella cartella temporanea
				$file 	= 'index.html';	
				$source = JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery3';	
				$dest 	= JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery3' . DS . 'skin' . DS . 'imagerotator' . DS . 'xml' . DS;	
				@copy($source. DS .$file,$dest. DS .$file);

				$message = '<p style="line-height:300%; font-size: 12px; font-weight:bold;">';					
				$message .= JText::_('Cartella XML').' Imagerotator '.JText::_('svuotata correttamente');
				$message .= '</p>';				
				$link = 'index.php?option=com_oziogallery3&view=resetel&tmpl=component';
				$app->redirect( $link, $message);
		}


		function resetAcc()
		{
				$app = JFactory::getApplication('administrator');
		    	$VAMBpathAssoluto = JPATH_SITE;
				$VAMBpathAssoluto = str_replace("\\", "/" , $VAMBpathAssoluto);	
				$dir = $VAMBpathAssoluto.'/components/com_oziogallery3/skin/accordion';
					if($objs = @glob($dir.'/xml/*'))
			{
		        foreach($objs as $obj) 
				{
					@is_dir($obj)? Svuota($obj) : @unlink($obj);
				}
			}
				@rmdir($dir);
				//copio file index.html nella cartella temporanea
				$file 	= 'index.html';	
				$source = JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery3';	
				$dest 	= JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery3' . DS . 'skin' . DS . 'accordion' . DS . 'xml' . DS;	
				@copy($source. DS .$file,$dest. DS .$file);

				$message = '<p style="line-height:300%; font-size: 12px; font-weight:bold;">';					
				$message .= JText::_('Cartella XML').' Accordion '.JText::_('svuotata correttamente');
				$message .= '</p>';				
				$link = 'index.php?option=com_oziogallery3&view=resetel&tmpl=component';
				$app->redirect( $link, $message);
		}		

		function resetCar()
		{
				$app = JFactory::getApplication('administrator');
		    	$VAMBpathAssoluto = JPATH_SITE;
				$VAMBpathAssoluto = str_replace("\\", "/" , $VAMBpathAssoluto);	
				$dir = $VAMBpathAssoluto.'/components/com_oziogallery3/skin/carousel';
					if($objs = @glob($dir.'/xml/*'))
			{
		        foreach($objs as $obj) 
				{
					@is_dir($obj)? Svuota($obj) : @unlink($obj);
				}
			}
				@rmdir($dir);
				//copio file index.html nella cartella temporanea
				$file 	= 'index.html';	
				$source = JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery3';	
				$dest 	= JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery3' . DS . 'skin' . DS . 'carousel' . DS . 'xml' . DS;	
				@copy($source. DS .$file,$dest. DS .$file);

				$message = '<p style="line-height:300%; font-size: 12px; font-weight:bold;">';					
				$message .= JText::_('Cartella XML').' Carousel '.JText::_('svuotata correttamente');
				$message .= '</p>';				
				$link = 'index.php?option=com_oziogallery3&view=resetel&tmpl=component';
				$app->redirect( $link, $message);
		}

		
	
		function resetFLG()
		{
				$app = JFactory::getApplication('administrator');
		    	$VAMBpathAssoluto = JPATH_SITE;
				$VAMBpathAssoluto = str_replace("\\", "/" , $VAMBpathAssoluto);	
				$dir = $VAMBpathAssoluto.'/components/com_oziogallery3/skin/flashgallery';
					if($objs = @glob($dir.'/xml/*'))
			{
		        foreach($objs as $obj) 
				{
					@is_dir($obj)? Svuota($obj) : @unlink($obj);
				}
			}
				@rmdir($dir);
				//copio file index.html nella cartella temporanea
				$file 	= 'index.html';	
				$source = JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery3';	
				$dest 	= JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery3' . DS . 'skin' . DS . 'flashgallery' . DS . 'xml' . DS;	
				@copy($source. DS .$file,$dest. DS .$file);
				
				$message = '<p style="line-height:300%; font-size: 12px; font-weight:bold;">';		 
				$message .= JText::_('Cartella XML').' FlashGallery '.JText::_('svuotata correttamente');
				$message .= '</p>';				
				$link = 'index.php?option=com_oziogallery3&view=resetel&tmpl=component';
				$app->redirect( $link, $message);
		}		
	
		function resetTilt()
		{
				$app = JFactory::getApplication('administrator');
		    	$VAMBpathAssoluto = JPATH_SITE;
				$VAMBpathAssoluto = str_replace("\\", "/" , $VAMBpathAssoluto);	
				$dir = $VAMBpathAssoluto.'/components/com_oziogallery3/skin/tiltviewer';
					if($objs = @glob($dir.'/xml/*'))
			{
		        foreach($objs as $obj) 
				{
					@is_dir($obj)? Svuota($obj) : @unlink($obj);
				}
			}
				@rmdir($dir);
				//copio file index.html nella cartella temporanea
				$file 	= 'index.html';	
				$source = JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery3';	
				$dest 	= JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery3' . DS . 'skin' . DS . 'tiltviewer' . DS . 'xml' . DS;	
				@copy($source. DS .$file,$dest. DS .$file);
		 
				$message = '<p style="line-height:300%; font-size: 12px; font-weight:bold;">';
				$message .= JText::_('Cartella XML').' Tilt 3D '.JText::_('svuotata correttamente');
				$message .= '</p>';
				$link = 'index.php?option=com_oziogallery3&view=resetel&tmpl=component';
				$app->redirect( $link, $message);
		}			

		function mediagallery()
		{
				$app = JFactory::getApplication('administrator');
		    	$VAMBpathAssoluto = JPATH_SITE;
				$VAMBpathAssoluto = str_replace("\\", "/" , $VAMBpathAssoluto);	
				$dir = $VAMBpathAssoluto.'/components/com_oziogallery3/skin/mediagallery';
					if($objs = @glob($dir.'/xml/*'))
			{
		        foreach($objs as $obj) 
				{
					@is_dir($obj)? Svuota($obj) : @unlink($obj);
				}
			}
				@rmdir($dir);
				//copio file index.html nella cartella temporanea
				$file 	= 'index.html';	
				$source = JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery3';	
				$dest 	= JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery3' . DS . 'skin' . DS . 'mediagallery' . DS . 'xml' . DS;	
				@copy($source. DS .$file,$dest. DS .$file);
		 
				$message = JText::_('Cartella XML').' mediagallery '.JText::_('svuotata correttamente');
				$link = 'index.php?option=com_oziogallery3&view=reset';
				$app->redirect( $link, $message);
		}			

		function resetmediagallery()
		{
				$app = JFactory::getApplication('administrator');
		    	$VAMBpathAssoluto = JPATH_SITE;
				$VAMBpathAssoluto = str_replace("\\", "/" , $VAMBpathAssoluto);	
				$dir = $VAMBpathAssoluto.'/components/com_oziogallery3/skin/mediagallery';
					if($objs = @glob($dir.'/xml/*'))
			{
		        foreach($objs as $obj) 
				{
					@is_dir($obj)? Svuota($obj) : @unlink($obj);
				}
			}
				@rmdir($dir);
				//copio file index.html nella cartella temporanea
				$file 	= 'index.html';	
				$source = JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery3';	
				$dest 	= JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery3' . DS . 'skin' . DS . 'mediagallery' . DS . 'xml' . DS;	
				@copy($source. DS .$file,$dest. DS .$file);
		 
				$message = '<p style="line-height:300%; font-size: 12px; font-weight:bold;">';
				$message .= JText::_('Cartella XML').' mediagallery '.JText::_('svuotata correttamente');
				$message .= '</p>';
				$link = 'index.php?option=com_oziogallery3&view=resetel&tmpl=component';
				$app->redirect( $link, $message);
		}

		function futura($dir)
		{
				$app = JFactory::getApplication('administrator');
		    	$VAMBpathAssoluto = JPATH_SITE;
				$VAMBpathAssoluto = str_replace("\\", "/" , $VAMBpathAssoluto);	
				$dir = $VAMBpathAssoluto.'/components/com_oziogallery3/skin/futura';
					if($objs = @glob($dir.'/xml/*'))
			{
		        foreach($objs as $obj) 
				{
					@is_dir($obj)? Svuota($obj) : @unlink($obj);
				}
			}
				@rmdir($dir);
				//copio file index.html nella cartella temporanea
				$file 	= 'index.html';	
				$source = JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery3';	
				$dest 	= JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery3' . DS . 'skin' . DS . 'futura' . DS . 'xml' . DS;	
				@copy($source. DS .$file,$dest. DS .$file);
		 
				$message = JText::_('Cartella XML').' futura '.JText::_('svuotata correttamente');
				$link = 'index.php?option=com_oziogallery3&view=reset';
				$app->redirect( $link, $message);
		}			

		function resetfutura()
		{
				$app = JFactory::getApplication('administrator');
		    	$VAMBpathAssoluto = JPATH_SITE;
				$VAMBpathAssoluto = str_replace("\\", "/" , $VAMBpathAssoluto);	
				$dir = $VAMBpathAssoluto.'/components/com_oziogallery3/skin/futura';
					if($objs = @glob($dir.'/xml/*'))
			{
		        foreach($objs as $obj) 
				{
					@is_dir($obj)? Svuota($obj) : @unlink($obj);
				}
			}
				@rmdir($dir);
				//copio file index.html nella cartella temporanea
				$file 	= 'index.html';	
				$source = JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery3';	
				$dest 	= JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery3' . DS . 'skin' . DS . 'futura' . DS . 'xml' . DS;	
				@copy($source. DS .$file,$dest. DS .$file);
		 
				$message = '<p style="line-height:300%; font-size: 12px; font-weight:bold;">';
				$message .= JText::_('Cartella XML').' futura '.JText::_('svuotata correttamente');
				$message .= '</p>';
				$link = 'index.php?option=com_oziogallery3&view=resetel&tmpl=component';
				$app->redirect( $link, $message);
		}
		
		function cooliris($dir)
		{
				$app = JFactory::getApplication('administrator');
		    	$VAMBpathAssoluto = JPATH_SITE;
				$VAMBpathAssoluto = str_replace("\\", "/" , $VAMBpathAssoluto);	
				$dir = $VAMBpathAssoluto.'/components/com_oziogallery3/skin/cooliris';
					if($objs = @glob($dir.'/xml/*'))
			{
		        foreach($objs as $obj) 
				{
					@is_dir($obj)? Svuota($obj) : @unlink($obj);
				}
			}
				@rmdir($dir);
				//copio file index.html nella cartella temporanea
				$file 	= 'index.html';	
				$source = JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery3';	
				$dest 	= JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery3' . DS . 'skin' . DS . 'cooliris' . DS . 'xml' . DS;	
				@copy($source. DS .$file,$dest. DS .$file);
		 
				$message = JText::_('Cartella XML').' Tilt 3D '.JText::_('svuotata correttamente');
				$link = 'index.php?option=com_oziogallery3&view=reset';
				$app->redirect( $link, $message);
		}
		
        function resetcooliris($dir)
		{
				$app = JFactory::getApplication('administrator');
		    	$VAMBpathAssoluto = JPATH_SITE;
				$VAMBpathAssoluto = str_replace("\\", "/" , $VAMBpathAssoluto);	
				$dir = $VAMBpathAssoluto.'/components/com_oziogallery3/skin/cooliris';
					if($objs = @glob($dir.'/xml/*'))
			{
		        foreach($objs as $obj) 
				{
					@is_dir($obj)? Svuota($obj) : @unlink($obj);
				}
			}
				@rmdir($dir);
				//copio file index.html nella cartella temporanea
				$file 	= 'index.html';	
				$source = JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery3';	
				$dest 	= JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery3' . DS . 'skin' . DS . 'cooliris' . DS . 'xml' . DS;	
				@copy($source. DS .$file,$dest. DS .$file);
		 
				$message = '<p style="line-height:300%; font-size: 12px; font-weight:bold;">';
				$message .= JText::_('Cartella XML').' cooliris '.JText::_('svuotata correttamente');
				$message .= '</p>';
				$link = 'index.php?option=com_oziogallery3&view=resetel&tmpl=component';
				$app->redirect( $link, $message);
		}				
		
}
?>