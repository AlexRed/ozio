<?php
/**
* This file is part of Ozio Gallery 2.
*
* Ozio Gallery 2 is free software: you can redistribute it and/or modify
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
<table width="100%" align="<?php echo $this->table ?>">
	<tr>
		<td>
			<div id="oziowall" class="oziofloat">  aaaaa</div>
    <script>
        var flashvars = {
		<?php if  	  ( $this->flickr == 1 ) : ?>	
					feed: "api://www.flickr.com/<?php if ( $this->user_id != '' ) : ?>?user=<?php echo $this->user_id ?><?php else: ?><?php if ( $this->group_id != '' ) : ?>?group=<?php echo $this->group_id ?><?php else: ?><?php if ( $this->set_id != '' ) : ?>?album=<?php echo $this->set_id ?><?php else: ?><?php if ( $this->text != '' ) : ?>?search=<?php echo $this->text ?><?php else: ?><?php endif; ?><?php endif; ?><?php endif; ?><?php endif; ?>"};
		<?php else: ?>
		<?php if  	  ( $this->xml_mode == 0 ) : ?>
					feed: "<?php echo JURI::root() ?>components/com_oziogallery2/skin/cooliris/xml/cooliris_<?php echo $this->nomexml ?>.ozio&numRows=<?php echo $this->rows ?>&backgroundColor=0x<?php echo $this->bkgndretro ?>&backgroundImage=&showEmbed=false&glowColor=0x<?php echo $this->framecolor ?>&showDescription=<?php echo $this->download ?>"}; 
		<?php else: ?>
					feed: "<?php echo JURI::root() ?><?php echo $this->manualxmlname ?>"};
		<?php endif; ?>
		<?php endif; ?>
        var params = {
             allowFullScreen: "true",
             allowscriptaccess: "always"
        };
        swfobject.embedSWF("<?php echo JURI::root() ?>components/com_oziogallery2/skin/cooliris/cooliris.swf",
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
<?php require(JPATH_COMPONENT_ADMINISTRATOR.DS."css".DS."ozio.css");  ?> 
<div class="clr"><br /></div>	
