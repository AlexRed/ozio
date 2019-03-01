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


class OzioViewOzio extends JViewLegacy
{

	protected $pubblicate;
	protected $nonpubblicate;
	protected $cestinate;

	public function display($tpl = null)
	{
		jimport('joomla.html.pane');
/*
		$pubblicate		= $this->get( 'Pubblicate' );
		$nonpubblicate	= $this->get( 'Nonpubblicate' );
		$cestinate		= $this->get( 'Cestinate' );

		$this->assignRef('pubblicate'			, $pubblicate);
		$this->assignRef('nonpubblicate'		, $nonpubblicate);
		$this->assignRef('cestinate'			, $cestinate);
*/
		$this->addToolbar();
		$document = JFactory::getDocument();
		//$document->addScript("https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.js");
		//$document->addScript(JURI::root(true) . "/components/com_oziogallery3/js/jquery-noconflict.js");
		//$document->addScript(JURI::base(true) . "/media/jui/js/jquery.min.js");
		//$document->addScript(JURI::base(true) . "/media/jui/js/jquery-noconflict.js");
		//JHtml::_('jquery.framework');
		JHtml::_('bootstrap.framework');

		$document->addScript(JURI::root(true) . "/media/com_oziogallery3/js/jquery-pwi.js",array('version' => 'auto'));
		$document->addScript(JURI::root(true) . "/media/com_oziogallery3/js/jQuery.XDomainRequest.js",array('version' => 'auto'));
		$document->addScript(JURI::base(true) . "/components/com_oziogallery3/js/oziostatistics.js",array('version' => 'auto'));
		
		parent::display($tpl);
	}


	protected function addToolbar()
	{
		$document	= JFactory::getDocument();
		$document->addStyleSheet('components/com_oziogallery3/assets/css/default.css',array('version' => 'auto'));

		JToolBarHelper::title( JText::_( 'COM_OZIOGALLERY3_OZIO_GALLERY_3' ),'camera' );
		// Options button
		if (JFactory::getUser()->authorise("core.admin", "com_oziogallery3"))
		{
			JToolBarHelper::preferences("com_oziogallery3");
		}
		JSubMenuHelper::addEntry( JText::_( 'COM_OZIOGALLERY3_OZIOGALLERY_3_-_CPANEL' ), 'index.php?option=com_oziogallery3', true);
		JSubMenuHelper::addEntry( JText::_( 'COM_OZIOGALLERY3_SETUP' ), 'index.php?option=com_oziogallery3&amp;view=setup');
		JSubMenuHelper::addEntry( JText::_( 'COM_OZIOGALLERY3_STATISTICS' ), 'index.php?option=com_oziogallery3&amp;view=statistics');
		JSubMenuHelper::addEntry( JText::_( 'COM_OZIOGALLERY3_FAQ' ), 'index.php?option=com_oziogallery3&amp;view=faq');

	}
}
