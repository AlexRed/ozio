<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * OzioColor Form Field class for the Joomla Platform.
 * This implementation is designed to be compatible with HTML5's <input type="color">
 *
 * @link   http://www.w3.org/TR/html-markup/input.color.html
 * @since  11.3
 */
class JFormFieldOzioColor extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.3
	 */
	protected $type = 'OzioColor';

	/**
	 * The control.
	 *
	 * @var    mixed
	 * @since  3.2
	 */
	protected $control = 'hue';

	/**
	 * The position.
	 *
	 * @var    mixed
	 * @since  3.2
	 */
	protected $position = 'default';

	/**
	 * The keywords.
	 *
	 * @var    string
	 * @since  3.5
	 */
	protected $keywords = '';

	/**
	 * The alpha.
	 *
	 * @var    boolean
	 * @since  3.5
	 */
	protected $alpha = false;

	/**
	 * The colors.
	 *
	 * @var    mixed
	 * @since  3.2
	 */
	protected $colors;

	/**
	 * The split.
	 *
	 * @var    integer
	 * @since  3.2
	 */
	protected $split = 3;

	/**
	 * Method to get certain otherwise inaccessible properties from the form field object.
	 *
	 * @param   string  $name  The property name for which to the the value.
	 *
	 * @return  mixed  The property value or null.
	 *
	 * @since   3.2
	 */
	public function __get($name)
	{
		switch ($name)
		{
			case 'control':
			case 'keywords':
			case 'alpha':
			case 'exclude':
			case 'colors':
			case 'split':
				return $this->$name;
		}

		return parent::__get($name);
	}

	/**
	 * Method to set certain otherwise inaccessible properties of the form field object.
	 *
	 * @param   string  $name   The property name for which to the the value.
	 * @param   mixed   $value  The value of the property.
	 *
	 * @return  void
	 *
	 * @since   3.2
	 */
	public function __set($name, $value)
	{
		switch ($name)
		{
			case 'split':
				$value = (int) $value;
			case 'control':
			case 'keywords':
				$this->$name = (string) $value;
				break;
			case 'alpha':
			case 'exclude':
			case 'colors':
				$this->$name = (string) $value;
				break;

			default:
				parent::__set($name, $value);
		}
	}

	/**
	 * Method to attach a JForm object to the field.
	 *
	 * @param   SimpleXMLElement  $element  The SimpleXMLElement object representing the <field /> tag for the form field object.
	 * @param   mixed             $value    The form field value to validate.
	 * @param   string            $group    The field name group control value. This acts as as an array container for the field.
	 *                                      For example if the field has name="foo" and the group value is set to "bar" then the
	 *                                      full field name would end up being "bar[foo]".
	 *
	 * @return  boolean  True on success.
	 *
	 * @see     JFormField::setup()
	 * @since   3.2
	 */
	public function setup(SimpleXMLElement $element, $value, $group = null)
	{
		$return = parent::setup($element, $value, $group);

		if ($return)
		{
			$this->control	= isset($this->element['control']) ? (string) $this->element['control'] : 'hue';
			$this->position	= isset($this->element['position']) ? (string) $this->element['position'] : 'default';
			$this->keywords	= isset($this->element['keywords']) ? (string) $this->element['keywords'] : '';
			$this->alpha	= isset($this->element['alpha']) ? $this->element['alpha'] : false;
			$this->colors	= (string) $this->element['colors'];
			$this->split	= isset($this->element['split']) ? (int) $this->element['split'] : 3;
		}

		return $return;
	}

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.3
	 */
	protected function getInput()
	{
		// Translate placeholder text
		$hint = $this->translateHint ? JText::_($this->hint) : $this->hint;

		// Control value can be: hue (default), saturation, brightness, wheel or simple
		$control = $this->control;

		// Position of the panel can be: right (default), left, top or bottom (default rtl is left)
		$position = ' data-position="' . $this->position . '"';

		// Validation of data can be: color (hex color value)
		$validate = $this->validate ? ' data-validate="' . $this->validate . '"' : '';

		$onchange  = ! empty($this->onchange) ? ' onchange="' . $this->onchange . '"' : '';
		$class     = $this->class;
		$required  = $this->required ? ' required aria-required="true"' : '';
		$disabled  = $this->disabled ? ' disabled' : '';
		$autofocus = $this->autofocus ? ' autofocus' : '';

		$color = strtolower($this->value);
		$color = ! $color ? '' : $color;

		if (in_array($color, array('none', 'transparent')))
		{
			$color = 'transparent';
			$alpha = '0';
		}
		elseif (in_array($color, array('inherit', 'initial')))
		{
			$color = $color;
			$alpha = '1';
		}
		elseif (strpos($color, 'rgba') !== false)
		{
			$color_rgba = str_replace(array('rgba(', ')'), '', $color);
			$rgba_array = explode(',', $color_rgba);

			$hex_R = dechex($rgba_array[0]);
			$hex_G = dechex($rgba_array[1]);
			$hex_B = dechex($rgba_array[2]);
			$alpha = $rgba_array[3];

			$R = (strlen($hex_R) < 2) ? '0' . $hex_R : $hex_R;
			$G = (strlen($hex_G) < 2) ? '0' . $hex_G : $hex_G;
			$B = (strlen($hex_B) < 2) ? '0' . $hex_B : $hex_B;

			$color = '#' . $R . $G . $B;
		}
		elseif ($color && $color['0'] != '#')
		{
			$color = '#' . $color;
		}


		$class			= ' class="' . trim('oziominicolors ' . $class) . '"';
		$control		= $control ? ' data-control="' . $control . '"' : '';
		$readonly		= $this->readonly ? ' readonly' : '';
		$placeholder	= ($this->validate == 'color') ? ' placeholder="#rrggbb"' : ' placeholder="' . JText::_('JSELECT') . '"';
		$hint			= $hint ? ' placeholder="' . $hint . '"' : $placeholder;
		$autocomplete	= ! $this->autocomplete ? ' autocomplete="off"' : '';
		$opacity		= (isset($alpha) && $this->alpha) ? ' data-alpha="true" data-opacity="' . $alpha . '"' : '';
		$alpha			= $this->alpha ? ' data-alpha="true"' : '';
		$keywords		= $this->keywords ? ' data-keywords="' . $this->keywords . '"' : '';

		// Force LTR input value in RTL, due to display issues with rgba/hex colors
		$lang			= JFactory::getLanguage();
		$direction		= $lang->isRTL() ? ' dir="ltr" style="text-align:right"' : '';

		// Including fallback code for HTML5 non supported browsers.
		JHtml::_('jquery.framework');
		JHtml::_('script', 'system/html5fallback.js', false, true);

		static $resources = true;
		if ($resources)
		{
			$resources = false;
		
			$document = JFactory::getDocument();
			$document->addScript(JUri::base(true) . "/components/com_oziogallery3/js/jquery.ozio-minicolors.js",array('version' => 'auto'));
			$document->addStyleSheet(JUri::base(true) . "/components/com_oziogallery3/css/jquery.ozio-minicolors.css",array('version' => 'auto'));
			
			
			JFactory::getDocument()->addScriptDeclaration("
					jQuery(document).ready(function (){
						jQuery('.oziominicolors').each(function() {
							jQuery(this).oziominicolors({
								alpha: jQuery(this).attr('data-alpha') || false,
								control: jQuery(this).attr('data-control') || 'hue',
								keywords: jQuery(this).attr('data-keywords') || '',
								opacity: jQuery(this).attr('data-validate') === 'color' ? false : jQuery(this).attr('data-alpha') || false,
								position: jQuery(this).attr('data-position') || 'default',
								theme: 'bootstrap'
							});
						});
					});
				"
			);
		}
		return '<input type="text" name="' . $this->name . '" id="' . $this->id . '"' . ' value="'
			. htmlspecialchars($color, ENT_COMPAT, 'UTF-8') . '"' . $hint . $class . $position . $control
			. $readonly . $disabled . $required . $onchange . $autocomplete . $autofocus . $validate
			. $alpha . $opacity . $keywords . $direction . '/>';
	}
}
