<?php
/**
* This file is part of Ozio Gallery 3
*
* Ozio Gallery 3 is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 2 of the License, or
* (at your option) any later version.
*
* Foobar is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Foobar.  If not, see <http://www.gnu.org/licenses/>.
*
* @copyright Copyright (C) 2010 Open Source Solutions S.L.U. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see RT-LICENSE.php
*/
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
   <table width="100%" align="<?php echo $this->table ?>"><tr><td>
	<div id="flashcontent">

			<strong>You need to upgrade your Flash Player.</strong>
		</div>

		<script type="text/javascript">
			var so = new SWFObject("<?php echo JURI::root() ?>components/com_oziogallery3/skin/mediagallery/mediaGallery.swf?"+Math.random()*1, "gallery", "<?php echo $this->larghezza ?>", "<?php echo $this->altezza ?>", "6", "#333333");
 <?php if  	  ( $this->xml_mode == 0 ) : ?>
			so.addVariable("data_source", "<?php echo JURI::root() ?>components/com_oziogallery3/skin/mediagallery/xml/mediagallery_<?php echo $this->nomexml ?>.ozio?"+Math.random()*1)
<?php else: ?>
			so.addVariable("data_source", "<?php echo JURI::root() ?><?php echo $this->manualxmlname ?>")
<?php endif; ?>
			so.addVariable("stageAlign", "TL");
			so.addVariable("stageScaleMode", "noScale");
 <?php if  	  ( $this->behind == 1 ) : ?>
			so.addParam("wmode", "transparent");
<?php else: ?>
<?php endif; ?>
			so.write("flashcontent");
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
