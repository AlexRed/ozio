<?php defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class OzioModelGalleries extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array
			(
			'id', 'a.id',
			'title', 'a.title',
			'menutype', 'a.menutype',
			'link', 'a.link',
			'published', 'a.published'
			);
		}

		parent::__construct($config);
	}


	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication();
		$session = JFactory::getSession();

		// Adjust the context to support modal layouts.
		if ($layout = JRequest::getVar('layout'))
		{
			$this->context .= '.'.$layout;
		}

		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $this->getUserStateFromRequest($this->context.'.published', 'filter_published', '');
		$this->setState('filter.published', $published);

		// List state information.
		parent::populateState('a.title', 'asc');
	}


	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
		$id	.= ':'.$this->getState('filter.published');

		return parent::getStoreId($id);
	}


	protected function getListQuery()
	{
		// Create a new query object.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
		$user	= JFactory::getUser();

		// Query on the menu items
		// Warning: both table #__menu and #__menu_types have a field named title, so the first has been renamed in "itemtitle" (see below).
		$query->select(
		$this->getState(
		'list.select',
		'a.id, a.title as itemtitle, a.menutype, a.link, a.published, t.title'
		)
		);
		$query->from('#__menu AS a');

		// Join with the menu type to know the menu name.
		// This join automatically removes the backend items that are not associated to a frontend menu
		$query->join('INNER', '#__menu_types AS t ON a.menutype = t.menutype');

		// Filter on the extension name
		$query->where("a.link LIKE '%option=com_oziogallery3&view=%' ");

		// Filter by search in title.
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			$search = $db->Quote('%' . $db->escape($search, true) . '%');
			$query->where('(a.title LIKE ' . $search . ' OR a.alias LIKE ' . $search . ')');
		}

		// Filter on the published state.
		$published = $this->getState('filter.published');
		if (is_numeric($published))
		{
			$query->where('a.published = ' . (int)$published);
		}
		// Otherwise there is no state select.
		// Our default is to show all states, while Joomla defaults to 0 || 1
		/*
		elseif ($published === '')
		{
		$query->where('(a.published IN (0, 1))');
		}
		*/

		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering', 'a.title');
		$orderDirn	= $this->state->get('list.direction', 'asc');
		$query->order($db->escape($orderCol.' '.$orderDirn));

		return $query;
	}


	public function getItems()
	{
		$items	= parent::getItems();
		$app	= JFactory::getApplication();
		if ($app->isSite())
		{
			$user	= JFactory::getUser();
			$groups	= $user->getAuthorisedViewLevels();

			for ($x = 0, $count = count($items); $x < $count; $x++)
			{
				//Check the access level. Remove articles the user shouldn't see
				if (!in_array($items[$x]->access, $groups))
				{
					unset($items[$x]);
				}
			}
		}
		return $items;
	}
}
