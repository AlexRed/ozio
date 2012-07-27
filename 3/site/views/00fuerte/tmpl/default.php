<?php defined("_JEXEC") or die("Restricted access");
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
?>

<div class="fuertecontainer" style="width:<?php echo $this->gallerywidth["text"] . $this->gallerywidth["select"]; ?>; height:<?php echo $this->galleryheight["text"] . $this->galleryheight["select"]; ?>;">

<!--Thumbnail Navigation-->
<div id="prevthumb"></div>
<div id="nextthumb"></div>

<!--Arrow Navigation-->
<a id="prevslide" class="load-item"></a>
<a id="nextslide" class="load-item"></a>

<div id="thumb-tray" class="load-item">
	<div id="thumb-back"></div>
	<div id="thumb-forward"></div>
</div>

<!--Time Bar-->
<div id="progress-back" class="load-item">
	<div id="progress-bar"></div>
</div>

<!--Control Bar -->
<div id="controls-wrapper" class="load-item">
	<div id="controls">

		<a id="play-button"><img id="pauseplay" src="<?php echo JURI::base(true); ?>/components/com_oziogallery3/views/00fuerte/img/pause.png"/></a>

		<!--Slide counter-->
		<div id="slidecounter">
			<span class="slidenumber"></span> / <span class="totalslides"></span>
		</div>

		<!--Slide captions displayed here-->
		<div id="slidecaption"></div>

		<!--Thumb Tray button-->
		<a id="tray-button"><img id="tray-arrow" src="img/button-tray-up.png"/></a>

		<!--Navigation-->
		<ul id="slide-list"></ul>

	</div>
	</div>

<div id="supersized-loader"></div>
<ul id="supersized"></ul>

</div>