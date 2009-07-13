<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<?php if ( $this->params->def( 'show_page_title', 1 ) ) : ?>
	<div class="componentheading<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
		<?php echo $this->escape($this->params->get('page_title')); ?>
	</div>
<?php endif; ?>
<?php if ( $this->params->get('showintrotext')) : ?>
<table width="100%" cellpadding="4" cellspacing="0" border="0" align="center" class="contentpane<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
<tr>
	<td valign="top" class="contentdescription<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
		<?php echo $this->escape($this->params->get('introtext')); ?>	
	</td>
</tr>
</table>
<?php endif; ?>
   <table align="<?php echo $this->table ?>"><tr><td>
	<div id="ozioflashcontent" class="oziofloat">
			<a href="http://www.macromedia.com/go/getflashplayer">Get the Flash Player</a> to see this rotator.
	</div>
				<script type="text/javascript">
					var fo = new SWFObject("<?php echo JURI::root() ?>components/com_oziogallery2/skin/tiltviewer/TiltViewer.swf","viewer","<?php echo $this->larghezza ?>","<?php echo $this->altezza ?>", "9.0.28", "#<?php echo $this->bkgndoutercolor ?>");

					//FLICKR GALLERY OPTIONS
					// To use images from Flickr, uncomment this block
<?php if  	  ( $this->flickr == 1 ) : ?>					
					fo.addVariable("user_id", "<?php echo $this->user_id ?>");
					fo.addVariable("tags", "<?php echo $this->tags ?>");
					fo.addVariable("text", "<?php echo $this->text ?>");
					fo.addVariable("tag_mode", "<?php echo $this->tag_mode ?>");
					fo.addVariable("showTakenByText", "true");
			        fo.addVariable("sort", "<?php echo $this->sort ?>");
			        fo.addVariable("group_id", "<?php echo $this->group_id ?>");
					fo.addVariable("set_id", "<?php echo $this->set_id ?>");
<?php endif; ?>					
					// To use local images defined in an XML document, use this block
					fo.addVariable("useFlickr", "<?php echo $this->flickrs ?>");
		
<?php if  	  ( $this->xml_mode == 0 ) : ?>
					fo.addVariable("xmlURL","<?php echo JURI::root() ?>components/com_oziogallery2/skin/tiltviewer/xml/tiltviewer_<?php echo $this->nomexml ?>.xml"); 
<?php else: ?>
					fo.addVariable("xmlURL","<?php echo JURI::root() ?>components/com_oziogallery2/skin/tiltviewer/xml/tiltviewer.xml");
<?php endif; ?>
					fo.addVariable("maxJPGSize","<?php echo $this->maximagesize ?>");
					//GENERAL OPTIONS
					fo.addVariable("useReloadButton", "false");
					fo.addVariable("width","<?php echo $this->larghezza ?>");
					fo.addVariable("height","<?php echo $this->altezza ?>");					
					fo.addVariable("columns", "<?php echo $this->columns ?>");
					fo.addVariable("rows", "<?php echo $this->rows ?>");
					fo.addVariable("showFlipButton", "true");
					fo.addVariable("showLinkButton", "<?php echo $this->download ?>");
					fo.addVariable("linkLabel", "<?php echo $this->downloadtxt ?>");
					fo.addVariable("frameColor", "0x<?php echo $this->framecolor ?>");
					fo.addVariable("backColor", "0x<?php echo $this->bkgndretro ?>");
					fo.addVariable("bkgndInnerColor", "0x<?php echo $this->bkgndinnercolor ?>");
					fo.addVariable("bkgndOuterColor", "0x<?php echo $this->bkgndoutercolor ?>");
					//fo.addVariable("langGoFull", "Go Fullscreen");
					//fo.addVariable("langExitFull", "Exit Fullscreen");
					//fo.addVariable("langAbout", "About");
					
				//PRO OPTIONS
					//fo.addVariable("bkgndTransparent", "true");
					//fo.addVariable("showFullscreenOption", "false");
					//fo.addVariable("frameWidth", "40");
					//fo.addVariable("zoomedInDistance", "1400");
					//fo.addVariable("zoomedOutDistance", "7500");
					//fo.addVariable("fontName", "Times");
					//fo.addVariable("titleFontSize", "90");
					//fo.addVariable("descriptionFontSize", "32");
					//fo.addVariable("linkFontSize", "41");
					//fo.addVariable("linkTarget", "_self");
					//fo.addVariable("navButtonColor", "0xFF00FF");
					//fo.addVariable("flipButtonColor", "0x0000FF");
					//fo.addVariable("textColor", "0xFFFFFF");
					//fo.addVariable("linkTextColor", "0x000000");
					//fo.addVariable("linkBkgndColor", "0xFFFFFF");
					
				// END TILTVIEWER CONFIGURATION OPTIONS					
					fo.addParam("wmode", "transparent");
					fo.addParam("allowFullScreen","true");
					fo.write("ozioflashcontent");
				</script>
   </td></tr></table>
<?php if ( $this->modifiche == 1 ) : ?>   
	<table align="<?php echo $this->table ?>"><tr><td>   
		<?php echo $this->tempo ?> 
	</td></tr></table> 
<?php endif; ?>	 
<?php if ( $this->debug == 1 ) : ?>   
	<table class="oziopre"><tr><td>   
		<?php echo $this->oziodebug ?> 
	</td></tr></table> 
<?php endif; ?>
<?php require(JPATH_COMPONENT_ADMINISTRATOR.DS."css".DS."ozio.css");  ?> 