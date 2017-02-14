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
class JFormFieldListGalleries extends JFormFieldList
{
	protected $type = "ListGalleries";

	protected function getInput()
	{
		$name = basename(realpath(dirname(__FILE__) . "/../.."));

		static $resources = true;
		$i18n=array(
			'public'=>JText::_("COM_OZIOGALLERY3_ALBUM_PUBLIC"),
			'private'=>JText::_("COM_OZIOGALLERY3_ALBUM_PRIVATE"),
			'protected'=>JText::_("COM_OZIOGALLERY3_ALBUM_PROTECTED"),
			'topublic'=>JText::_("COM_OZIOGALLERY3_ALBUM_TOPUBLIC"),
			'toprivate'=>JText::_("COM_OZIOGALLERY3_ALBUM_TOPRIVATE"),
			'toprotected'=>JText::_("COM_OZIOGALLERY3_ALBUM_TOPROTECTED"),
		);
		if ($resources)
		{
			$resources = false;
			
			JHtml::_('behavior.framework', true);			
			$document = JFactory::getDocument();
			$prefix = JUri::current() . "?option=" . $name . "&amp;view=loader";

			// pwi
			$document->addScript(JUri::root(true) . "/media/" . $name . "/js/jquery-pwi.js");

			// Alternative code: $type = strtolower($this->type);
			$type = (string)$this->element["type"];

			if (file_exists(JPATH_ADMINISTRATOR . "/components/" . $name . "/js/" . $type . ".js"))
				$document->addScript($prefix . "&amp;type=js&amp;filename=" . $type);

			if (file_exists(JPATH_ADMINISTRATOR . "/components/" . $name . "/css/" . $type . ".css"))
				$document->addStyleSheet(JUri::base(true) . "/components/" . $name . "/css/" . $type . ".css");

			// per la compatibilità con Internet Explorer
			$document->addScript(JURI::root(true) . "/media/" . $name . "/js/jQuery.XDomainRequest.js");

			$document->addScript(JUri::base(true) . "/components/com_oziogallery3/js/get_id.js");
			$document->addScriptDeclaration("var g_ozio_admin_buttons=".json_encode($i18n).";");
			$document->addStyleSheet(JUri::base(true) . "/components/com_oziogallery3/models/fields/fields.css");
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
		$html[] ='			<p><strong>'.JText::_("COM_OZIOGALLERY3_WAIT_GDATA_MSG").'</strong></p></div> ';
		$html[] ='	<table class="table table-striped">';
		$html[] ='	</table>';
		$html[] ='	</div>';
		$html[] ='	<div class="modal-footer">';
		$html[] ='		<button class="btn" data-dismiss="modal" aria-hidden="true">OK</button>';
		$html[] ='	</div>';
		$html[] ='</div>';	

		$buttons .=	implode(" ",$html);			
		
		return
		'<div id="album_selection">' .
		parent::getInput() .$buttons.
		'<img id="jform_params_' . (string)$this->element["name"] . '_loader" style="display:none;" src="' . JUri::root(true) . '/media/' . $name . '/views/00fuerte/img/progress.gif">' .
		'<span id="jform_params_' . (string)$this->element["name"] . '_warning" style="display:none;">' . JText::_("COM_OZIOGALLERY3_ERR_INVALID_USER") . '</span>' .
		'<span id="jform_params_' . (string)$this->element["name"] . '_selected" style="display:none;">' . $this->value . '</span>' .
		'</div>';
	}



}
