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
	function display( $tpl = null )
	{
		$this->params = JFactory::getApplication()->getParams("com_oziogallery3");
		$user = $this->params->get("userid");

		$this->gallerywidth = $this->params->get("gallerywidth", array("text" => "600", "select" => "px"));
		$this->galleryheight = $this->params->get("galleryheight", array("text" => "400", "select" => "px"));

		$album_feed = 'http://picasaweb.google.com/data/feed/api/user/' . $user . '?v=2';
		$photo_feed = 'http://picasaweb.google.com/data/feed/api/user/' . $user . '/albumid/';
		$albums = simplexml_load_file($album_feed);

		//$index = rand(0, count($albums->entry) - 1);
		$index = 1;
		$album = $albums->entry[$index];

		$images = array();
		// get the number of photos for this album
		$photocount = (int) $album->children('http://schemas.google.com/photos/2007')->numphotos;

		// get the ID of the current album
		$album_id = $album->children('http://schemas.google.com/photos/2007')->id;

		// read photo feed for this album into a SimpleXML object
		$photos = simplexml_load_file($photo_feed . $album_id . '?v=2');

		foreach ($photos->entry as $photo)
		{
			$image = array();

			// get the photo and thumbnail information
			$media = $photo->children('http://search.yahoo.com/mrss/');

			// full image information
			$group_content = $media->group->content;

			$url = parse_url((string)$group_content->attributes()->{'url'});
			$relative_path = substr($url["path"], 1);
			$pieces = explode("/", $relative_path);
			$last = count($pieces) - 1;
			$filename = $pieces[$last];

			// Ripiego temporaneo: se espresso in percentuale fissa l'immagine a 960 px
			$width = $this->gallerywidth["select"] == "px" ? $this->gallerywidth["text"] : "960";
			$pieces[$last] = "s" . $width;
			$new_url = $url["scheme"] . "://" . $url["host"] . "/";
			foreach ($pieces as $piece)
			{
				$new_url .= $piece . "/";
			}
			$new_url .= $filename;

			//$image['full']['url'] = (string)$group_content->attributes()->{'url'};
			$image['full']['url'] = $new_url;
			$image['full']['width'] = (string)$group_content->attributes()->{'width'};
			$image['full']['height'] = (string)$group_content->attributes()->{'height'};


			// thumbnail information, get the 3rd (=biggest) thumbnail version
			// change the [2] to [0] or [1] to get smaller thumbnails
			$group_thumbnail = $media->group->thumbnail[2];
			$image['thumbnail']['url'] = (string)$group_thumbnail->attributes()->{'url'};
			$image['thumbnail']['width'] = (string)$group_thumbnail->attributes()->{'width'};
			$image['thumbnail']['height'] = (string)$group_thumbnail->attributes()->{'height'};

			$image['album'] = str_replace("'", "\\'", (string)$album->title);
			$image['summary'] = str_replace("'", "\\'", (string)$photo->summary);

			$images[] = $image;
		}

		$slides = "slides : [";
		foreach ($images as $image)
		{
			$slides .= "{image : '" . $image["full"]["url"] . "', title : '" . $image["album"] . " " . $image["summary"] . "', thumb : '" . $image["thumbnail"]["url"] . "', url : ''},";
		}
		$slides .= "],";

		$slideshow = $this->params->get("slideshow", "1");
		$autoplay = $this->params->get("autoplay", 0);
		$stop_loop = $this->params->get("stop_loop", 0);
		$slide_interval = $this->params->get("slide_interval", 3000);
		$transition = $this->params->get("transition", "slideRight");
		$transition_speed = $this->params->get("transition_speed", "1000");
		$pause_hover = $this->params->get("pause_hover", 0);
		$progress_bar = $this->params->get("progress_bar", 1);

$js = <<<EOT
			jQuery(function($){

				$.supersized({

					// Functionality
					slideshow : $slideshow, // Slideshow on/off
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
					image_protect			:	1,			// Disables image dragging and right click with Javascript

					// Size & Position
					min_width		        :   0,			// Min width allowed (in pixels)
					min_height		        :   0,			// Min height allowed (in pixels)
					vertical_center         :   1,			// Vertically center background
					horizontal_center       :   1,			// Horizontally center background
					fit_always				:	0,			// Image will never exceed browser width or height (Ignores min. dimensions)
					fit_portrait         	:   1,			// Portrait images will not exceed browser height
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
	$document->addScript("https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js");
	$document->addScript(JURI::base(true) . "/components/com_oziogallery3/views/00fuerte/js/jquery.easing.min.js");
	$document->addScript(JURI::base(true) . "/components/com_oziogallery3/views/00fuerte/js/supersized.js");
	$prefix = JURI::base(true) . "/index.php?option=com_oziogallery3&amp;view=loader";
	$menu = JFactory::getApplication()->getMenu();
	$itemid = $menu->getActive() or $itemid = $menu->getDefault();
	$itemid = "&amp;Itemid=" . $itemid->id;
	$document->addScript($prefix . "&amp;type=js&amp;filename=shutter" . $itemid);
	$document->addScriptDeclaration($js);
	$document->addScript(JURI::base(true) . "/components/com_oziogallery3/views/00fuerte/js/jquery-ui-1.7.2.js");
	$document->addScript(JURI::base(true) . "/components/com_oziogallery3/views/00fuerte/js/jquery.ba-bbq.js");
	$document->addScript(JURI::base(true) . "/components/com_oziogallery3/views/00fuerte/js/barbecue.js");

		parent::display($tpl);
	}
}
?>
