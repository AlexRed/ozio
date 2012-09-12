<?php defined('_JEXEC') or die;

class plgButtonOziogallery extends JPlugin
{
	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}


	function onDisplay($name)
	{
		$js = "
		function oziofunction(menu_id) {
			var tag = '{oziogallery ' + menu_id + '}';
			jInsertEditorText(tag, '$name');
			SqueezeBox.close();
		}";

		$document = JFactory::getDocument();
		$document->addScriptDeclaration($js);
		$document->addStyleSheet(JURI::root(true) . "/plugins/" . $this->_type . "/" . $this->_name . "/css/style.css");
		JHtml::_('behavior.modal');

		$button = new JObject();
		$button->set('modal', true);
		$button->set('link', 'index.php?option=com_oziogallery3&amp;view=galleries&amp;layout=modal&amp;tmpl=component&amp;function=oziofunction');
		$button->set('text', JText::_('BTN_OZIOGALLERY_BUTTON_LABEL'));
		$button->set('name', 'oziogallery');
		$button->set('options', "{handler: 'iframe', size: {x: 770, y: 400}}");

		return $button;
	}
}
