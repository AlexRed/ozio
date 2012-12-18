<?php
// Doesn't work on Joomla 1.6.3
// defined('JPATH_PLATFORM') or die;
defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('list');

class JFormFieldMenuItemsList extends JFormFieldList
{
	public $type = "MenuItemsList";

	protected function getOptions()
	{
		$name = basename(realpath(dirname(__FILE__) . "/../.."));
		$document = JFactory::getDocument();
		$document->addScript(JURI::base(true) . "/components/" . $name . "/js/chosen.js");
		$document->addStyleSheet(JURI::base(true) . "/components/" . $name . "/css/chosen.css");

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

			$options[] = JHtml::_('select.optgroup', $menu->title);
			foreach ($items as $item)
			{
				$options[] = JHtml::_('select.option', $item->{"id"}, $item->{"title"});
			}
			$options[] = JHtml::_('select.optgroup', $menu->title);

		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
