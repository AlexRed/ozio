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
		require_once JPATH_SITE . "/components/com_oziogallery3/oziogallery.inc";

		$js = "
		function oziofunction(menu_id) {
			var tag = '{oziogallery ' + menu_id + '}';
			jInsertEditorText(tag, '$name');
			SqueezeBox.close();
		}";

		$style = "";
		$postfix = "";
		if (!$GLOBALS["oziogallery3"]["registered"])
		{
			$style = ".button2-left .oziogallery a { color: #f03030; }";
			$postfix = " (Unregistered)";
		}
		$document = JFactory::getDocument();
		$document->addScriptDeclaration($js);
		$document->addStyleSheet(JURI::root(true) . "/plugins/" . $this->_type . "/" . $this->_name . "/css/style.css");
		$document->addStyleDeclaration($style);
		JHtml::_('behavior.modal');

		$button = new JObject();
		$button->set('modal', true);
		$button->set('link', 'index.php?option=com_oziogallery3&amp;view=galleries&amp;layout=modal&amp;tmpl=component&amp;function=oziofunction');
		$button->set('text', JText::_('BTN_OZIOGALLERY_BUTTON_LABEL') . $postfix);
		$button->set('name', 'oziogallery');
		$button->set('options', "{handler: 'iframe', size: {x: 770, y: 400}}");

		return $button;
	}
}
