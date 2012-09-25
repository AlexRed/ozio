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
	<div id="content">

			<strong>You need to upgrade your Flash Player.</strong>
		</div>

		<script type="text/javascript">
            var so = new SWFObject('<?php echo JURI::root() ?>components/com_oziogallery3/skin/futura/futura.swf?cahcebuster='+ escape((new Date()).getTime()), 'slideshow', '<?php echo $this->larghezza ?>', '<?php echo $this->altezza ?>', '10', '#333333');
            so.useExpressInstall('<?php echo JURI::root() ?>components/com_oziogallery3/skin/futura/swfobject/expressinstall.swf');
            so.addParam('menu', 'false');
			so.addParam('allowfullscreen', 'true');
			so.addParam('allowScriptAccess','always');
			so.addParam('wmode','opaque');  <!--   Se usi il wmode passalo anche come variabile a Flash ( vedi  so.addVariable('wmode','opaque'); )  -->
			<?php if  	  ( $this->xml_mode == 0 ) : ?>
			so.addVariable('xmlPath', '<?php echo JURI::root() ?>components/com_oziogallery3/skin/futura/xml/futura_<?php echo $this->nomexml ?>.ozio?'+Math.random()*1)
			<?php else: ?>
			so.addVariable('xmlPath', '<?php echo JURI::root() ?><?php echo $this->manualxmlname ?>')
			<?php endif; ?>
			so.addVariable('wmode','opaque');  <!--   Passa il valore di wmode anche a Flash  -->
			so.addParam('scale','noscale');
			so.addParam('salign','tl');
			so.addVariable('background_color','0x<?php echo $this->bkgndoutercolor ?>');  <!--   Imposta il colore di sfondo   -->
			so.addVariable('background_image','<?php echo $this->immaginesfondo ?>'); <!--   Se vuoi un'immagine di sfondo inserisci l'url dove si trova l'immagine, altrimenti inserisci "none"   -->
			so.addVariable('image_name','<?php echo $this->titolo ?>');  <!--   Mostra nome immagine true o false   -->
			so.addVariable('image_date','true');  <!--   Mostra data immagine true o false   -->
			so.addVariable('category_name','<?php echo $this->titolocat ?>');  <!--   Mostra nome categoria true o false   -->
			so.addVariable('category_date','true');  <!--   Mostra data categoria true o false   -->
			so.addVariable('background_color_miniature_categorie','0x<?php echo $this->bkgnd_min_categorie ?>');  <!--   Colore sfondo miniature categorie   -->
			so.addVariable('background_color_miniature_immagini','0x<?php echo $this->bkgnd_min_immagini ?>');  <!--   Colore sfondo miniature immagini   -->
			so.addVariable('background_mouseover_color_miniature_immagini','0x<?php echo $this->bkgnd_min_immagini_over ?>');  <!--   Colore sfondo in mouseover delle miniature immagini   -->
			so.addVariable('background_color_immagini_grandi','0x<?php echo $this->bkgnd_immagini_grandi ?>');  <!--   Colore sfondo delle immagini grandi   -->
			so.addVariable('text_color_miniature_immagini','0x<?php echo $this->colortext_miniature ?>');  <!--   Colore testo miniature immagini   -->
			so.addVariable('text_color_mouseover_miniature_immagini','0x<?php echo $this->colortext_miniature_over ?>');  <!--   Colore testo in mouseover delle miniature immagini   -->
			so.addVariable('lines_color','0x<?php echo $this->color_linea ?>');  <!--   Colore delle linee categorie   -->
			so.addVariable('nav_color','0x<?php echo $this->color_freccia ?>');  <!--   Colore delle icone di navigazione  -->
			so.addVariable('numero_anteprime','12');  <!--   Quante anteprime caricare ( max 24 )  -->
			so.addVariable('transition','<?php echo $this->transition ?>');  <!--   Tipo di transizione ( fade, blinds, fly, iris, photo, pixeldissolve, rotate, wipe, zoom)  -->
			so.addVariable('speed_transition','0.5');
			so.addVariable('home_color','0x<?php echo $this->color_home ?>');  <!--   Colore icona home  -->
			so.addVariable('menu_background_color','0x<?php echo $this->color_menu ?>');  <!--   Colore sfondi menu  -->
			so.addVariable('menu_text_color','0x<?php echo $this->color_menu_text ?>');  <!--   Colore testi menu  -->
			so.addVariable('titolo_full_screen','<?php echo $this->schermointerotxt ?>');  <!--   Testo click destro per full screen  -->
			so.addVariable('titolo_normal_screen','<?php echo $this->schermonormaletxt ?>');  <!--   Testo click destro per normal screen  -->
            so.write('content');
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
