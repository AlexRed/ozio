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


class OzioViewOzio extends JView
{

	protected $pubblicate;
	protected $nonpubblicate;
	protected $cestinate;

	public function display($tpl = null)
	{
		jimport('joomla.html.pane');
		$pane   = JPane::getInstance('sliders');

		$pubblicate		= $this->get( 'Pubblicate' );
		$nonpubblicate	= $this->get( 'Nonpubblicate' );
		$cestinate		= $this->get( 'Cestinate' );

		$this->assignRef('pane'					, $pane);
		$this->assignRef('pubblicate'			, $pubblicate);
		$this->assignRef('nonpubblicate'		, $nonpubblicate);
		$this->assignRef('cestinate'			, $cestinate);

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

		JToolBarHelper::title( JText::_( 'COM_OZIOGALLERY3_OZIO_GALLERY_3' ),'logo' );
		// Options button
		if (JFactory::getUser()->authorise("core.admin", "com_oziogallery3"))
		{
			JToolBarHelper::preferences("com_oziogallery3");
		}
		JSubMenuHelper::addEntry( JText::_( 'COM_OZIOGALLERY3_OZIOGALLERY_3_-_CPANEL' ), 'index.php?option=com_oziogallery3', true);
		JSubMenuHelper::addEntry( JText::_( 'COM_OZIOGALLERY3_RESET_XML' ), 'index.php?option=com_oziogallery3&amp;view=reset');
		JSubMenuHelper::addEntry( JText::_( 'COM_OZIOGALLERY3_FAQ' ), 'index.php?option=com_oziogallery3&amp;view=faq');

	}
}
