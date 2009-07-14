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
	<div id="oziogallery" class="oziofloat">
			<a href="http://www.macromedia.com/go/getflashplayer">Get the Flash Player</a> to see this rotator.
	</div>
				<script type="text/javascript">
					var so = new SWFObject("<?php echo JURI::root() ?>components/com_oziogallery2/skin/flashgallery/flashgallery.swf","gallery","<?php echo $this->larghezza ?>","<?php echo $this->altezza ?>", "8");
					so.addParam("quality", "high");
					so.addParam("allowFullScreen", "true");
					so.addParam("wmode", "transparent");
					so.addVariable("color_path","<?php echo JURI::root() ?>components/com_oziogallery2/skin/flashgallery/settings/settings_<?php echo $this->nomexml ?>.xml");					
<?php if ( $this->flickr == 1 ) : ?>						
					so.addVariable("content_path","http://www.flickr.com/photos/<?php echo $this->user_id ?>");
<?php else: ?>	
					so.addVariable("script_path","<?php echo JURI::root() ?>components/com_oziogallery2/skin/flashgallery/xml/flashgallery_<?php echo $this->nomexml ?>.xml" );
<?php endif; ?>						
					so.write("oziogallery");
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
<div class="clr"><br /></div> 