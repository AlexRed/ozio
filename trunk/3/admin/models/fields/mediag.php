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

defined('JPATH_BASE') or die;

require_once realpath(dirname(__FILE__)) . DS . "xmlcleaner.php";

class JFormFieldMediag extends OSSXMLCleaner
{
	protected $type = 'Mediag';
	protected $dir_name = "mediagallery"; // It would be better if $dir_name and $type were the same thing

	protected function getInput()
	{

		return '<div style="padding:5px 5px 5px 0; "><p style="font-size:98%; color:#666; padding: 10px 5px 0px 2px;"><br />' .
				JText::_('COM_OZIOGALLERY3_OZIO_MEDIAGALLERY_SKIN_DESC').
				'</p></div>';
	}
}
