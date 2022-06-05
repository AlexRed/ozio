<?php
/**
* This file is part of Ozio Gallery
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
// Doesn't work on Joomla 1.6.3
// defined('JPATH_PLATFORM') or die;

defined('JPATH_BASE') or die;
use Joomla\CMS\HTML;
JFormHelper::loadFieldClass('list');

class JFormFieldMenuItemsList extends JFormFieldList
{
	public $type = "MenuItemsList";

	protected function getOptions()
	{
		// Initialize variables.
		$options = array();
		// Get the database object.
		$db = JFactory::getDBO();

		// Query on the menu types
		$query = $db->getQuery(true);
		$query->select($db->quoteName("menutype") . "," . $db->quoteName("title"));
		$query->from($db->quoteName("#__menu_types"));
		$db->setQuery($query);
		$menus = $db->loadObjectlist() or $menus = new stdClass();

		foreach ($menus as $menu)
		{

			// Recycle
			$query->clear();
			$query->select("id, title, menutype, link, published");
			$query->from('#__menu');
			// Filter on the extension name
			$query->where("link LIKE '%option=" . (string)$this->element["component"] . "&view=%'");
			$query->where("link NOT LIKE '%&view=list%'");
			// Filter on the menu type
			$query->where("menutype" . "=" . $db->quote($menu->menutype));
			// Filter on the published state.
			$query->where("published = 1");

			$query->order($db->quoteName("lft"));

			// Set the query and get the result list.
			$db->setQuery($query);

			$items = $db->loadObjectlist() or $items = new stdClass();

			//$options[] = JHtml::_('select.option', $menu->title);
			foreach ($items as $item)
			{
				$options[] = JHtml::_('select.option', $item->{"id"}, $item->{"title"}." ($menu->title)");
			}
		//	$options[] = JHtml::_('select.option', $menu->title);

		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
