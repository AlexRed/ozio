<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.model');


class OzioModelOzio extends JModel
{

	function __construct()
	{
		parent::__construct();
	}

	function getPubblicate()
	{
	
	
		$query = 'SELECT id, componentid, name, link'
				. ' FROM #__menu'
				. ' WHERE (link = "index.php?option=com_oziogallery2&view=01tiltviewer" 
						OR link = "index.php?option=com_oziogallery2&view=02flashgallery"
						OR link = "index.php?option=com_oziogallery2&view=03imagin"
						OR link = "index.php?option=com_oziogallery2&view=04carousel"
						OR link = "index.php?option=com_oziogallery2&view=05imagerotator"
						OR link = "index.php?option=com_oziogallery2&view=06accordion"	
						OR link = "index.php?option=com_oziogallery2&view=07flickrslidershow"
						OR link = "index.php?option=com_oziogallery2&view=08flickrphoto"							
				)'
				. ' AND published = 1'				
				. ' ORDER BY name DESC'
				;

		$this->_db->SetQuery($query);
  		$genstats = $this->_db->loadObjectList();

  		return $genstats;
	}	
	
	function getNonpubblicate()
	{
	
	
		$query = 'SELECT id, componentid, name, link'
				. ' FROM #__menu'
				. ' WHERE (link = "index.php?option=com_oziogallery2&view=01tiltviewer" 
						OR link = "index.php?option=com_oziogallery2&view=02flashgallery"
						OR link = "index.php?option=com_oziogallery2&view=03imagin"
						OR link = "index.php?option=com_oziogallery2&view=04carousel"
						OR link = "index.php?option=com_oziogallery2&view=05imagerotator"
						OR link = "index.php?option=com_oziogallery2&view=06accordion"	
						OR link = "index.php?option=com_oziogallery2&view=07flickrslidershow"
						OR link = "index.php?option=com_oziogallery2&view=08flickrphoto"							
				)'
				. ' AND published != 1'				
				. ' ORDER BY name DESC'
				;

		$this->_db->SetQuery($query);
  		$genstats2 = $this->_db->loadObjectList();

  		return $genstats2;
	}	
	
}
?>