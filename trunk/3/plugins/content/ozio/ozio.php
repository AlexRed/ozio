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

defined( '_JEXEC' ) or die( 'Restricted access' );

class plgContentOzio extends JPlugin
{
	public function onContentPrepare($context, &$article, &$params, $page = 0)
	{
		$regex		= '/{oziogallery\s+(.*?)}/i';
		$matches	= array();

		// Search for {oziogallery xxx} occurrences
		preg_match_all($regex, $article->text, $matches, PREG_SET_ORDER);

		// If at least one is found, load related javascript only once
		empty($matches) or JFactory::getDocument()->addScript(JURI::root(true) . '/components/com_oziogallery3/assets/js/autoHeight.js');

		// translate {oziogallery xxx} calls into iframe code
		foreach ($matches as $match)
		{
			$output = $this->_load($match[1]);
			$article->text = str_replace($match[0], $output, $article->text);
		}
	}


	protected function _load($galleriaozio)
	{
		// Load the component url from the database
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select($db->quoteName("link"));
		$query->from($db->quoteName("#__menu"));
		$query->where($db->quoteName("id") . " = " . $db->quote($galleriaozio));
		$query->where($db->quoteName("published") . " > " . $db->quote("0"));
		$query->where($db->quoteName("link") . " LIKE " . $db->quote("%com_oziogallery3%"));
		$db->setQuery($query);
		$codice = $db->loadObject();

		// Generate and return the iframe code
		return $codice ?
		'<div class="clr"></div>
		<iframe src="' . JURI::root() . $codice->link .'&Itemid=' . $galleriaozio . '&amp;tmpl=component" width="100%" marginwidth="0px" allowtransparency="true" frameborder="0" scrolling="no" class="autoHeight">
		</iframe>
		<div class="clr"></div>' :
		'';
	}

}