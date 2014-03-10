<?php
$non_printable_separator="\x16";
?>
jQuery( document ).ready(function( $ ) {
	$("#nanoGallery").nanoGallery({
		locationHash: <?php echo json_encode(intval($this->Params->get("ozio_nano_locationHash", "1"))); ?>,
		viewerDisplayLogo: false,
		//thumbnailHeight può essere anche auto
		thumbnailHeight: <?php echo json_encode( $this->Params->get("ozio_nano_thumbnailHeight_kind","auto")=="fixed"?intval($this->Params->get("ozio_nano_thumbnailHeight", "150")):"auto" ); ?>,
		thumbnailWidth: <?php echo json_encode(intval($this->Params->get("ozio_nano_thumbnailWidth", "200"))); ?>,
		maxItemsPerLine: <?php echo json_encode(intval($this->Params->get("ozio_nano_maxItemsPerLine", "0"))); ?>,
		maxWidth: <?php echo json_encode(intval($this->Params->get("ozio_nano_maxWidth", "0"))); ?>,
		touchAnimation: <?php echo json_encode(intval($this->Params->get("ozio_nano_touchAnimation", "1"))); ?>,
		galleryToolbarWidthAligned: <?php echo json_encode(intval($this->Params->get("ozio_nano_galleryToolbarWidthAligned", "1"))); ?>,
		slideshowDelay: <?php echo json_encode(intval($this->Params->get("ozio_nano_slideshowDelay", "3000"))); ?>,
		paginationMaxItemsPerPage: <?php echo json_encode(intval($this->Params->get("ozio_nano_paginationMaxItemsPerPage", "0"))); ?>,
		paginationMaxLinesPerPage: <?php echo json_encode(intval($this->Params->get("ozio_nano_paginationMaxLinesPerPage", "0"))); ?>,
		thumbnailDisplayInterval: <?php echo json_encode(intval($this->Params->get("ozio_nano_thumbnailDisplayInterval", "30"))); ?>,
		thumbnailDisplayTransition: <?php echo json_encode(intval($this->Params->get("ozio_nano_thumbnailDisplayTransition", "1"))); ?>,
		thumbnailLazyLoad: <?php echo json_encode(intval($this->Params->get("ozio_nano_thumbnailLazyLoad", "1"))); ?>,
		thumbnailLazyLoadTreshold: <?php echo json_encode(intval($this->Params->get("ozio_nano_thumbnailLazyLoadTreshold", "100"))); ?>,

		viewer: <?php echo json_encode( "internal"/*$this->Params->get("ozio_nano_viewer", "internal")*/); ?>,
		thumbnailLabel: <?php echo json_encode(
							array(
									'position'=>$this->Params->get("ozio_nano_thumbnailLabel_position", "overImageOnBottom"),
									'display'=>intval($this->Params->get("ozio_nano_thumbnailLabel_display", "1")),
									'displayDescription'=>intval($this->Params->get("ozio_nano_thumbnailLabel_displayDescription", "1")),
									'maxTitle'=>intval($this->Params->get("ozio_nano_thumbnailLabel_maxTitle", "25")),
									'maxDescription'=>intval($this->Params->get("ozio_nano_thumbnailLabel_maxDescription", "0")),
							)); 
						?>,
				
		thumbnailHoverEffect: <?php echo $this->Params->get("ozio_nano_thumbnailHoverEffect", "imageOpacity50")=='none'?'null':json_encode($this->Params->get("ozio_nano_thumbnailHoverEffect", "imageOpacity50")); ?>,
		theme: <?php echo json_encode($this->Params->get("ozio_nano_theme", "clean")); ?>,
		colorScheme: <?php echo json_encode($this->Params->get("ozio_nano_colorScheme", "light")); ?>,
		colorSchemeViewer: <?php echo json_encode($this->Params->get("ozio_nano_colorSchemeViewer", "light")); ?>,

		kind: <?php echo json_encode($this->Params->get("ozio_nano_kind", "picasa")); ?>,
		userID: <?php echo json_encode($this->Params->get("ozio_nano_userID", "110359559620842741677")); ?>,
		displayBreadcrumb: <?php echo json_encode(intval($this->Params->get("ozio_nano_displayBreadcrumb", "1"))); ?>,
		blackList: <?php echo json_encode($this->Params->get("ozio_nano_blackList", "Scrapbook|profil|2013-")); ?>,
		whiteList: <?php echo json_encode($this->Params->get("ozio_nano_whiteList", "")); ?>,
		<?php
		$albumList=$this->Params->get("ozio_nano_albumList", array());
		if (!empty($albumList) && is_array($albumList) ){
			if (count($albumList)==1){
				list($albumid,$title)=explode($non_printable_separator,$albumList[0]);
				$kind=$this->Params->get("ozio_nano_kind", "picasa");
				if ($kind=='picasa'){
					echo 'album:'.json_encode($albumid).",\n";
				}else{
					echo 'photoset:'.json_encode($albumid).",\n";
				}
			}else{
				$albumTitles=array();
				foreach ($albumList as $a){
					list($albumid,$title)=explode($non_printable_separator,$a);
					$albumTitles[]=$title;
				}
				echo 'albumList:'.json_encode(implode('|',$albumTitles)).",\n";
			}
		}		
		?>
				

	});

});
