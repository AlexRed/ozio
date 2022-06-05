<?php defined('_JEXEC') or die("Restricted access");
	/**
	* This file is part of Ozio Gallery 4
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
	* along with Ozio Gallery.  If not, see <http://www.gnu.org/licenses/>.
	*
	* @copyright Copyright (C) 2010 Open Source Solutions S.L.U. All rights reserved.
	* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see RT-LICENSE.php
	*/
	use Joomla\CMS\HTML\HTMLHelper;
	HTMLHelper::_('jquery.framework');
$GLOBALS["enable_jquery_ozio_plugin"]=true;
	
?>
<?php if ($this->Params->get("show_page_heading", 1)) { ?>
<h1><?php echo $this->escape($this->Params->get("page_heading")); ?></h1>
<?php } ?>

<?php 
$testo_sotto_mappa=trim(strip_tags($this->Params->get("bottom_description", ""),'<a><b><blockquote><code><del><dd><dl><dt><em><h1><h2><h3><i><kbd><li><ol><p><pre><s><sup><sub><strong><strike><ul><br><hr>'));
if (!empty($testo_sotto_mappa) && $this->Params->get("description_pos", "under")=='above') {  ?>
	<div class="ozio_00fuerte_bottom_description">
	<?php echo $testo_sotto_mappa; ?>
	</div>
<?php }?>


<div class="fuertecontainer<?php echo $this->escape($this->Params->get("pageclass_sfx", "")); ?>" id="fuertecontainer" style="width:<?php echo $this->escape($this->gallerywidth["text"] . $this->gallerywidth["select"]); ?>; height:0;">

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
	<?php 
	
		$class_progressbar='';
		if ($this->Params->get("hide_bottombar", false)) { 
			if ($this->Params->get("autoplay", 0)){
				$class_progressbar='progress-back-bottom';
			}else{
				$class_progressbar='progress-back-hide';
			}
		}
		
	?>
	<div id="progress-back" class="load-item <?php echo $class_progressbar;?>">
		<div id="progress-bar"></div>
	</div>

	<?php if ($this->Params->get("show_album", false)) { ?>
		<!-- Top Bar -->
		<div id="oziotopbar" class="load-item oziobar">
			<div id="oziotoptitle" class="oziotitle"></div>
		</div>
		<?php } ?>

	<?php if (!$this->Params->get("hide_bottombar", false)) { ?>

	<!-- Bottom Bar -->
	<div id="oziobottombar" class="load-item oziobar">
		<div id="controls">
			<!--Thumb Tray button-->
			<?php if (!$this->Params->get("hide_thumbnails", 0)) { ?>
			 <a id="tray-button"><img id="tray-arrow" src="<?php echo JUri::base(true); ?>/media/com_oziogallery4/views/00fuerte/img/button-tray-up.png"/></a> 
			 <?php } ?>

			<a id="play-button" class="oziobutton" <?php echo $this->play_button_style; ?>><img id="pauseplay" src="<?php echo JUri::base(true); ?>/media/com_oziogallery4/views/00fuerte/img/pause.png" alt="stop" /></a>
			<?php if ($this->Params->get("fullsize_button", true)) { ?>
				<a id="view-button" class="oziobutton" data-loading-gif="<?php echo JUri::base(true); ?>/media/com_oziogallery4/views/00fuerte/img/progress.gif" ><img src="<?php echo JUri::base(true); ?>/media/com_oziogallery4/views/00fuerte/img/view.png" alt="view" /></a>
			<?php } ?>
			<?php if ($this->Params->get("info_button", false)) { ?>
			<a id="info-button" class="oziobutton"><img src="<?php echo JUri::base(true); ?>/media/com_oziogallery4/views/00fuerte/img/info.png" alt="Info" /></a>
			<?php } ?>

			<!--Slide counter-->
			<div id="slidecounter">
				<span class="slidenumber"></span> / <span class="totalslides"></span>
			</div>

			<?php if ($this->Params->get("show_summary", true)) { ?>
				<div id="slidecaption" class="oziotitle"></div>
				<?php } ?>


			<!--Navigation-->
			<ul id="slide-list"></ul>

		</div>
	</div>

	<?php } ?>

	<div id="supersized-loader"></div>
	<ul id="supersized"></ul>

</div>

<!-- Thumbnails -->
<div id="thumb-tray" class="load-item" style="width:<?php echo $this->escape($this->gallerywidth["text"] . $this->gallerywidth["select"]); ?>;">
	<div id="thumb-back"></div>
	<div id="thumb-forward"></div>
</div>


<div id="ozio-pw-nano" class="ozio-pw-nano load-item" style="height:<?php echo $this->escape($this->Params->get("photowall_height", 200));?>px;width:<?php echo $this->escape($this->gallerywidth["text"] . $this->gallerywidth["select"]); ?>;">
 <div class="ozio-pw-content">
	<div id="photo-wall">
	</div>
 </div> 
</div>

<?php 
$testo_sotto_mappa=trim(strip_tags($this->Params->get("bottom_description", ""),'<a><b><blockquote><code><del><dd><dl><dt><em><h1><h2><h3><i><kbd><li><ol><p><pre><s><sup><sub><strong><strike><ul><br><hr>'));
if (!empty($testo_sotto_mappa) && $this->Params->get("description_pos", "under")=='under') {  ?>
	<div class="ozio_00fuerte_bottom_description">
	<?php echo $testo_sotto_mappa; ?>
	</div>
<?php }?>

 
 <?php if ($this->Params->get("info_button", false)==true) { ?>

<div id="photo-info" class="nanoGalleryInfoBox ozio-00fuerte-white-info-box mfp-hide" style="background-image:url(<?php echo json_encode($this->Params->get("infobox_bg_url", "https://lh4.googleusercontent.com/nr01-F6eM6Mb09CuDZBLvnxzpyRMpWQ0amrS593Rb7Q=w1200")); ?>);">
	<div class="ozio-00fuerte-infobox-middle">
		<dl class="odl-horizontal">
			<dt></dt><dd><img class="oimg-polaroid pi-image" alt="preview"/></dd>
			<?php if ($this->Params->get("hide_infobox_album", false)==false) { ?>
			<dt><?php echo JText::_('COM_OZIOGALLERY4_PHOTOINFO_ALBUM_LBL');?></dt><dd class="pi-album"></dd>
			<?php }?>
			<?php if ($this->Params->get("hide_infobox_photo", false)==false) { ?>
			<dt><?php echo JText::_('COM_OZIOGALLERY4_PHOTOINFO_PHOTO_LBL');?></dt><dd class="pi-photo"></dd>
			<?php }?>
			<?php if ($this->Params->get("hide_infobox_date", false)==false) { ?>
			<dt><?php echo JText::_('COM_OZIOGALLERY4_PHOTOINFO_DATE_LBL');?></dt><dd class="pi-data"></dd>
			<?php }?>
			<?php if ($this->Params->get("hide_infobox_width_height", false)==false) { ?>
			<dt><?php echo JText::_('COM_OZIOGALLERY4_PHOTOINFO_DIMENSIONS_LBL');?></dt><dd class="pi-width_height"></dd>
			<?php }?>
			<?php if ($this->Params->get("hide_infobox_file_name", false)==false) { ?>
			<dt><?php echo JText::_('COM_OZIOGALLERY4_PHOTOINFO_FILENAME_LBL');?></dt><dd class="pi-file_name"></dd>
			<?php }?>
			<?php if ($this->Params->get("hide_infobox_file_size", false)==false) { ?>
			<dt><?php echo JText::_('COM_OZIOGALLERY4_PHOTOINFO_FILESIZE_LBL');?></dt><dd class="pi-file_size"></dd>
			<?php }?>
			<?php if ($this->Params->get("hide_infobox_model", false)==false) { ?>
			<dt><?php echo JText::_('COM_OZIOGALLERY4_PHOTOINFO_CAMERA_LBL');?></dt><dd class="pi-model"></dd>
			<?php }?>
			<?php if ($this->Params->get("hide_infobox_focallength", false)==false) { ?>
			<dt><?php echo JText::_('COM_OZIOGALLERY4_PHOTOINFO_FOCALLENGTH_LBL');?></dt><dd class="pi-focallength"></dd>
			<?php }?>
			<?php if ($this->Params->get("hide_infobox_exposure", false)==false) { ?>
			<dt><?php echo JText::_('COM_OZIOGALLERY4_PHOTOINFO_EXPOSURE_LBL');?></dt><dd class="pi-exposure"></dd>
			<?php }?>
			<?php if ($this->Params->get("hide_infobox_fstop", false)==false) { ?>
			<dt><?php echo JText::_('COM_OZIOGALLERY4_PHOTOINFO_FSTOP_LBL');?></dt><dd class="pi-fstop"></dd>
			<?php }?>
			<?php if ($this->Params->get("hide_infobox_iso", false)==false) { ?>
			<dt><?php echo JText::_('COM_OZIOGALLERY4_PHOTOINFO_ISO_LBL');?></dt><dd class="pi-iso"></dd>
			<?php }?>
			<?php if ($this->Params->get("hide_infobox_make", false)==false) { ?>
			<dt><?php echo JText::_('COM_OZIOGALLERY4_PHOTOINFO_CAMERAMAKE_LBL');?></dt><dd class="pi-make"></dd>
			<?php }?>
			<?php if ($this->Params->get("hide_infobox_flash", false)==false) { ?>
			<dt><?php echo JText::_('COM_OZIOGALLERY4_PHOTOINFO_FLASH_LBL');?></dt><dd class="pi-flash"></dd>
			<?php }?>
			<?php if ($this->Params->get("hide_infobox_views", false)==false) { ?>
			<dt><?php echo JText::_('COM_OZIOGALLERY4_PHOTOINFO_VIEWS_LBL');?></dt><dd class="pi-views"></dd>
			<?php }?>
			<?php if ($this->Params->get("hide_infobox_comments", false)==false) { ?>
			<dt><?php echo JText::_('COM_OZIOGALLERY4_PHOTOINFO_COMMENTS_LBL');?></dt><dd class="pi-comments"></dd>
			<?php }?>
			<!-- <dt>Visualizzazioni</dt><dd></dd>
			<dt>+1</dt><dd></dd> -->
		</dl>
	
		<div class="map-container">
		</div>
	
	</div>
	<div class="pi-photo-buttons">
		<?php if ($this->Params->get("hide_infobox_link", false)==false) { ?>
		<a href="#" class="btn pi-google btn-info" target="_blank">↗ Google+</a>
		<?php }?>
		<?php if ($this->Params->get("hide_infobox_download", false)==false) { ?>
		<a href="#" class="btn pi-dowload">⬇ Download</a>
		<?php }?>
    </div>
</div>
<?php }?>    
