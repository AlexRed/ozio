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
   <table align="<?php echo $this->table ?>">
   <tr>  
		<td align="<?php echo $this->iframe ?>">
			<iframe align="<?php echo $this->iframe ?>" src="http://www.flickr.com/photos/<?php echo $this->user_id ?><?php if ($this->tags == '') : ?><?php echo ""; ?><?php else : ?><?php echo "/tags/" .$this->tags; ?><?php endif; ?><?php if ($this->set_id == '') : ?><?php echo ""; ?><?php else : ?><?php echo "/sets/" .$this->set_id; ?><?php endif; ?><?php if( $this->sort == 1 ) echo "/interesting";?>/show" frameBorder="0" width="<?php echo $this->larghezza ?>" scrolling="no" height="<?php echo $this->altezza ?>"></iframe>
		</td>
	</tr>
</table>
<?php if ( $this->debug == 1 ) : ?>   
	<table class="oziopre"><tr><td>   
		<?php echo $this->oziodebug ?> 
	</td></tr></table> 
<?php endif; ?>	 
<?php require(JPATH_COMPONENT_ADMINISTRATOR.DS."css".DS."ozio.css");  ?>