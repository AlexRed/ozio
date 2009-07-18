<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

class OzioViewReset extends JView
{
	function display( $tpl = null )
	{
		global $mainframe;


		$document	= & JFactory::getDocument();
		$template	= $mainframe->getTemplate();
		$params 	= & JComponentHelper::getParams('com_oziogallery2');
	
		JToolBarHelper::title( JText::_( 'Ozio Gallery 2' ),'logo' );

		$document->addStyleSheet('components/com_oziogallery2/css/default.css');

		JSubMenuHelper::addEntry( JText::_( 'OzioGallery 2 - Cpanel' ), 'index.php?option=com_oziogallery2');
		JSubMenuHelper::addEntry( JText::_( 'Reset XML' ), 'index.php?option=com_oziogallery2&amp;view=reset', true);			
		JSubMenuHelper::addEntry( JText::_( 'F.A.Q.' ), 'index.php?option=com_oziogallery2&amp;view=faq');


		$link1	= 'index.php?option=com_oziogallery2&task=accordion';
		$link2	= 'index.php?option=com_oziogallery2&task=carousel';		
		$link3	= 'index.php?option=com_oziogallery2&task=flashgallery';
		$link4	= 'index.php?option=com_oziogallery2&task=imagerotator';
		$link5	= 'index.php?option=com_oziogallery2&task=tilt';
		
        $img = JURI::root().'administrator/images/delete_f2.png';
		
		$VAMBpathAssoluto = JPATH_SITE;
		$VAMBpathAssoluto = str_replace("\\", "/" , $VAMBpathAssoluto);	
		$Directory1 = $VAMBpathAssoluto.'/components/com_oziogallery2/skin/accordion/xml/';
		$Directory2 = $VAMBpathAssoluto.'/components/com_oziogallery2/skin/carousel/xml/';			
		$Directory3 = $VAMBpathAssoluto.'/components/com_oziogallery2/skin/flashgallery/xml/';
		$Directory4 = $VAMBpathAssoluto.'/components/com_oziogallery2/skin/imagerotator/xml/';		
		$Directory5 = $VAMBpathAssoluto.'/components/com_oziogallery2/skin/tiltviewer/xml/';


      if(is_dir($Directory1))
      {
          $dir = opendir($Directory1);
          $accordion = '<pre>';
          while(false !== ($file = readdir($dir)))
          {
		  if($file != '.' && $file != '..' && $file != 'index.html') 
		  {
              $size = filesize($Directory1 ."/". $file);
              $accordion .= "$file | $size kb \n";			  
          }			  
          }
          closedir($dir);
          $accordion .= "</pre>";
      }
      else
      {
          $accordion .= $Directory1 . JText::_ ( 'La directory non esiste' );
      }			
		
      if(is_dir($Directory2))
      {
          $dir = opendir($Directory2);
          $carousel = '<pre>';
          while(false !== ($file = readdir($dir)))
          {
		  if($file != '.' && $file != '..' && $file != 'index.html') 
		  {
              $size = filesize($Directory2 ."/". $file);
              $carousel .= "$file | $size kb \n";			  
          }			  
          }
          closedir($dir);
          $carousel .= "</pre>";
      }
      else
      {
          $carousel .= $Directory2 . JText::_ ( 'La directory non esiste' );
      }	

      if(is_dir($Directory3))
      {
          $dir = opendir($Directory3);
          $flashgallery = '<pre>';
          while(false !== ($file = readdir($dir)))
          {
		  if($file != '.' && $file != '..' && $file != 'index.html') 
		  {
              $size = filesize($Directory3 ."/". $file);
              $flashgallery .= "$file | $size kb \n";			  
          }			  
          }
          closedir($dir);
          $flashgallery .= "</pre>";
      }
      else
      {
          $flashgallery .= $Directory3 . JText::_ ( 'La directory non esiste' );
      }	

      if(is_dir($Directory4))
      {
          $dir = opendir($Directory4);
          $imagerotator = '<pre>';
          while(false !== ($file = readdir($dir)))
          {
		  if($file != '.' && $file != '..' && $file != 'index.html') 
		  {
              $size = filesize($Directory4 ."/". $file);
              $imagerotator .= "$file | $size kb \n";			  
          }			  
          }
          closedir($dir);
          $imagerotator .= "</pre>";
      }
      else
      {
          $imagerotator .= $Directory4 . JText::_ ( 'La directory non esiste' );
      }	

      if(is_dir($Directory5))
      {
          $dir = opendir($Directory5);
          $tilt = '<pre>';
          while(false !== ($file = readdir($dir)))
          {
		  if($file != '.' && $file != '..' && $file != 'index.html') 
		  {
              $size = filesize($Directory5 ."/". $file);
              $tilt .= "$file | $size kb \n";			  
          }			  
          }
          closedir($dir);
          $tilt .= "</pre>";
      }
      else
      {
          $tilt .= $Directory5 . JText::_ ( 'La directory non esiste' );
      }		  

		$this->assignRef('img'					, $img);
		$this->assignRef('link1'				, $link1);
		$this->assignRef('link2'				, $link2);
		$this->assignRef('link3'				, $link3);
		$this->assignRef('link4'				, $link4);
		$this->assignRef('link5'				, $link5);
		$this->assignRef('accordion'			, $accordion);	
		$this->assignRef('carousel'				, $carousel);
		$this->assignRef('flashgallery'			, $flashgallery);
		$this->assignRef('imagerotator'			, $imagerotator);
		$this->assignRef('tilt'					, $tilt);		

		parent::display($tpl);

	}
	
}
?>