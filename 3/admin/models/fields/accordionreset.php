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

defined('JPATH_BASE') or die();

class JFormFieldAccordionreset extends JFormField
{
	public $type = 'Accordionreset';
	
	protected function getInput()
	{
		JHTML::_('behavior.modal', 'a.modal');
		$link = 'index.php?option=com_oziogallery3&amp;task=resetAcc&amp;tmpl=component';

		$html = "<div class=\"button2-left\"><div class=\"blank\"><a class=\"modal\" title=\"".JText::_('Resetta XML')."\"  href=\"$link\" rel=\"{handler: 'iframe', size: {x: 450, y: 115}}\">".JText::_('Resetta XML')."</a></div></div>\n";
		
		return $html;
	}
}