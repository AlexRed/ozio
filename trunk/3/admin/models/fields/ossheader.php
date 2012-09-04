<?php defined('JPATH_PLATFORM') or die();
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

jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldOSSHeader extends JFormField
{
	protected $type = 'OSSHeader';

	protected function getInput()
	{
		return '';
	}

	protected function getLabel()
	{
		$name = basename(realpath(dirname(__FILE__) . DS . '..' . DS . '..'));
		$direction = intval(JFactory::getLanguage()->get('rtl', 0));
		$left  = $direction ? "right" : "left";
		$right = $direction ? "left" : "right";

		echo '<div class="clr"></div>';
		$image = '';
		$icon	= (string)$this->element['icon'];
		if (!empty($icon))
		{
			$image .= '<img style="margin:0; float:' . $left . ';" src="' . JURI::base(true) . '/components/' . $name . '/models/images/' . $icon . '">';
		}

		$style = 'background:#f4f4f4; color:#025a8d; border:1px solid silver; padding:5px; margin:5px 0;';
		if ($this->element['default'])
		{
			return '<div style="' . $style . '">' .
			$image .
			'<span style="padding-' . $left . ':5px; font-weight:bold; line-height:16px;">' .
			JText::_($this->element['default']) .
			'</span>' .
			'</div>';
		}
		else
		{
			return parent::getLabel();
		}

		echo '<div class="clr"></div>';
	}
}

