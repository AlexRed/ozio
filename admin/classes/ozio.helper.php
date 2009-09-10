<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

class ozio_helper
{	

		function imagerotator($dir)
		{
				global $mainframe;
		    	$VAMBpathAssoluto = JPATH_SITE;
				$VAMBpathAssoluto = str_replace("\\", "/" , $VAMBpathAssoluto);	
				$dir = $VAMBpathAssoluto.'/components/com_oziogallery2/skin/imagerotator';
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
				$source = JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery2';	
				$dest 	= JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery2' . DS . 'skin' . DS . 'imagerotator' . DS . 'xml' . DS;	
				@copy($source. DS .$file,$dest. DS .$file);
		 
				$message = JText::_('Cartella XML').' Imagerotator '.JText::_('svuotata correttamente');
				$link = 'index.php?option=com_oziogallery2&view=reset';
				$mainframe->redirect( $link, $message);
		}

		
		function accordion($dir)
		{
				global $mainframe;
		    	$VAMBpathAssoluto = JPATH_SITE;
				$VAMBpathAssoluto = str_replace("\\", "/" , $VAMBpathAssoluto);	
				$dir = $VAMBpathAssoluto.'/components/com_oziogallery2/skin/accordion';
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
				$source = JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery2';	
				$dest 	= JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery2' . DS . 'skin' . DS . 'accordion' . DS . 'xml' . DS;	
				@copy($source. DS .$file,$dest. DS .$file);
		 
				$message = JText::_('Cartella XML').' Accordion '.JText::_('svuotata correttamente');
				$link = 'index.php?option=com_oziogallery2&view=reset';
				$mainframe->redirect( $link, $message);
		}		

		function carousel($dir)
		{
				global $mainframe;
		    	$VAMBpathAssoluto = JPATH_SITE;
				$VAMBpathAssoluto = str_replace("\\", "/" , $VAMBpathAssoluto);	
				$dir = $VAMBpathAssoluto.'/components/com_oziogallery2/skin/carousel';
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
				$source = JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery2';	
				$dest 	= JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery2' . DS . 'skin' . DS . 'carousel' . DS . 'xml' . DS;	
				@copy($source. DS .$file,$dest. DS .$file);
		 
				$message = JText::_('Cartella XML').' Carousel '.JText::_('svuotata correttamente');
				$link = 'index.php?option=com_oziogallery2&view=reset';
				$mainframe->redirect( $link, $message);
		}

		function flashgallery($dir)
		{
				global $mainframe;
		    	$VAMBpathAssoluto = JPATH_SITE;
				$VAMBpathAssoluto = str_replace("\\", "/" , $VAMBpathAssoluto);	
				$dir = $VAMBpathAssoluto.'/components/com_oziogallery2/skin/flashgallery';
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
				$source = JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery2';	
				$dest 	= JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery2' . DS . 'skin' . DS . 'flashgallery' . DS . 'xml' . DS;	
				@copy($source. DS .$file,$dest. DS .$file);
		 
				$message = JText::_('Cartella XML').' FlashGallery '.JText::_('svuotata correttamente');
				$link = 'index.php?option=com_oziogallery2&view=reset';
				$mainframe->redirect( $link, $message);
		}	

		function tilt($dir)
		{
				global $mainframe;
		    	$VAMBpathAssoluto = JPATH_SITE;
				$VAMBpathAssoluto = str_replace("\\", "/" , $VAMBpathAssoluto);	
				$dir = $VAMBpathAssoluto.'/components/com_oziogallery2/skin/tiltviewer';
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
				$source = JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery2';	
				$dest 	= JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery2' . DS . 'skin' . DS . 'tiltviewer' . DS . 'xml' . DS;	
				@copy($source. DS .$file,$dest. DS .$file);
		 
				$message = JText::_('Cartella XML').' Tilt 3D '.JText::_('svuotata correttamente');
				$link = 'index.php?option=com_oziogallery2&view=reset';
				$mainframe->redirect( $link, $message);
		}			

		function resetImg ($dir)
		{
				global $mainframe;
		    	$VAMBpathAssoluto = JPATH_SITE;
				$VAMBpathAssoluto = str_replace("\\", "/" , $VAMBpathAssoluto);	
				$dir = $VAMBpathAssoluto.'/components/com_oziogallery2/skin/imagerotator';
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
				$source = JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery2';	
				$dest 	= JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery2' . DS . 'skin' . DS . 'imagerotator' . DS . 'xml' . DS;	
				@copy($source. DS .$file,$dest. DS .$file);

				$message = '<p style="line-height:300%; font-size: 12px; font-weight:bold;">';					
				$message .= JText::_('Cartella XML').' Imagerotator '.JText::_('svuotata correttamente');
				$message .= '</p>';				
				$link = 'index.php?option=com_oziogallery2&view=resetel&tmpl=component';
				$mainframe->redirect( $link, $message);
		}


		function resetAcc ($dir)
		{
				global $mainframe;
		    	$VAMBpathAssoluto = JPATH_SITE;
				$VAMBpathAssoluto = str_replace("\\", "/" , $VAMBpathAssoluto);	
				$dir = $VAMBpathAssoluto.'/components/com_oziogallery2/skin/accordion';
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
				$source = JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery2';	
				$dest 	= JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery2' . DS . 'skin' . DS . 'accordion' . DS . 'xml' . DS;	
				@copy($source. DS .$file,$dest. DS .$file);

				$message = '<p style="line-height:300%; font-size: 12px; font-weight:bold;">';					
				$message .= JText::_('Cartella XML').' Accordion '.JText::_('svuotata correttamente');
				$message .= '</p>';				
				$link = 'index.php?option=com_oziogallery2&view=resetel&tmpl=component';
				$mainframe->redirect( $link, $message);
		}		

		function resetCar ($dir)
		{
				global $mainframe;
		    	$VAMBpathAssoluto = JPATH_SITE;
				$VAMBpathAssoluto = str_replace("\\", "/" , $VAMBpathAssoluto);	
				$dir = $VAMBpathAssoluto.'/components/com_oziogallery2/skin/carousel';
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
				$source = JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery2';	
				$dest 	= JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery2' . DS . 'skin' . DS . 'carousel' . DS . 'xml' . DS;	
				@copy($source. DS .$file,$dest. DS .$file);

				$message = '<p style="line-height:300%; font-size: 12px; font-weight:bold;">';					
				$message .= JText::_('Cartella XML').' Carousel '.JText::_('svuotata correttamente');
				$message .= '</p>';				
				$link = 'index.php?option=com_oziogallery2&view=resetel&tmpl=component';
				$mainframe->redirect( $link, $message);
		}

		
	
		function resetFLG($dir)
		{
				global $mainframe;
		    	$VAMBpathAssoluto = JPATH_SITE;
				$VAMBpathAssoluto = str_replace("\\", "/" , $VAMBpathAssoluto);	
				$dir = $VAMBpathAssoluto.'/components/com_oziogallery2/skin/flashgallery';
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
				$source = JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery2';	
				$dest 	= JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery2' . DS . 'skin' . DS . 'flashgallery' . DS . 'xml' . DS;	
				@copy($source. DS .$file,$dest. DS .$file);
				
				$message = '<p style="line-height:300%; font-size: 12px; font-weight:bold;">';		 
				$message .= JText::_('Cartella XML').' FlashGallery '.JText::_('svuotata correttamente');
				$message .= '</p>';				
				$link = 'index.php?option=com_oziogallery2&view=resetel&tmpl=component';
				$mainframe->redirect( $link, $message);
		}		
	
		function resetTilt($dir)
		{
				global $mainframe;
		    	$VAMBpathAssoluto = JPATH_SITE;
				$VAMBpathAssoluto = str_replace("\\", "/" , $VAMBpathAssoluto);	
				$dir = $VAMBpathAssoluto.'/components/com_oziogallery2/skin/tiltviewer';
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
				$source = JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery2';	
				$dest 	= JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery2' . DS . 'skin' . DS . 'tiltviewer' . DS . 'xml' . DS;	
				@copy($source. DS .$file,$dest. DS .$file);
		 
				$message = '<p style="line-height:300%; font-size: 12px; font-weight:bold;">';
				$message .= JText::_('Cartella XML').' Tilt 3D '.JText::_('svuotata correttamente');
				$message .= '</p>';
				$link = 'index.php?option=com_oziogallery2&view=resetel&tmpl=component';
				$mainframe->redirect( $link, $message);
		}			

		function mediagallery($dir)
		{
				global $mainframe;
		    	$VAMBpathAssoluto = JPATH_SITE;
				$VAMBpathAssoluto = str_replace("\\", "/" , $VAMBpathAssoluto);	
				$dir = $VAMBpathAssoluto.'/components/com_oziogallery2/skin/mediagallery';
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
				$source = JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery2';	
				$dest 	= JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery2' . DS . 'skin' . DS . 'mediagallery' . DS . 'xml' . DS;	
				@copy($source. DS .$file,$dest. DS .$file);
		 
				$message = JText::_('Cartella XML').' mediagallery '.JText::_('svuotata correttamente');
				$link = 'index.php?option=com_oziogallery2&view=reset';
				$mainframe->redirect( $link, $message);
		}			

		function resetmediagallery($dir)
		{
				global $mainframe;
		    	$VAMBpathAssoluto = JPATH_SITE;
				$VAMBpathAssoluto = str_replace("\\", "/" , $VAMBpathAssoluto);	
				$dir = $VAMBpathAssoluto.'/components/com_oziogallery2/skin/mediagallery';
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
				$source = JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery2';	
				$dest 	= JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery2' . DS . 'skin' . DS . 'mediagallery' . DS . 'xml' . DS;	
				@copy($source. DS .$file,$dest. DS .$file);
		 
				$message = '<p style="line-height:300%; font-size: 12px; font-weight:bold;">';
				$message .= JText::_('Cartella XML').' mediagallery '.JText::_('svuotata correttamente');
				$message .= '</p>';
				$link = 'index.php?option=com_oziogallery2&view=resetel&tmpl=component';
				$mainframe->redirect( $link, $message);
		}		
		
}
?>