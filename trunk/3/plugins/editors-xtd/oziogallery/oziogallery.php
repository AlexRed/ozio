<?php defined('_JEXEC') or die;
/**
* This file is part of Ozio Gallery
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

class plgButtonOziogallery extends JPlugin
{
	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}


	function onDisplay($name)
	{
		require_once JPATH_SITE . "/components/com_oziogallery3/oziogallery.inc";

		$js = "
		function oziofunction(menu_id) {
			var tag = '{oziogallery ' + menu_id + '}';
			jInsertEditorText(tag, '$name');
			SqueezeBox.close();
		}";

		$style = "";
		$postfix = "";
		if (!$GLOBALS["oziogallery3"]["registered"])
		{
			$style = ".button2-left .oziogallery a { color: #f03030; }";
			$postfix = " (Unregistered)";
		}
		$document = JFactory::getDocument();
		$document->addScriptDeclaration($js);
		$document->addStyleSheet(JURI::root(true) . "/plugins/" . $this->_type . "/" . $this->_name . "/css/style.css");
		$document->addStyleDeclaration($style);
		JHtml::_('behavior.modal');

		$button = new JObject();
		$button->set('modal', true);
		$button->set('link', 'index.php?option=com_oziogallery3&amp;view=galleries&amp;layout=modal&amp;tmpl=component&amp;function=oziofunction');
		$button->set('text', JText::_('BTN_OZIOGALLERY_BUTTON_LABEL') . $postfix);
		$button->set('name', 'oziogallery');
		$button->set('options', "{handler: 'iframe', size: {x: 770, y: 400}}");

		return $button;
	}
}
