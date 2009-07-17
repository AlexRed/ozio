<?php
/*
+-------------------------------------------------+
| Joomla Ozio Gallery Component for Joomla 1.5
| oziogallery.php
+-------------------------------------------------+
| Author:                    
|	AlexRed
|	http://www.joomla.it
|
| Based on:
|    Joomla WebcamXP Component for Joomla 1.5 by Jooglar (jwebcamxp@jooglar.com) http://jooglar.com
|    &
|    WebcamXP Component for Joomla 1.0.x & Mambo 4.5.x by Andy Stewart (andy@troozers.com) http://www.troozers.com
|
| License:
|     Released under GNU/GPL License
|     http://www.gnu.org/copyleft/gpl.html
|
+-------------------------------------------------+
*/
// ensure this file is being included by a parent file
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once( JApplicationHelper::getPath( 'admin_html' ) );

		$document 	=& JFactory::getDocument();
		$url 		= $mainframe->getSiteURL();
		$url_css 	= 'administrator/components/'.$option.'/css/default.css';
		
		$document->addStyleSheet($url.$url_css);

		switch($task)
		{
			case 'faq': 					showFAQ( $option );		break;
			case 'reset': 					showReset( $option );	break;
			case 'imagerotator': 			Svuota	( $option );	break;	
			case 'accordion': 				Svuota1	( $option );	break;
			case 'carousel': 				Svuota2	( $option );	break;
			case 'flashgallery': 			Svuota3	( $option );	break;
			case 'tilt': 					Svuota4	( $option );	break;

			case 'resetel': 				showResetel	( $option );	break;			
			case 'resetImg': 				resetImg	( $option );	break;	
			case 'resetAcc': 				resetAcc	( $option );	break;
			case 'resetCar': 				resetCar	( $option );	break;
			case 'resetFLG': 				resetFLG	( $option );	break;
			case 'resetTilt': 				resetTilt	( $option );	break;			
			default:						showIntro	( $option );	break;

		}


		function showIntro( $option )
		{
	
		JToolBarHelper::title( JText::_( 'Ozio Gallery 2' ),'logo' );  
		JToolBarHelper::customX( 'faq',  'faq', 'alt', 'FAQ', false );	
		JToolBarHelper::customX( 'reset',  'reset', 'alt', 'Reset', false );		
			JSubMenuHelper::addEntry( JText::_( 'OzioGallery 2 - Cpanel' ), 'index.php?option=com_oziogallery2', true);
			JSubMenuHelper::addEntry( JText::_( 'Reset XML' ), 'index.php?option=com_oziogallery2&amp;task=reset');			
			JSubMenuHelper::addEntry( JText::_( 'F.A.Q.' ), 'index.php?option=com_oziogallery2&amp;task=faq');
			HTML_oziogallery2::showIntro( $option );
		}


		function showFAQ( $option )
		{
			$lists["faq"] = array();
			$faq["question"] = "Very Important Note";
			$faq["answer"] = "Once you install the Ozio Gallery software on your site, you need to <strong>upload the photos</strong> to images/oziogallery2 directory.";

			$lists["faq"][] = $faq;

		JToolBarHelper::title( JText::_( 'Ozio Gallery 2 - Frequently Asked Questions' ),'logo' );  
		JToolBarHelper::cancel();				
			JSubMenuHelper::addEntry( JText::_( 'OzioGallery - Cpanel' ), 'index.php?option=com_oziogallery2');
			JSubMenuHelper::addEntry( JText::_( 'Reset XML' ), 'index.php?option=com_oziogallery2&amp;task=reset');				
			JSubMenuHelper::addEntry( JText::_( 'F.A.Q.' ), 'index.php?option=com_oziogallery2&amp;task=faq', true);			
			HTML_oziogallery2::showFAQ( $option, $lists );
		}
		
		function showReset( $option )
		{
	
		JToolBarHelper::title( JText::_( 'Ozio Gallery 2 - Reset' ),'logo' );  
		JToolBarHelper::cancel();	
			JSubMenuHelper::addEntry( JText::_( 'OzioGallery - Cpanel' ), 'index.php?option=com_oziogallery2');
			JSubMenuHelper::addEntry( JText::_( 'Reset XML' ), 'index.php?option=com_oziogallery2&amp;task=reset', true);				
			JSubMenuHelper::addEntry( JText::_( 'F.A.Q.' ), 'index.php?option=com_oziogallery2&amp;task=faq');
			HTML_oziogallery2::showReset( $option );
		}

		function showResetel( $option )
		{
	
		JToolBarHelper::title( JText::_( 'Ozio Gallery 2 - Reset' ),'logo' );  
			HTML_oziogallery2::showResetel( $option );
		}			


		function Svuota($dir)
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
				$link = 'index.php?option=com_oziogallery2&task=reset';
				$mainframe->redirect( $link, $message);
		}


		function Svuota1($dir)
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
				$link = 'index.php?option=com_oziogallery2&task=reset';
				$mainframe->redirect( $link, $message);
		}		

		function Svuota2($dir)
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
				$link = 'index.php?option=com_oziogallery2&task=reset';
				$mainframe->redirect( $link, $message);
		}

		function Svuota3($dir)
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
				$link = 'index.php?option=com_oziogallery2&task=reset';
				$mainframe->redirect( $link, $message);
		}	

		function Svuota4($dir)
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
				$link = 'index.php?option=com_oziogallery2&task=reset';
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
				$link = 'index.php?option=com_oziogallery2&task=resetel&tmpl=component';
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
				$link = 'index.php?option=com_oziogallery2&task=resetel&tmpl=component';
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
				$link = 'index.php?option=com_oziogallery2&task=resetel&tmpl=component';
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
				$link = 'index.php?option=com_oziogallery2&task=resetel&tmpl=component';
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
				$link = 'index.php?option=com_oziogallery2&task=resetel&tmpl=component';
				$mainframe->redirect( $link, $message);
		}		
	
	
?>
