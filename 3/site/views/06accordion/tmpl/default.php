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
   <table align="<?php echo $this->table ?>"><tr><td>

		<div id="ozioflashcontent" class="oziofloat">

			<strong>You need to upgrade your Flash Player.</strong>
		</div>

<script type="text/javascript">

		var stageW = <?php echo $this->larghezza;?>;
		var stageH = <?php echo $this->altezza;?>;

		var so = new SWFObject("<?php echo JURI::root().'components/com_oziogallery3/skin/accordion/preview.swf';?>?"+Math.random()*1, "sotester", stageW, stageH, "9", "<?php echo $this->loadercolor;?>");

		so.addVariable("xmlPath", "<?php echo $this->xml_moder;?>");

		so.addVariable("stageW", stageW);
       		so.addVariable("stageH", stageH);

       		so.addVariable("keepSelected", "<?php if( $this->tuttochiuso == 1 ) echo "f"; else echo "t";?>");
       		so.addVariable("selectedWindow", "<?php echo $this->fotoiniziale;?>");
       		so.addVariable("imageWidth", "<?php echo $this->larghezzaimmagine;?>");
       		so.addVariable("imageHeight", "<?php echo $this->altezza;?>");
		so.addVariable("slideshow", "<?php if( $this->slidershow == 1 ) echo "t"; else echo "f";?>");

		so.addParam("allowFullScreen", "true");
		so.addParam("wmode", "transparent");
		so.write("ozioflashcontent");

	</script>

   </td></tr></table>
<?php if ( $this->modifiche == 1 ) : ?>
	<table align="<?php echo $this->table ?>"><tr><td>
		<?php echo $this->tempo ?>
	</td></tr></table>
<?php endif; ?>
<?php if ( $this->debug == 1 ) : ?>
	<table><tr><td>
		<?php echo $this->oziodebug ?>
	</td></tr></table>
<?php endif; ?>
