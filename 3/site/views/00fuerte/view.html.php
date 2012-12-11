<?php
	/**
	* This file is part of Ozio Gallery 3
	*
	* Ozio Gallery 3 is free software: you can redistribute it and/or modify
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
	* along with Ozio Gallery.  If not, see <http://www.gnu.org/licenses/>.
	*
	* @copyright Copyright (C) 2010 Open Source Solutions S.L.U. All rights reserved.
	* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see RT-LICENSE.php
	*/

	defined( '_JEXEC' ) or die( 'Restricted access' );
	jimport( 'joomla.application.component.view');

	class OzioGalleryView00Fuerte extends JView
	{
		protected $Params;

		function display($tpl = null)
		{
			// SqueezeBox (e' una richiesta ajax cross-domain quindi non funziona. Usare Lightbox)
			// JHTML::_("behavior.modal");

			$this->Params = JFactory::getApplication()->getParams("com_oziogallery3");
			$user = $this->Params->get("userid");

            // Set Meta Description
            if ($description = $this->Params->get('menu-meta_description'))
                $this->document->setDescription($description);
            // Set Meta Keywords
            if ($keywords = $this->Params->get('menu-meta_keywords'))
                $this->document->setMetadata('keywords', $keywords);
            // Set robots (index, follow)
            if ($robots = $this->Params->get('robots'))
                $this->document->setMetadata('robots', $robots);

			$images = array();

			$albumid = $this->Params->get("gallery_id");
			$authcode = "";
			if ($this->Params->get("albumvisibility") == "limited")
			{
				$albumid = $this->Params->get("limitedalbum");
				$authcode = "&authkey=Gv1sRg" . $this->Params->get("limitedpassword");
			}

			// Old identifier: the album number
			$feed = "http://picasaweb.google.com/data/feed/api/user/" . $user . "/albumid/" . $albumid . "?v=2" . $authcode;
			// New identifier: the album unique name
			//$feed = "http://picasaweb.google.com/data/feed/api/user/" . $user . "/album/" . $albumid . "?v=2" . $authcode;
			$photos = simplexml_load_file($feed) or
			$photos = new SimpleXMLElement(file_get_contents(JPATH_COMPONENT . "/views/00fuerte/empty.xml"));

			foreach ($photos->entry as $photo)
			{
				$image = array();

				$url = parse_url((string)$photo->content->attributes()->{"src"});

				$relative_path = substr($url["path"], 1);
				$pieces = explode("/", $relative_path);
				$last = count($pieces) - 1;
				unset($pieces[$last]);

				$image["seed"] = $url["scheme"] ? $url["scheme"] . "://" . $url["host"] . "/" . implode("/", $pieces) . "/" : JURI::root() . implode("/", $pieces) . "/empty.png";
				$image["album"] = str_replace("'", "\\'", (string)$photos->title);
				$image["summary"] = str_replace("'", "\\'", (string)$photo->summary);

				$gphoto = $photo->children('gphoto', true);
				$image["width"] = (string)$gphoto->width;
				$image["height"] = (string)$gphoto->height;

				// Avoids divisions by 0
				if ($image["width"]) $image["ratio"] = $image["height"] / $image["width"];
				else $image["ratio"] = 1;

				$images[] = $image;
			}

			$slides = "slides : [";
			$separator = "";
			foreach ($images as $image)
			{
				$slides .= $separator . "{ " .
				"seed : '" . $image["seed"] . "', " .
				"width : '" . $image["width"] . "', " .
				"height : '" . $image["height"] . "', " .
				"ratio : '" . $image["ratio"] . "', " .
				"album : '" . JText::_($image["album"]) . "', " .
				"summary : '" . JText::_($image['summary']) . "'" .
				" }";
				$separator = ",";
			}
			$slides .= "],";
			$slides = str_replace(array("\r\n", "\r", "\n"), " ", $slides);

			$autoplay = $this->Params->get("autoplay", 0);
			$stop_loop = $this->Params->get("stop_loop", 0);
			$slide_interval = $this->Params->get("slide_interval", 3000);
			$transition = $this->Params->get("transition", "slideRight");
			$transition_speed = $this->Params->get("transition_speed", "1000");
			$pause_hover = $this->Params->get("pause_hover", 0);
			$progress_bar = $this->Params->get("progress_bar", 1);
			$image_protect = $this->Params->get("image_protect", 1);

			$js = <<<EOT
			jQuery(function($){

				$.supersized({

					// Functionality
					slideshow : 1, // Slideshow on/off
					autoplay : $autoplay, // Slideshow starts playing automatically
					start_slide             :   1,			// Start slide (0 is random)
					stop_loop : $stop_loop, // Pauses slideshow on last slide
					random					: 	0,			// Randomize slide order (Ignores start slide)
					slide_interval : $slide_interval, // Length between transitions
					transition : '$transition', // 0-None, 1-Fade, 2-Slide Top, 3-Slide Right, 4-Slide Bottom, 5-Slide Left, 6-Carousel Right, 7-Carousel Left
					transition_speed : $transition_speed, // Speed of transition
					new_window				:	1,			// Image links open in new window/tab
					pause_hover : $pause_hover, // Pause slideshow on hover
					keyboard_nav            :   1,			// Keyboard navigation on/off
					performance				:	1,			// 0-Normal, 1-Hybrid speed/quality, 2-Optimizes image quality, 3-Optimizes transition speed // (Only works for Firefox/IE, not Webkit)
					image_protect			:	$image_protect,			// Disables image dragging and right click with Javascript

					// Size & Position
					min_width		        :   0,			// Min width allowed (in pixels)
					min_height		        :   0,			// Min height allowed (in pixels)
					vertical_center         :   0,			// Vertically center background
					horizontal_center       :   0,			// Horizontally center background
					fit_always				:	1,			// Image will never exceed browser width or height (Ignores min. dimensions)
					fit_portrait         	:   0,			// Portrait images will not exceed browser height
					fit_landscape			:   0,			// Landscape images will not exceed browser width

					// Components
					slide_links				:	'blank',	// Individual links for each slide (Options: false, 'num', 'name', 'blank')
					thumb_links				:	1,			// Individual thumb links for each slide
					thumbnail_navigation    :   0,			// Thumbnail navigation
					$slides
					// Theme Options
					progress_bar : $progress_bar, // Timer for each slide
					mouse_scrub				:	0

				});
		    });
EOT;

			$document = JFactory::getDocument();
			$document->addStyleSheet(JURI::base(true) . "/components/com_oziogallery3/views/00fuerte/css/supersized.css");
			$document->addStyleSheet(JURI::base(true) . "/components/com_oziogallery3/views/00fuerte/theme/supersized.shutter.css");

			if ($this->Params->get("jquery", 1))
				// protocol: https, location: googleapis,
				$document->addScript("https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.js");
			// the ordering of MooTools and jQuery does not matter if you make sure jQuery.noConflict() is called immediately after jQuery is loaded (http://www.designvsdevelop.com/jquery-in-joomla-i-was-wrong/)
			$document->addScript(JURI::base(true) . "/components/com_oziogallery3/views/00fuerte/js/supersized.js");
			// Solo per l'effetto easeOutExpo
			$document->addScript(JURI::base(true) . "/components/com_oziogallery3/views/00fuerte/js/jquery.easing.min.js");
			$prefix = JURI::base(true) . "/index.php?option=com_oziogallery3&amp;view=loader";
			$menu = JFactory::getApplication()->getMenu();
			$itemid = $menu->getActive() or $itemid = $menu->getDefault();
			$itemid = "&amp;Itemid=" . $itemid->id;
			$document->addScript($prefix . "&amp;type=js&amp;filename=shutter" . $itemid);
			$document->addScript($prefix . "&amp;type=js&amp;filename=tinybox" . $itemid);
			$document->addScriptDeclaration($js);
			$document->addScript(JURI::base(true) . "/components/com_oziogallery3/views/00fuerte/js/jquery.ba-bbq.js");

			$this->gallerywidth = $this->Params->get("gallerywidth", array("text" => "100", "select" => "%"));
			$this->play_button_style = $this->Params->get("play_button", "0") ? '' : 'style="display:none;"';

			parent::display($tpl);
		}
	}
?>
