<?php
/**
* This file is part of Ozio Gallery 4.
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

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view');

class OzioViewSetup_Auth extends JViewLegacy
{

	public function display($tpl = null)
	{
		//tmpl=component&credentials_id
		$document = JFactory::getDocument();
		JHtml::_('bootstrap.framework');
		
		$app = JFactory::getApplication();
		$jinput = $app->input;
		
		
		$credentials_id = $jinput->get('credentials_id', 0, 'INT');
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('id','client_id')));
		$query->from($db->quoteName('#__ozio_setup'));
		$query->where($db->quoteName('id') . ' = '. $db->quote($credentials_id));	
		$db->setQuery($query);
			
		$credentials = $db->loadAssoc();
		if (empty($credentials)){
			die();
		}
		
		
		
		$document->addScript(JURI::base(true) . "/components/com_oziogallery3/js/oziosetup_auth.js");
		$document->addScript("https://apis.google.com/js/client:platform.js?onload=ozio_setup_auth","text/javascript",true,true);// async defer
		$document->addScriptDeclaration('var ozio_google_client_id='.json_encode($credentials['client_id']).';');
		$document->addScriptDeclaration('var ozio_credentials_id='.json_encode($credentials['id']).';');
		
		parent::display($tpl);
	}	
	
}

?>