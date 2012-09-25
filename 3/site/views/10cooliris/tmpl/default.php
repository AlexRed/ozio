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
<?php
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");
srand((double)microtime()*1000000);
$randval = rand();
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
<table width="100%" align="<?php echo $this->table ?>">
	<tr>
		<td>
			<div id="oziowall" class="oziofloat">  <a href="http://www.macromedia.com/go/getflashplayer">Get the Flash Player</a> to see this gallery.</div>
    <script>
        var flashvars = {
		<?php if  	  ( $this->flickr == 1 ) : ?>
					feed: "api://www.flickr.com/<?php if ( $this->user_id != '' ) : ?>?user=<?php echo $this->user_id ?><?php else: ?><?php if ( $this->group_id != '' ) : ?>?group=<?php echo $this->group_id ?><?php else: ?><?php if ( $this->set_id != '' ) : ?>?album=<?php echo $this->set_id ?><?php else: ?><?php if ( $this->text != '' ) : ?>?search=<?php echo $this->text ?><?php else: ?><?php endif; ?><?php endif; ?><?php endif; ?><?php endif; ?>&numRows=<?php echo $this->rows ?>&backgroundColor=0x<?php echo $this->bkgndretro ?>&backgroundImage=<?php echo $this->immaginesfondo ?>&showEmbed=false&glowColor=0x<?php echo $this->framecolor ?>&showDescription=<?php echo $this->download ?>&cellWidth=<?php echo $this->larghezzaant ?>&cellHeight=<?php echo $this->altezzaant ?>&cellSpacingX=<?php echo $this->distanzaoriz ?>&cellSpacingY=<?php echo $this->distanzavert ?>"};
		<?php else: ?>
		<?php if  	  ( $this->xml_mode == 0 ) : ?>
					feed: "<?php echo JURI::root() ?>components/com_oziogallery3/skin/cooliris/xml/cooliris_<?php echo $this->nomexml ?>.ozio?<?php echo $randval; ?>&numRows=<?php echo $this->rows ?>&backgroundColor=0x<?php echo $this->bkgndretro ?>&backgroundImage=<?php echo $this->immaginesfondo ?>&showEmbed=false&glowColor=0x<?php echo $this->framecolor ?>&showDescription=<?php echo $this->download ?>&cellWidth=<?php echo $this->larghezzaant ?>&cellHeight=<?php echo $this->altezzaant ?>&cellSpacingX=<?php echo $this->distanzaoriz ?>&cellSpacingY=<?php echo $this->distanzavert ?>"};
		<?php else: ?>
					feed: "<?php echo JURI::root() ?><?php echo $this->manualxmlname ?>"};
		<?php endif; ?>
		<?php endif; ?>
        var params = {
             allowFullScreen: "true",
			 wmode: "opaque",
             allowscriptaccess: "always"
        };
        swfobject.embedSWF("<?php echo JURI::root() ?>components/com_oziogallery3/skin/cooliris/cooliris.swf?"+Math.random()*1,
            "oziowall", "<?php echo $this->larghezza ?>", "<?php echo $this->altezza ?>", "9.0.0", "",
            flashvars, params);
    </script>



       </td>
	</tr>
</table>
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
