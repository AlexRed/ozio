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
   
<div id="carousel_container" class="oziofloat">
	<div id="carousel1">
		<p>This slideshow requires <a href="http://www.adobe.com/go/getflashplayer">Adobe Flash Player 9.0</a> (or higher). JavaScript must be enabled.</p>
	</div>
</div></td></tr></table>
<script type="text/javascript">
	swfobject.embedSWF("<?php echo JURI::root() ?>components/com_oziogallery2/skin/carousel/Carousel.swf", "carousel1", "<?php echo $this->larghezza ?>", "<?php echo $this->altezza ?>", "9.0.0", false, {xmlfile:"<?php echo $this->xml_moder ?>", loaderColor:"<?php echo $this->loadercolor ?>"}, {wmode: "transparent"});
</script>

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