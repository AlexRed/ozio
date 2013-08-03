<?php defined('_JEXEC') or die("Restricted access");
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

<div class="fuertecontainer<?php echo $this->Params->get("pageclass_sfx", ""); ?>" id="fuertecontainer" style="width:<?php echo $this->gallerywidth["text"] . $this->gallerywidth["select"]; ?>; height:0;">

	<!--Thumbnail Navigation-->
	<div id="prevthumb"></div>
	<div id="nextthumb"></div>

	<!--Arrow Navigation-->
	<a id="prevslide" class="load-item"></a>
	<a id="nextslide" class="load-item"></a>

	<!-- Thumbnails
	<div id="thumb-tray" class="load-item">
	<div id="thumb-back"></div>
	<div id="thumb-forward"></div>
	</div>
	-->

	<!--Time Bar-->
	<div id="progress-back" class="load-item">
		<div id="progress-bar"></div>
	</div>

	<?php if ($this->Params->get("show_album", false)) { ?>
		<!-- Top Bar -->
		<div id="oziotopbar" class="load-item oziobar">
			<div id="oziotoptitle" class="oziotitle"></div>
		</div>
		<?php } ?>

	<!-- Bottom Bar -->
	<div id="oziobottombar" class="load-item oziobar">
		<div id="controls">

			<a id="play-button" class="oziobutton" <?php echo $this->play_button_style; ?>><img id="pauseplay" src="<?php echo JURI::base(true); ?>/components/com_oziogallery3/views/00fuerte/img/pause.png" alt="stop"/></a>
			<?php if ($this->Params->get("fullsize_button", true)) { ?>
				<a id="view-button" class="oziobutton"><img src="<?php echo JURI::base(true); ?>/components/com_oziogallery3/views/00fuerte/img/view.png" alt="view"/></a>
				<?php } ?>
			<?php if ($this->Params->get("info_button", false)) { ?>
			<a id="info-button" class="oziobutton"><img src="<?php echo JUri::base(true); ?>/components/com_oziogallery3/views/00fuerte/img/info.png" alt="Info" /></a>
			<?php } ?>

			<!--Slide counter-->
			<div id="slidecounter">
				<span class="slidenumber"></span> / <span class="totalslides"></span>
			</div>

			<?php if ($this->Params->get("show_summary", true)) { ?>
				<div id="slidecaption" class="oziotitle"></div>
				<?php } ?>

			<!--Thumb Tray button-->
			<!-- <a id="tray-button"><img id="tray-arrow" src="img/button-tray-up.png"/></a> -->

			<!--Navigation-->
			<ul id="slide-list"></ul>

		</div>
	</div>

	<div id="supersized-loader"></div>
	<ul id="supersized"></ul>

</div>

<!-- Thumbnails -->
<div id="thumb-tray" class="load-item" style="width:<?php echo $this->gallerywidth["text"] . $this->gallerywidth["select"]; ?>;">
	<div id="thumb-back"></div>
	<div id="thumb-forward"></div>
</div>

<!-- Details -->
<div id="photo-info" style="display:none;">
<div class="details-container">
	<p></p>

		<dl class="dl-horizontal">
			<dt><?php echo JText::_('COM_OZIOGALLERY3_PHOTOINFO_ALBUM_LBL');?></dt><dd class="pi-album"></dd>
			<dt><?php echo JText::_('COM_OZIOGALLERY3_PHOTOINFO_PHOTO_LBL');?></dt><dd class="pi-photo"></dd>
			<dt><?php echo JText::_('COM_OZIOGALLERY3_PHOTOINFO_DATE_LBL');?></dt><dd class="pi-data"></dd>
			<dt><?php echo JText::_('COM_OZIOGALLERY3_PHOTOINFO_DIMENSIONS_LBL');?></dt><dd class="pi-width_height"></dd>
			<dt><?php echo JText::_('COM_OZIOGALLERY3_PHOTOINFO_FILENAME_LBL');?></dt><dd class="pi-file_name"></dd>
			<dt><?php echo JText::_('COM_OZIOGALLERY3_PHOTOINFO_FILESIZE_LBL');?></dt><dd class="pi-file_size"></dd>
			<dt><?php echo JText::_('COM_OZIOGALLERY3_PHOTOINFO_CAMERA_LBL');?></dt><dd class="pi-model"></dd>
			<dt><?php echo JText::_('COM_OZIOGALLERY3_PHOTOINFO_FOCALLENGTH_LBL');?></dt><dd class="pi-focallength"></dd>
			<dt><?php echo JText::_('COM_OZIOGALLERY3_PHOTOINFO_EXPOSURE_LBL');?></dt><dd class="pi-exposure"></dd>
			<dt><?php echo JText::_('COM_OZIOGALLERY3_PHOTOINFO_FSTOP_LBL');?></dt><dd class="pi-fstop"></dd>
			<dt><?php echo JText::_('COM_OZIOGALLERY3_PHOTOINFO_ISO_LBL');?></dt><dd class="pi-iso"></dd>
			<dt><?php echo JText::_('COM_OZIOGALLERY3_PHOTOINFO_CAMERAMAKE_LBL');?></dt><dd class="pi-make"></dd>
			<dt><?php echo JText::_('COM_OZIOGALLERY3_PHOTOINFO_FLASH_LBL');?></dt><dd class="pi-flash"></dd>
			<dt><?php echo JText::_('COM_OZIOGALLERY3_PHOTOINFO_VIEWS_LBL');?></dt><dd class="pi-views"></dd>
			<dt><?php echo JText::_('COM_OZIOGALLERY3_PHOTOINFO_COMMENTS_LBL');?></dt><dd class="pi-comments"></dd>
			<!-- <dt>Visualizzazioni</dt><dd></dd>
			<dt>+1</dt><dd></dd> -->
		</dl>

	<p>
	<?php if ($this->Params->get("albumvisibility", 'public')=='public') { ?>
		<span class="photo-buttons">
		<a href="#" class="btn pi-google" target="_blank">
			<i class="icon-picassa"></i>
			↗ Google+
		</a><br/><br/>
		<a href="" class="btn pi-dowload">
			<i class="icon-download"></i>
			⬇ Download
		</a>
		</span>
	<?php }?>
		<img class="img-polaroid  pi-image" src="" alt="preview" />
	</p>
	<!-- 
	<p>Commenti</p> -->
	<!--
	<p>Mappa</p>
	
	 <iframe class="pi-googlemap" width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe> -->
	
	<p></p>
</div>
<div class="map-container">
</div>	
</div>