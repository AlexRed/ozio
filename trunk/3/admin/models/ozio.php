<?php
/**
* This file is part of Ozio Gallery 3
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
		$query = 'SELECT i.id, i.component_id, i.title, i.link, i.menutype, men.title AS nomemenu, men.id AS menuid'
		. ' FROM #__menu AS i'
		. ' LEFT JOIN #__menu_types AS men ON men.menutype = i.menutype'
		. " WHERE i.link LIKE '%com_oziogallery3&view=%'"
		. ' AND i.published = 1'
		. ' ORDER BY i.title ASC'
		;

		$this->_db->SetQuery($query);
		$genstats = $this->_db->loadObjectList();

		return $genstats;
	}


	function getNonpubblicate()
	{
		$query = 'SELECT i.id, i.component_id, i.title, i.link, i.menutype, men.title AS nomemenu, men.id AS menuid'
		. ' FROM #__menu AS i'
		. ' LEFT JOIN #__menu_types AS men ON men.menutype = i.menutype'
		. " WHERE i.link LIKE '%com_oziogallery3&view=%'"
		. ' AND published  = 0'
		. ' AND published != -2'
		. ' ORDER BY title ASC'
		;

		$this->_db->SetQuery($query);
		$genstats2 = $this->_db->loadObjectList();

		return $genstats2;
	}


	function getCestinate()
	{
		$query = 'SELECT i.id, i.component_id, i.title, i.link, i.menutype, men.title AS nomemenu, men.id AS menuid'
		. ' FROM #__menu AS i'
		. ' LEFT JOIN #__menu_types AS men ON men.menutype = i.menutype'
		. " WHERE i.link LIKE '%com_oziogallery3&view=%'"
		. ' AND i.published = -2'
		. ' ORDER BY i.title ASC'
		;

		$this->_db->SetQuery($query);
		$genstats3 = $this->_db->loadObjectList();

		return $genstats3;
	}

}
