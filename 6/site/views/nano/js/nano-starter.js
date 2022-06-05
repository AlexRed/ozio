<?php
$non_printable_separator="\x16";
$new_non_printable_separator="|!|";
?>

<?php echo 'var ozmaxres = '.json_encode($GLOBALS["oziogallery3max"]).";\n"; ?>

jQuery( document ).ready(function( $ ) {
	if (typeof ozio_fullscreen != 'undefined'?ozio_fullscreen:0){
		var closelink=<?php $closelink = trim( $this->Params->get("closelink","") ); if (empty($closelink)){$closelink=JURI::base();} echo json_encode($closelink); ?>;
		jQuery('a.close_fullscreen').attr('href',closelink);
		jQuery('a.close_fullscreen').css('left','15px');
		jQuery('a.close_fullscreen').css('right','auto');
	}


	var infobox_bg_url = <?php echo json_encode($this->Params->get("infobox_bg_url", "https://lh4.googleusercontent.com/nr01-F6eM6Mb09CuDZBLvnxzpyRMpWQ0amrS593Rb7Q=w1200")); ?>;

	$("#nanoGallery").nanoGallery({
		
		flickrApiKey: <?php echo json_encode($this->Params->get("ozio_flickr_api_key", "")); ?>,
		
		picasaUrl: <?php echo json_encode(JURI::base().'index.php?option=com_oziogallery4&view=picasa&format=raw&ozio-menu-id='.JFactory::getApplication()->input->get('id')); ?>,
		
		locationHash: <?php echo json_encode(intval($this->Params->get("ozio_nano_locationHash", "1"))); ?>,
		viewerDisplayLogo: false,
		//thumbnailHeight può essere anche auto
		thumbnailHeight: <?php echo json_encode( $this->Params->get("ozio_nano_thumbnailHeight_kind","auto")!="auto"?$this->Params->get("ozio_nano_thumbnailHeight", "134"):"auto" ); ?>,
		thumbnailWidth: <?php echo json_encode( $this->Params->get("ozio_nano_thumbnailHeight_kind","autowidth")!="autowidth"?$this->Params->get("ozio_nano_thumbnailWidth", "226"):"auto" ); ?>,
		maxItemsPerLine: <?php echo json_encode(intval($this->Params->get("ozio_nano_maxItemsPerLine", "0"))); ?>,
		maxWidth: <?php echo json_encode(intval($this->Params->get("ozio_nano_maxWidth", "0"))); ?>,
		touchAnimation: 1,
		galleryToolbarWidthAligned: <?php echo json_encode(intval($this->Params->get("ozio_nano_galleryToolbarWidthAligned", "1"))); ?>,
		slideshowDelay: <?php echo json_encode(intval($this->Params->get("ozio_nano_slideshowDelay", "3000"))); ?>,
		/*paginationMaxItemsPerPage: <?php echo json_encode(intval($this->Params->get("ozio_nano_paginationMaxItemsPerPage", "0"))); ?>,*/
		paginationMaxLinesPerPage: <?php 
			$ozio_nano_paginationMaxLinesPerPage=intval($this->Params->get("ozio_nano_paginationMaxLinesPerPage", "0"));
			$ozio_nano_paginationMaxItemsPerPage=intval($this->Params->get("ozio_nano_paginationMaxItemsPerPage", "0"));
			
			if ($ozio_nano_paginationMaxItemsPerPage>0 && $ozio_nano_paginationMaxLinesPerPage==0){
				$ozio_nano_paginationMaxLinesPerPage=5;
			}
			
			echo json_encode($ozio_nano_paginationMaxLinesPerPage); 
		
		?>,
		
		thumbnailDisplayInterval: 0,
		thumbnailDisplayTransition: 1,
		thumbnailLazyLoad: <?php echo json_encode(intval($this->Params->get("ozio_nano_thumbnailLazyLoad", "1"))); ?>,
		thumbnailLazyLoadTreshold: <?php echo json_encode(intval($this->Params->get("ozio_nano_thumbnailLazyLoadTreshold", "150"))); ?>,

		viewer: <?php echo json_encode( "internal"/*$this->Params->get("ozio_nano_viewer", "internal")*/); ?>,
		thumbnailLabel: <?php echo json_encode(
							array(
									'position'=>$this->Params->get("ozio_nano_thumbnailLabel_position", "overImageOnBottom"),
									
									'oz_title_kind' => $this->Params->get("ozio_thumbnailTitle_kind", "description"),
									
									'display'=>intval($this->Params->get("ozio_nano_thumbnailLabel_display", "1")),
									'displayDescription'=>intval($this->Params->get("ozio_nano_thumbnailLabel_display", "1")),
									'titleMaxLength'=>intval($this->Params->get("ozio_nano_thumbnailLabel_maxTitle", "25")),
									'descriptionMaxLength'=>intval($this->Params->get("ozio_nano_thumbnailLabel_maxTitle", "25")),
									'hideIcons'=>intval($this->Params->get("ozio_nano_thumbnailLabel_hideIcons", "1")),
									'align'=>$this->Params->get("ozio_nano_thumbnailLabel_align", "left"),
									'itemsCount'=>$this->Params->get("ozio_nano_thumbnailLabel_itemsCount", "none")
							)); 
						?>,
						
		viewerToolbar: <?php 
							$standard=$this->Params->get("ozio_nano_viewerToolbar_standard", array());
							$minimized=$this->Params->get("ozio_nano_viewerToolbar_minimized", array());
							if (empty($standard)){
								$standard=array('minimizeButton','previousButton','pageCounter','nextButton','playPauseButton','fullscreenButton','infoButton','shareButton','closeButton','label');
							}
							if (empty($minimized)){
								$minimized=array('minimizeButton','label');
							}
		
		
							echo json_encode(
							array(
									'display'=>($this->Params->get("ozio_nano_viewerToolbar_display", 1)==1),
									'position'=>$this->Params->get("ozio_nano_viewerToolbar_position", "bottom"),
									'style'=>$this->Params->get("ozio_nano_viewerToolbar_style", "innerImage"),
									'autoMinimize'=>800,
									'standard'=>implode(',',$standard),
									'minimized'=>implode(',',$minimized)
									
							)); 
						?>,
						
		RTL: <?php $lang = JFactory::getLanguage(); echo json_encode($lang->isRTL()); ?>,
						
						
		galleryFullpageButton:<?php echo json_encode($this->Params->get("ozio_nano_galleryFullpageButton", 0)==1); ?>,
		thumbnailGutterWidth:<?php echo json_encode(intval($this->Params->get("ozio_nano_thumbnailGutterWidth", "5"))); ?>,
		thumbnailGutterHeight:<?php echo json_encode(intval($this->Params->get("ozio_nano_thumbnailGutterHeight", "5"))); ?>,
		thumbnailAlignment:<?php echo json_encode($this->Params->get("ozio_nano_thumbnailAlignment", "center")); ?>,
						
		showInfoBoxButton: <?php echo json_encode(intval($this->Params->get("info_button", "1"))==1); ?>,
		showInfoBoxAlbum: <?php echo json_encode(!intval($this->Params->get("hide_infobox_album", "0"))); ?>,
		showInfoBoxPhoto: <?php echo json_encode(!intval($this->Params->get("hide_infobox_photo", "0"))); ?>,
		showInfoBoxDate: <?php echo json_encode(!intval($this->Params->get("hide_infobox_date", "0"))); ?>,
		showInfoBoxDimensions: <?php echo json_encode(!intval($this->Params->get("hide_infobox_width_height", "0"))); ?>,
		showInfoBoxFilename: <?php echo json_encode(!intval($this->Params->get("hide_infobox_file_name", "0"))); ?>,
		showInfoBoxFilesize: <?php echo json_encode(!intval($this->Params->get("hide_infobox_file_size", "0"))); ?>,
		showInfoBoxCamera: <?php echo json_encode(!intval($this->Params->get("hide_infobox_model", "0"))); ?>,
		showInfoBoxFocallength: <?php echo json_encode(!intval($this->Params->get("hide_infobox_focallength", "0"))); ?>,
		showInfoBoxFNumber: <?php echo json_encode(!intval($this->Params->get("hide_infobox_fstop", "0"))); ?>,
		showInfoBoxExposure: <?php echo json_encode(!intval($this->Params->get("hide_infobox_exposure", "0"))); ?>,
		showInfoBoxISO: <?php echo json_encode(!intval($this->Params->get("hide_infobox_iso", "0"))); ?>,
		showInfoBoxMake: <?php echo json_encode(!intval($this->Params->get("hide_infobox_make", "0"))); ?>,
		showInfoBoxFlash: <?php echo json_encode(!intval($this->Params->get("hide_infobox_flash", "0"))); ?>,
		showInfoBoxViews: <?php echo json_encode(!intval($this->Params->get("hide_infobox_views", "0"))); ?>,
		showInfoBoxComments: <?php echo json_encode(!intval($this->Params->get("hide_infobox_comments", "0"))); ?>,
		showInfoBoxLink: <?php echo json_encode(!intval($this->Params->get("hide_infobox_link", "0"))); ?>,
		showInfoBoxDownload: <?php echo json_encode(!intval($this->Params->get("hide_infobox_download", "0"))); ?>,
		infoboxBgUrl: infobox_bg_url,
						
		thumbnailHoverEffect: <?php 
			$ozio_nano_thumbnailHoverEffect= $this->Params->get("ozio_nano_thumbnailHoverEffect", array("imageOpacity50"));
			if (!is_array($ozio_nano_thumbnailHoverEffect)){
				$ozio_nano_thumbnailHoverEffect=array("imageOpacity50");
			}
			echo json_encode(implode(',',$ozio_nano_thumbnailHoverEffect)); 
		?>,
		theme: <?php echo json_encode($this->Params->get("ozio_nano_theme", "clean")); ?>,
		colorScheme: <?php echo json_encode($this->Params->get("ozio_nano_colorScheme", "light")); ?>,
		colorSchemeViewer: <?php echo json_encode($this->Params->get("ozio_nano_colorSchemeViewer", "light")); ?>,
		
		imageTransition: <?php echo json_encode($this->Params->get("ozio_nano_imageTransition", "swipe")); ?>,

		kind: <?php echo json_encode($this->Params->get("ozio_nano_kind", "picasa")); ?>,
		userID: <?php echo json_encode($this->Params->get("ozio_nano_userID", "")); ?>,
		displayBreadcrumb: <?php echo json_encode(intval($this->Params->get("ozio_nano_displayBreadcrumb", "1"))); ?>,
		blackList: <?php echo json_encode($this->Params->get("ozio_nano_blackList", "Scrapbook|profil|2013-")); ?>,
		whiteList: <?php echo json_encode($this->Params->get("ozio_nano_whiteList", "")); ?>,
		photoSorting: <?php echo json_encode($this->Params->get("ozio_nano_photoSorting", "standard")); ?>,
		albumSorting: <?php echo json_encode($this->Params->get("ozio_nano_albumSorting", "standard")); ?>,
		ozmaxres: ozmaxres,
		
		thumbnailLabelL2_display: <?php echo json_encode(intval($this->Params->get("ozio_nano_thumbnailLabelL2_display", "1"))); ?>,
		//paginationDisableSwipe: <?php echo json_encode(intval($this->Params->get("ozio_nano_paginationDisableSwipe", "0"))); ?>,
		paginationDisableSwipe: false,
		paginationNumSelectable: <?php echo json_encode(intval($this->Params->get("ozio_nano_paginationNumSelectable", "10"))); ?>,
		
		oz_max_num_photo: <?php echo json_encode(intval($this->Params->get("oz_max_num_photo", "0"))); ?>,
		
		<?php
		$kind=$this->Params->get("ozio_nano_kind", "picasa");
		$albumvisibility= "public";
		if ($kind=='picasa' && $albumvisibility=='limited'){
			echo 'album:'.json_encode($this->Params->get("limitedalbum", "")."&authkey=".$this->Params->get("limitedpassword", "")).",\n";
			//echo 'authkey:'.json_encode($this->Params->get("limitedpassword", "")).",\n";
			
		}else{
					
			$albumList=$this->Params->get("ozio_nano_albumList", array());
			if (!empty($albumList) && is_array($albumList) ){
				if (count($albumList)==1){
					if (strpos($albumList[0],$non_printable_separator)!==FALSE){
						list($albumid,$title)=explode($non_printable_separator,$albumList[0]);
					}else{
						list($albumid,$title)=explode($new_non_printable_separator,$albumList[0]);
					}
					$kind=$this->Params->get("ozio_nano_kind", "picasa");
					if ($kind=='picasa'){
						echo 'album:'.json_encode($albumid).",\n";
					}else{
						echo 'photoset:'.json_encode($albumid).",\n";
					}
				}else{
					$albumTitles=array();
					foreach ($albumList as $a){
						if (strpos($a,$non_printable_separator)!==FALSE){
							list($albumid,$title)=explode($non_printable_separator,$a);
						}else{
							list($albumid,$title)=explode($new_non_printable_separator,$a);
						}
						$albumTitles[]=$title;
					}
					echo 'albumList:'.json_encode(implode('|',$albumTitles)).",\n";
				}
			}
			
		}
		
		
		?>
		i18n:{
			'paginationPrevious':<?php echo json_encode(JText::_('JPREV'));?>,
            'paginationNext':<?php echo json_encode(JText::_('JNEXT'));?>,		
	        'infoBoxPhoto':<?php echo json_encode(JText::_('COM_OZIOGALLERY4_PHOTOINFO_PHOTO_LBL'));?>,
	        'infoBoxDate':<?php echo json_encode(JText::_('COM_OZIOGALLERY4_PHOTOINFO_DATE_LBL'));?>,
	        'infoBoxAlbum':<?php echo json_encode(JText::_('COM_OZIOGALLERY4_PHOTOINFO_ALBUM_LBL'));?>,
	        'infoBoxDimensions':<?php echo json_encode(JText::_('COM_OZIOGALLERY4_PHOTOINFO_DIMENSIONS_LBL'));?>,
	        'infoBoxFilename':<?php echo json_encode(JText::_('COM_OZIOGALLERY4_PHOTOINFO_FILENAME_LBL'));?>,
	        'infoBoxFileSize':<?php echo json_encode(JText::_('COM_OZIOGALLERY4_PHOTOINFO_FILESIZE_LBL'));?>,
	        'infoBoxCamera':<?php echo json_encode(JText::_('COM_OZIOGALLERY4_PHOTOINFO_CAMERA_LBL'));?>,
	        'infoBoxFocalLength':<?php echo json_encode(JText::_('COM_OZIOGALLERY4_PHOTOINFO_FOCALLENGTH_LBL'));?>,
	        'infoBoxExposure':<?php echo json_encode(JText::_('COM_OZIOGALLERY4_PHOTOINFO_EXPOSURE_LBL'));?>,
	        'infoBoxFNumber':<?php echo json_encode(JText::_('COM_OZIOGALLERY4_PHOTOINFO_FSTOP_LBL'));?>,
	        'infoBoxISO':<?php echo json_encode(JText::_('COM_OZIOGALLERY4_PHOTOINFO_ISO_LBL'));?>,
	        'infoBoxMake':<?php echo json_encode(JText::_('COM_OZIOGALLERY4_PHOTOINFO_CAMERAMAKE_LBL'));?>,
	        'infoBoxFlash':<?php echo json_encode(JText::_('COM_OZIOGALLERY4_PHOTOINFO_FLASH_LBL'));?>,
	        'infoBoxViews':<?php echo json_encode(JText::_('COM_OZIOGALLERY4_PHOTOINFO_VIEWS_LBL'));?>,
	        'infoBoxComments':<?php echo json_encode(JText::_('COM_OZIOGALLERY4_PHOTOINFO_COMMENTS_LBL'));?>
	        
	        }				

	});
	if (infobox_bg_url){
		nano_preload_imageObj = new Image();
		nano_preload_imageObj.src = infobox_bg_url;
	}

});
