<?php
/**
* This file is part of Ozio Gallery 4.
*
* Ozio Gallery 4 is free software: you can redistribute it and/or modify
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

class OzioViewSetup extends JViewLegacy
{

	public function display($tpl = null)
	{
		$this->addToolbar();
		
		$document = JFactory::getDocument();
		JHtml::_('bootstrap.framework');
		
		$document->addScript(JURI::base(true) . "/components/com_oziogallery4/js/oziosetup.js",array('version' => 'auto'));
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}	
	
	
	protected function addToolbar()
	{
		$document	= JFactory::getDocument();
		$document->addStyleSheet('components/com_oziogallery4/assets/css/default.css',array('version' => 'auto'));
		
		JToolBarHelper::title( JText::_( 'COM_OZIOGALLERY4_OZIO_GALLERY_3' ). ' - ' .JText::_( 'COM_OZIOGALLERY4_SETUP' ),'camera' );
		JHtmlSidebar::addEntry( JText::_( 'COM_OZIOGALLERY4_OZIOGALLERY_3_-_CPANEL' ), 'index.php?option=com_oziogallery4');
		JHtmlSidebar::addEntry( JText::_( 'COM_OZIOGALLERY4_SETUP' ), 'index.php?option=com_oziogallery4&view=setup', true);
		JHtmlSidebar::addEntry( JText::_( 'COM_OZIOGALLERY4_STATISTICS' ), 'index.php?option=com_oziogallery4&view=statistics');
		JHtmlSidebar::addEntry( JText::_( 'COM_OZIOGALLERY4_FAQ' ), 'index.php?option=com_oziogallery4&view=faq');

	}
}

?>