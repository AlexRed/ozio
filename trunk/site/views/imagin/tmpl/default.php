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
   
	<script src="<?php echo JURI::root() ?>components/com_oziogallery2/imagin/scripts_ralcr/js/flash_resize.js"></script>
	<script src="<?php echo JURI::root() ?>components/com_oziogallery2/imagin/scripts_ralcr/js/swfmacmousewheel2.js"></script>
	<script src="<?php echo JURI::root() ?>components/com_oziogallery2/imagin/scripts_ralcr/js/swfaddress.js"></script>
	<script src="<?php echo JURI::root() ?>components/com_oziogallery2/imagin/scripts_ralcr/js/swfaddress-optimizer.js?flash=9"></script>
	
	<script language="JavaScript">
		var imagin_scripts_path = "components/com_oziogallery2/imagin/scripts_ralcr/"; // a path relative to the html which embeds the swf
		var imagin_photos_path = "../../../<?php echo $this->folder ?>"; // a path relative to "scripts_ralcr" parent		
		var imagin_instance = "imagin_instance";
		var imagin_swf_color = "<?php echo $this->colore ?>";
		var imagin_swf_width = "<?php echo $this->larghezza ?>";
		var imagin_swf_height= "<?php echo $this->altezza ?>";
		
		
		// SWFObject embed by Geoff Stearns geoff@deconcept.com http://blog.deconcept.com/
		var flashvars = { id:imagin_instance, scripts_path:imagin_scripts_path, photos_path:imagin_photos_path };
		var params = { allowFullScreen:"true", allowNetworking:"all", allowScriptAccess:"always", bgcolor:imagin_swf_color };
		var attributes = { id:imagin_instance, name:imagin_instance };
		
		swfobject.embedSWF ("<?php echo JURI::root() ?>components/com_oziogallery2/imagin/imagin.swf?"+Math.random()*1, imagin_instance, imagin_swf_width, imagin_swf_height, "9",
							imagin_scripts_path + "/embed/expressInstall.swf", flashvars, params, attributes);
		swfmacmousewheel.registerObject( imagin_instance );
	</script>
	
	
	<div id="imagin_instance">
		<p>You need JavaScript activated and at least flash player 9 installed.</p>
	</div>

   </td></tr></table>
   <br /> 
<?php require(JPATH_COMPONENT_ADMINISTRATOR.DS."css".DS."ozio.css");  ?> 