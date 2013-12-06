<?php
defined('_JEXEC') or die();

jimport('joomla.form.formfield');

class JFormFieldMarkerpreview extends JFormField
{
	public $type = 'Markerpreview';

	protected function getInput()
	{
		$html   = '<div style="float:left">';
		$html .= '<img id="ozio_markerpreview" src="" />';
		$html .= '</div>';

		return $html;
		
	}
}
