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
use Joomla\CMS\HTML\HTMLHelper;

JFormHelper::loadFieldClass("list");
class JFormFieldListNanoAlbums extends JFormFieldList
{
	protected $type = "ListNanoAlbums";

	
	
	protected function getInput()
	{
		static $resources = true;
		$i18n=array(
			'public'=>JText::_("COM_OZIOGALLERY4_ALBUM_PUBLIC"),
			'private'=>JText::_("COM_OZIOGALLERY4_ALBUM_PRIVATE"),
			'protected'=>JText::_("COM_OZIOGALLERY4_ALBUM_PROTECTED"),
			'topublic'=>JText::_("COM_OZIOGALLERY4_ALBUM_TOPUBLIC"),
			'toprivate'=>JText::_("COM_OZIOGALLERY4_ALBUM_TOPRIVATE"),
			'toprotected'=>JText::_("COM_OZIOGALLERY4_ALBUM_TOPROTECTED"),
		);

		if ($resources)
		{
			
			HTMLHelper::_('jquery.framework');
			$resources = false;
			$document = JFactory::getDocument();
			$document->addScriptDeclaration("var g_ozio_picasa_url=".json_encode('index.php?option=com_oziogallery4&view=picasa&format=raw').";");
			$document->addScript(JUri::root(true) . "/media/com_oziogallery4/js/listnanoalbums.js",array('version' => 'auto'));
			$document->addScript(JUri::base(true) . "/components/com_oziogallery4/js/get_id.js",array('version' => 'auto'));
			$document->addScriptDeclaration("var g_ozio_admin_buttons=".json_encode($i18n).";");
			$document->addStyleSheet(JUri::base(true) . "/components/com_oziogallery4/models/fields/fields.css",array('version' => 'auto'));
		}
		
		
		
		
		$buttons = '';
		//$buttons .= '<div class="ozio-buttons-frame">';
		//$buttons .= '<iframe style="margin:0;padding:0;border:0;width:30px;height:22px;overflow:hidden;" src="https://www.opensourcesolutions.es/album_publish_v2.html"></iframe>';
		//$buttons .= '</div>';

		$html=array();
		$html[] ='<div id="oziogallery-modal" class="modal hide fade" >';
		$html[] ='';
		$html[] ='	<div class="modal-header">';
		$html[] ='		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
		$html[] ='		<h3>Ozio Gallery</h3>';
		$html[] ='	</div>';
		$html[] ='	<div class="modal-body" style="overflow-y:auto;">';
		$html[] ='			<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">×</button> ';
		$html[] ='			<p><strong>'.JText::_("COM_OZIOGALLERY4_WAIT_GDATA_MSG").'</strong></p></div> ';
		$html[] ='	<table class="table table-striped">';
		$html[] ='	</table>';
		$html[] ='	</div>';
		$html[] ='	<div class="modal-footer">';
		$html[] ='		<button class="btn" data-dismiss="modal" aria-hidden="true">OK</button>';
		$html[] ='	</div>';
		$html[] ='</div>';	

		$buttons .=	implode(" ",$html);		
		
		
		return parent::getInput().$buttons.'<div id="jform_params_ozio_nano_albumList_alert"></div>';
	}
	protected function getOptions()
	{
		$non_printable_separator="\x16";
		$new_non_printable_separator="|!|";
		$options = array();
		if (!empty($this->value) && is_array($this->value)){
			foreach ($this->value as $v){
				if (strpos($v,$non_printable_separator)!==FALSE){
					list($id,$title)=explode($non_printable_separator,$v);
				}else{
					list($id,$title)=explode($new_non_printable_separator,$v);
				}
				$tmp = JHtml::_('select.option', $v,$title);
	
				$options[] = $tmp;
			}
		}
		return $options;
	}


}
