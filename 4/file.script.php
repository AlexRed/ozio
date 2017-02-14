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

defined( '_JEXEC' ) or die( 'Restricted access' );

class Com_OzioGallery3InstallerScript
{
	protected $component_name;
	protected $extension_name;
	protected $results=array();

	function install($parent)
	{
	
	
		//Inzio attivazione Plugin
		$manifest 	= $parent->get("manifest");
		$parent 	= $parent->getParent();
		$source 	= $parent->getPath("source");

		foreach($manifest->plugins->plugin as $plugin)
		{
			$installer 	= new JInstaller();

			$attributes = $plugin->attributes();
			$plg = $source . "/" . $attributes['folder'] . "/" . $attributes['plugin'];

			$result = array();
			$result["type"] = 'PLUGIN';
			$result["result"] = $installer->install($plg) ? "INSTALLED" : "NOT_INSTALLED";
			$this->results[(string)$attributes["name"]] = $result;
			
			
		}
		foreach($manifest->templates->template as $template)
		{
			$installer 	= new JInstaller();

			$attributes = $template->attributes();
			$tmplt = $source . "/" . $attributes['folder'] . "/" . $attributes['template'];
			
			$result = array();
			$result["type"] = 'TEMPLATE';
			$result["result"] = $installer->install($tmplt) ? "INSTALLED" : "NOT_INSTALLED";
			$this->results['OZIO TEMPLATE'] = $result;
			
			
		}
		
		// If Installscript is running, the component is already installed
		$result = array();
		$result["type"] = "COMPONENT";
		$result["result"] = "INSTALLED";
		$this->results[$this->component_name] = $result;

		// Language files are installed within the single pacakges
		$result = array();
		$result["type"] = "LANGUAGES";
		$result["result"] = "INSTALLED";
		foreach ($this->results as $res){
			if ($res['result']!="INSTALLED"){
				$result["result"]="NOT_INSTALLED";
				break;
			}
		}
		$this->results["Languages"] = $result;
		
		
		$db = JFactory::getDbo();
		$tableExtensions = $db->quoteName("#__extensions");
		$columnEnabled   = $db->quoteName("enabled");
		$columnElement   = $db->quoteName("element");
		$columnType      = $db->quoteName("type");

		// Attiva plugin
		$db->setQuery(
		"UPDATE  $tableExtensions  SET $columnEnabled=1  WHERE $columnElement='ozio'   AND  $columnType='plugin'"
		);
		$db->query();

		$this->message();
	}

	function uninstall($parent)
	{
		
		//Inzio attivazione Plugin
		$manifest 	= $parent->get("manifest");
		$parent 	= $parent->getParent();
		$source 	= $parent->getPath("source");

		$db = JFactory::getDbo();

		foreach($manifest->plugins->plugin as $plugin)
		{
			$installer 	= new JInstaller();

			$attributes = $plugin->attributes();

			//SELECT `extension_id` FROM `#__extensions` WHERE `type` = 'plugin' folder= <group> element =<plugin>

			$query = $db->getQuery(true);
			$query
				->select($db->quoteName(array('extension_id')))
				->from($db->quoteName('#__extensions'))
				->where($db->quoteName('type') . ' = \'plugin\'  AND '.$db->quoteName('folder').' = '.$db->quote($attributes['group']).' AND '.$db->quoteName('element').' = '.$db->quote($attributes['plugin']));

			$db->setQuery($query);
			$id = $db->loadResult();

			$result = array();
			$result["type"] = 'PLUGIN';
			$result["result"] = "NOT_FOUND";
			if ($id){
				$result["result"] = $installer->uninstall('plugin',$id,1) ? "UNINSTALLED" : "NOT_UNINSTALLED";
			}
				
			$this->results[(string)$attributes["name"]] = $result;
		}
		
		
		foreach($manifest->templates->template as $template)
		{
			$installer 	= new JInstaller();

			$attributes = $template->attributes();

			//SELECT extension_id FROM extensions WHERE type = 'template' element =<template>


			$query = $db->getQuery(true);
			$query
				->select($db->quoteName(array('extension_id')))
				->from($db->quoteName('#__extensions'))
				->where($db->quoteName('type') . ' = \'template\'  AND '.$db->quoteName('element').' = '.$db->quote($attributes['template']));

			$db->setQuery($query);
			$id = $db->loadResult();

			$result = array();
			$result["type"] = 'TEMPLATE';
			$result["result"] = "NOT_FOUND";
			if ($id){
				$result["result"] = $installer->uninstall('template',$id,1) ? "UNINSTALLED" : "NOT_UNINSTALLED";
			}
				
			$this->results['OZIO TEMPLATE'] = $result;
			
		}		

		echo '<p>The component Ozio Gallery 4 Component for Joomla 3! has been uninstalled successfully.</p>';
	}


	function update($parent)
	{
		$manifest = $parent->get("manifest");
		$parent = $parent->getParent();
		$source = $parent->getPath("source");

		foreach($manifest->plugins->plugin as $plugin)
		{
			$installer = new JInstaller();

			$attributes = $plugin->attributes();
			$plg = $source . "/" . $attributes['folder'] . "/" . $attributes['plugin'];
			
			$result = array();
			$result["type"] = 'PLUGIN';
			$result["result"] = $installer->install($plg) ? "INSTALLED" : "NOT_INSTALLED";
			$this->results[(string)$attributes["name"]] = $result;
			
			
		}
		foreach($manifest->templates->template as $template)
		{
			$installer 	= new JInstaller();

			$attributes = $template->attributes();
			$tmplt = $source . "/" . $attributes['folder'] . "/" . $attributes['template'];

			$result = array();
			$result["type"] = 'TEMPLATE';
			$result["result"] = $installer->install($tmplt) ? "INSTALLED" : "NOT_INSTALLED";
			$this->results['OZIO TEMPLATE'] = $result;
		}
		
		// If Installscript is running, the component is already installed
		$result = array();
		$result["type"] = "COMPONENT";
		$result["result"] = "INSTALLED";
		$this->results[$this->component_name] = $result;

		// Language files are installed within the single pacakges
		$result = array();
		$result["type"] = "LANGUAGES";
		$result["result"] = "INSTALLED";
		foreach ($this->results as $res){
			if ($res['result']!="INSTALLED"){
				$result["result"]="NOT_INSTALLED";
				break;
			}
		}
		$this->results["Languages"] = $result;
		
		
		$db = JFactory::getDBO();

		// Fixes a Joomla bug, wich adds a second repository rather than overwrite the first one if they are different
		$query = "DELETE FROM `#__update_sites` WHERE `name` = '" . $this->extension_name . " update site';";
		$db->setQuery($query);
		$db->query();

		$query = "DELETE FROM `#__update_sites` WHERE `name` = 'Ozio Gallery3 update site';";
		$db->setQuery($query);
		$db->query();
		
		// Clear updates cache related to this extension
		$query = "DELETE FROM `#__updates` WHERE `name` = '" . $this->extension_name . "';";
		$db->setQuery($query);
		$db->query();

		// Shows the installation/upgrade message
		$this->message();
	}


	function preflight($type, $parent)
	{
	
        $jversion = new JVersion();
 
        // Installing component manifest file version
        $this->release = $parent->get( "manifest" )->version;
 
        // Manifest file minimum Joomla version
        $this->minimum_joomla_release = $parent->get( "manifest" )->attributes()->version;   
 
        // abort if the current Joomla release is older
        if( version_compare( $jversion->getShortVersion(), $this->minimum_joomla_release, 'lt' ) ) {
                Jerror::raiseWarning(null, 'Cannot install Ozio Gallery v4 in a Joomla release prior to '.$this->minimum_joomla_release);
                return false;
        }	
	
	
		$this->component_name = $parent->get("element");
		$this->extension_name = substr($this->component_name, 4);
	}

	function postflight($type, $parent) {
		if ($type == 'uninstall') return true;

		$installed = true;
		foreach ($this->results as $res){
			if ($res['result']!="INSTALLED"){
				$installed = false;
				break;
			}
		}
		if (!$installed){
			return true;
		}
		
		
		$language =JFactory::getLanguage();
		$language_tag = $language->getTag(); // loads the current language-tag
		$language->load('com_oziogallery3', JPATH_ADMINISTRATOR . '/' . "components" . '/' . "com_oziogallery3", $language_tag, true);
		
		//style="width:34%;margin-left:-20%;top:25%;"
		$app = JFactory::getApplication();
		$html=array();			
		$html[] ='<div id="oziogallery-modal" class="modal hide fade" >';
		$html[] ='';
		$html[] ='	<div class="modal-header">';
		$html[] ='		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
		$html[] ='		<h3>Ozio Gallery</h3>';
		$html[] ='	</div>';
		$html[] ='	<div class="modal-body">';
		$html[] ='		<div class="progress progress-success progress-striped">';
		$html[] ='			<div class="bar" style="width: 0;"></div>';
		$html[] ='		</div>';
		//$html[] ='		<p>One fine body…</p>';
		require_once JPATH_SITE . "/components/com_oziogallery3/oziogallery.inc";
		if (!$GLOBALS["oziogallery3"]["registered"])
		{
		$html[] ='			<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">×</button> ';
		$html[] ='				<div style="float:left;margin-right:16px;margin-left:10px;"> ';
		$html[] ='					<a href="http://www.opensourcesolutions.es/ext/ozio-gallery.html" target="_blank"> ';
		$html[] ='						<img src="'.JUri::base(true) . "/components/com_oziogallery3/assets/images/buy_now.jpg".'" border="0" alt="Buy now"> ';
		$html[] ='					</a> ';
		$html[] ='				</div> ';
		$html[] ='			<p><strong>'.JText::_("COM_OZIOGALLERY3_PURCHASE").'</strong></p></div> ';
		}
		$html[] ='	</div>';
		$html[] ='	<div class="modal-footer">';
		$html[] ='		<a href="index.php?option=com_oziogallery3&amp;view=setup" class="btn btn-success">'.JText::_("COM_OZIOGALLERY3_SETUP_LINK").'</a>';

		$html[] ='		<button class="btn" data-dismiss="modal" aria-hidden="true">OK</button>';
		$html[] ='	</div>';
		$html[] ='</div>';
		$html[] ="<script>jQuery('#oziogallery-modal').remove().prependTo('body').modal({keyboard: false});jQuery('#oziogallery-modal .bar').animate({ width: '100%' },1000);</script>";
		$app->enqueueMessage('Installing OzioGallery... '.implode("\n",$html));

		return true;
	}	

	function message()
	{
		echo '<p>Congratulations! Ozio Gallery 4 Component for Joomla 3! has been installed successfully</p>';
		echo "<p><img src=\"https://www.opensourcesolutions.es/logo/responsive4.jpg" . "\"></p>";
		echo "<p>Take a look to 'lightGallery': our new, VIDEO and Photo responsive, adaptive and zoom skin. <a href=\"http://www.opensourcesolutions.es/ext/ozio-gallery.html#Changelog\" target=\"_blank\">Read the new version Changelog.</a></p>";

		echo(
		'<style type="text/css">' .
		'@import url("' . JURI::base(true) . "/components/" . $this->component_name . "/css/install.css" . '");' .
		'</style>' .
		'<img ' .
		'class="install_logo" ' .
		'src="' . JURI::base(true) . "/components/" . $this->component_name . "/css/images/logo.png" . '" '.
		'alt=" Ozio Gallery Logo" ' .
		'/>' .
		'<div class="install_container">' .
		'<div class="install_row">' .
		'<h2 class="install_title">Ozio Gallery</h2>' .
		'</div>');
		
		
		foreach ($this->results as $name => $extension)
		{
			echo(
			'<div class="install_row">' .
			'<div class="install_' . strtolower($extension["type"]) . ' install_icon">' . strtoupper($name) . '</div>' .
			'<div class="install_' . strtolower($extension["result"]) . ' install_icon">' . $extension["result"] . '</div>' .
			'</div>'
			);

		}
		echo('</div>');
		
		
		require_once JPATH_SITE . "/components/com_oziogallery3/oziogallery.inc";
		if (!$GLOBALS["oziogallery3"]["registered"])
		{
			echo "<p>" .
			'
			<div style="float:left;margin-right:16px;margin-left:10px;">
			<a href="http://www.opensourcesolutions.es/ext/ozio-gallery.html" target="_blank">
			<img src="' . JUri::base(true) . '/components/com_oziogallery3/assets/images/buy_now.jpg" border="0" alt="Buy now">
			</a>
			</div>
			<p><strong>This is a non-commercial version of Ozio Gallery. You can continue to use this free version with limitations max 30 photos for album. Remove the signature below the maps and the limitations max 30 photos for album, buying the paid version.</strong></p>
			' . "</p>";
		}
	}

}
