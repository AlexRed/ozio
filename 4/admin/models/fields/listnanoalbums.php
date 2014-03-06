<?php defined("JPATH_BASE") or die();
/**
* This file is part of Ozio Gallery 4.
*
* Ozio Gallery 4 is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 2 of the License, or
* (at your option) any later version.
*
* Ozio Gallery is distributed in the hope that it will be useful,
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

jimport("joomla.form.formfield");

JFormHelper::loadFieldClass("list");
class JFormFieldListNanoAlbums extends JFormFieldList
{
	protected $type = "ListNanoAlbums";

	protected function getInput()
	{
		static $resources = true;
		if ($resources)
		{
			$resources = false;
			$document = JFactory::getDocument();
			$document->addScript(JUri::root(true) . "/components/com_oziogallery3/js/listnanoalbums.js");
		}
		
		return parent::getInput().'<div id="jform_params_ozio_nano_albumList_alert"></div>';
	}
	protected function getOptions()
	{
		$non_printable_separator="\x16";
		$options = array();
		if (!empty($this->value) && is_array($this->value)){
			foreach ($this->value as $v){
				list($id,$title)=explode($non_printable_separator,$v);
				$tmp = JHtml::_('select.option', $v,$title);
	
				$options[] = $tmp;
			}
		}
		return $options;
	}


}
