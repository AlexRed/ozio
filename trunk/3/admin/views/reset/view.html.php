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

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');


class OzioViewReset extends JView
{

	protected $lists;
	protected $faq;

	public function display($tpl = null)
	{

		$link1	= 'index.php?option=com_oziogallery3&task=accordion';
		$link2	= 'index.php?option=com_oziogallery3&task=carousel';		
		$link3	= 'index.php?option=com_oziogallery3&task=flashgallery';
		$link4	= 'index.php?option=com_oziogallery3&task=imagerotator';
		$link5	= 'index.php?option=com_oziogallery3&task=tilt';
		$link6	= 'index.php?option=com_oziogallery3&task=mediagallery';
		$link7	= 'index.php?option=com_oziogallery3&task=cooliris';
		$link8	= 'index.php?option=com_oziogallery3&task=futura';
		
        $img = JURI::root().'administrator/components/com_oziogallery3/assets/images/delete.png';
		
		$VAMBpathAssoluto = JPATH_SITE;
		$VAMBpathAssoluto = str_replace("\\", "/" , $VAMBpathAssoluto);	
		$Directory1 = $VAMBpathAssoluto.'/components/com_oziogallery3/skin/accordion/xml/';
		$Directory2 = $VAMBpathAssoluto.'/components/com_oziogallery3/skin/carousel/xml/';			
		$Directory3 = $VAMBpathAssoluto.'/components/com_oziogallery3/skin/flashgallery/xml/';
		$Directory4 = $VAMBpathAssoluto.'/components/com_oziogallery3/skin/imagerotator/xml/';		
		$Directory5 = $VAMBpathAssoluto.'/components/com_oziogallery3/skin/tiltviewer/xml/';
		$Directory6 = $VAMBpathAssoluto.'/components/com_oziogallery3/skin/mediagallery/xml/';
		$Directory7 = $VAMBpathAssoluto.'/components/com_oziogallery3/skin/cooliris/xml/';
		$Directory8 = $VAMBpathAssoluto.'/components/com_oziogallery3/skin/futura/xml/';


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
          $accordion .= $Directory1 . JText::_ ( 'COM_OZIOGALLERY3_LA_DIRECTORY_NON_ESISTE' );
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
          $carousel .= $Directory2 . JText::_ ( 'COM_OZIOGALLERY3_LA_DIRECTORY_NON_ESISTE' );
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
          $flashgallery .= $Directory3 . JText::_ ( 'COM_OZIOGALLERY3_LA_DIRECTORY_NON_ESISTE' );
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
          $imagerotator .= $Directory4 . JText::_ ( 'COM_OZIOGALLERY3_LA_DIRECTORY_NON_ESISTE' );
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
          $tilt .= $Directory5 . JText::_ ( 'COM_OZIOGALLERY3_LA_DIRECTORY_NON_ESISTE' );
      }		  
	  
	        if(is_dir($Directory6))
      {
          $dir = opendir($Directory6);
          $mediagallery = '<pre>';
          while(false !== ($file = readdir($dir)))
          {
		  if($file != '.' && $file != '..' && $file != 'index.html') 
		  {
              $size = filesize($Directory6 ."/". $file);
              $mediagallery .= "$file | $size kb \n";			  
          }			  
          }
          closedir($dir);
          $mediagallery .= "</pre>";
      }
      else
      {
          $mediagallery .= $Directory6 . JText::_ ( 'COM_OZIOGALLERY3_LA_DIRECTORY_NON_ESISTE' );
      }	
	  
	  if(is_dir($Directory7))
      {
          $dir = opendir($Directory7);
          $cooliris = '<pre>';
          while(false !== ($file = readdir($dir)))
          {
		  if($file != '.' && $file != '..' && $file != 'index.html') 
		  {
              $size = filesize($Directory7 ."/". $file);
              $cooliris .= "$file | $size kb \n";			  
          }			  
          }
          closedir($dir);
          $cooliris .= "</pre>";
      }
      else
      {
          $cooliris .= $Directory7 . JText::_ ( 'COM_OZIOGALLERY3_LA_DIRECTORY_NON_ESISTE' );
      }	
	  
	  if(is_dir($Directory8))
      {
          $dir = opendir($Directory8);
          $futura = '<pre>';
          while(false !== ($file = readdir($dir)))
          {
		  if($file != '.' && $file != '..' && $file != 'index.html') 
		  {
              $size = filesize($Directory8 ."/". $file);
              $futura .= "$file | $size kb \n";			  
          }			  
          }
          closedir($dir);
          $futura .= "</pre>";
      }
      else
      {
          $futura .= $Directory8 . JText::_ ( 'COM_OZIOGALLERY3_LA_DIRECTORY_NON_ESISTE' );
      }

		$this->assignRef('img'					, $img);
		$this->assignRef('link1'				, $link1);
		$this->assignRef('link2'				, $link2);
		$this->assignRef('link3'				, $link3);
		$this->assignRef('link4'				, $link4);
		$this->assignRef('link5'				, $link5);
		$this->assignRef('link6'				, $link6);
		$this->assignRef('link7'				, $link7);
		$this->assignRef('link8'				, $link8);
		$this->assignRef('accordion'			, $accordion);	
		$this->assignRef('carousel'				, $carousel);
		$this->assignRef('flashgallery'			, $flashgallery);
		$this->assignRef('imagerotator'			, $imagerotator);
		$this->assignRef('tilt'					, $tilt);	
		$this->assignRef('mediagallery'			, $mediagallery);
		$this->assignRef('futura'				, $futura);
		$this->assignRef('cooliris'				, $cooliris);		
	
	
	
	
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
	}	
	
	
	protected function addToolbar()
	{
		$document	= JFactory::getDocument();
		$document->addStyleSheet('components/com_oziogallery3/assets/css/default.css');
		
		JToolBarHelper::title( JText::_( 'COM_OZIOGALLERY3_OZIO_GALLERY_3' ). ' - ' .JText::_( 'COM_OZIOGALLERY3_RESET_XML' ),'reset' );
		JSubMenuHelper::addEntry( JText::_( 'COM_OZIOGALLERY3_OZIOGALLERY_3_-_CPANEL' ), 'index.php?option=com_oziogallery3');
		JSubMenuHelper::addEntry( JText::_( 'COM_OZIOGALLERY3_RESET_XML' ), 'index.php?option=com_oziogallery3&amp;view=reset', true);			
		JSubMenuHelper::addEntry( JText::_( 'COM_OZIOGALLERY3_FAQ' ), 'index.php?option=com_oziogallery3&amp;view=faq');	

	}
}
?>