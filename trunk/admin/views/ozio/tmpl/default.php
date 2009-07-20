<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
JHTML::_('behavior.tooltip');
?>
	<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
			<td valign="top">
<table class="adminlist">
	<tr>
		<td align="left" width="50%" valign="top"><?php echo JText::_('COMPONENT DESCRIPTION');?></td>
		<td align="left" width="50%" valign="top"><?php echo JText::_('COMPONENT VOTE');	?></td>
	</tr>
	<tr>
		<td align="left" valign="top"><?php echo JText::_('COMPONENT INSTRUCTIONS');	?></td>
		<td align="left" valign="top"><?php echo JText::_('COMPONENT LANGUAGE');	?></td>
	</tr>
</table>

<table class="admintable">
	<tr>
<h2><?php echo JText::_('SYSTEM INFORMATION');	?></h2>
<?php echo JText::_('XML TEST1');	?>  <?php echo JText::_('XML TEST2');	?>
<p><?php echo JText::_('MUST BE GREEN');	?></p>
<table class="adminlist">
	<thead>
		<tr>
			<th width="4%" class="title" align="center">#</th>
			<th width="48%" class="title" align="center"><?php echo JText::_( 'CARTELLA' ); ?></th>
			<th width="48%" class="title" align="center"><?php echo JText::_( 'PERMISSIONS' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td align="center">1</td>
			<td align="center">images/oziogallery2</td>
			<td align="center"><?php echo is_writable(JPATH_SITE.DS.'images'.DS.'oziogallery2') ? 
			'<strong><font color="green">'. JText::_( 'Writable' ) .'</font></strong>' : 
			'<strong><font color="red">'. JText::_( 'Unwritable' ) .'</font></strong>'; ?></td>
		</tr>
			<tr>
			<td align="center">2</td>
			<td align="center">components/com_oziogallery2/skin/accordion/xml</td>
			<td align="center"><?php echo is_writable(JPATH_SITE.DS.'components'.DS.'com_oziogallery2'.DS.'skin'.DS.'accordion'.DS.'xml') ? 
			'<strong><font color="green">'. JText::_( 'Writable' ) .'</font></strong>' : 
			'<strong><font color="red">'. JText::_( 'Unwritable' ) .'</font></strong>'; ?></td>
		</tr>
		</tr>		
			<tr>
			<td align="center">4</td>
			<td align="center">components/com_oziogallery2/skin/carousel/xml</td>
			<td align="center"><?php echo is_writable(JPATH_SITE.DS.'components'.DS.'com_oziogallery2'.DS.'skin'.DS.'carousel'.DS.'xml') ? 
			'<strong><font color="green">'. JText::_( 'Writable' ) .'</font></strong>' : 
			'<strong><font color="red">'. JText::_( 'Unwritable' ) .'</font></strong>'; ?></td>
		</tr>
		</tr>		
			<tr>
			<td align="center">5</td>
			<td align="center">components/com_oziogallery2/skin/flashgallery/xml</td>
			<td align="center"><?php echo is_writable(JPATH_SITE.DS.'components'.DS.'com_oziogallery2'.DS.'skin'.DS.'flashgallery'.DS.'xml') ? 
			'<strong><font color="green">'. JText::_( 'Writable' ) .'</font></strong>' : 
			'<strong><font color="red">'. JText::_( 'Unwritable' ) .'</font></strong>'; ?></td>
		</tr>		
		</tr>		
			<tr>
			<td align="center">6</td>
			<td align="center">components/com_oziogallery2/skin/imagerotator/xml</td>
			<td align="center"><?php echo is_writable(JPATH_SITE.DS.'components'.DS.'com_oziogallery2'.DS.'skin'.DS.'imagerotator'.DS.'xml') ? 
			'<strong><font color="green">'. JText::_( 'Writable' ) .'</font></strong>' : 
			'<strong><font color="red">'. JText::_( 'Unwritable' ) .'</font></strong>'; ?></td>
		</tr>	
		</tr>		
			<tr>
			<td align="center">7</td>
			<td align="center">components/com_oziogallery2/skin/tiltviewer/xml</td>
			<td align="center"><?php echo is_writable(JPATH_SITE.DS.'components'.DS.'com_oziogallery2'.DS.'skin'.DS.'tiltviewer'.DS.'xml') ? 
			'<strong><font color="green">'. JText::_( 'Writable' ) .'</font></strong>' : 
			'<strong><font color="red">'. JText::_( 'Unwritable' ) .'</font></strong>'; ?></td>
		</tr>	
			<tr>
			<td align="center">8</td>
			<td align="center">components/com_oziogallery2/imagin</td>
			<td align="center"><?php echo is_writable(JPATH_SITE.DS.'components'.DS.'com_oziogallery2'.DS.'imagin') ? 
			'<strong><font color="green">'. JText::_( 'Writable' ) .'</font></strong>' : 
			'<strong><font color="red">'. JText::_( 'Unwritable' ) .'</font></strong>'; ?></td>
		</tr>			
	</tbody>
</table>
	</tr>
</table>

<table class="admintable">
	<tr>
		<td align="left">
<b><a href="http://forum.joomla.it/index.php/board,73.0.html" target="blank">Ozio Gallery FORUM</a></b> - <b><a href="http://code.google.com/p/oziogallery2/" target="blank">Ozio Gallery Google Code Project</a></b> - <b><a href="http://oziogallery.joomla.it/" target="blank">Ozio Gallery Site</a></b><br /><br />
Credits:
<br />
<br />Flash is based on:
<br />- <a href="http://www.airtightinteractive.com/projects/tiltviewer/" target='blank'>TiltViewer</a> Design/Development by <a href="http://www.airtightinteractive.com" target='blank'>Airtight</a>, Sound Design by <a href="http://www.earganic.com/" target='blank'>Earganic Studios</a>, <a href="http://blog.deconcept.com/flashobject/" target='blank'>FlashObject</a> Javascript embed code by Geoff Stearns.
<br />TiltViewer-Pro is also available for purchase with increased customization options and the TiltViewer logo not included. To purchase it <a href="http://www.airtightinteractive.com/projects/tiltviewer/pro/" target='blank'>click here</a>. To upgrade existing TiltViewer galleries to TiltViewer-Pro, replace the TiltViewer.swf file in your existing folder (at components/com_oziogallery2/skin/tiltviewer/TiltViewer.swf) with the one that comes in the Pro download.
<br />
- <a href="http://www.jeroenwijering.com/?item=JW_Image_Rotator" target='blank'>JW IMAGE ROTATOR</a> Design/Development by <a href="http://www.jeroenwijering.com" target='blank'>Jeroen Wijering</a> 
<br />
- <a href="http://www.flashden.net/item/strongaccordionstrong-v1/4332" target='blank'>ACCORDION V1</a> Design/Development by <a href="http://www.andreipotorac.com" target='blank'>Andrei Potorac</a>
<br />
- <a href="http://www.flash-gallery.org" target='blank'>FlashGallery</a> - Design/Development by <a href="http://www.realitysoftware.ca/" target='blank'>Reality Software</a>
<br />
- <a href="http://www.flshow.net/" target='blank'>Carousel</a> - Design/Development by <a href="http://www.dsi.uniroma1.it/~caminiti/" target='blank'>Saverio Caminiti</a>
<br />
- <a href="http://imagin.ro" target='blank'>Imagin</a> - Design/Development by <a href="http://ralcr.com/" target='blank'>Baluta Cristian</a>
<br /><br />
Thanks to Vamba <a href="http://www.joomlaitalia.com" target='blank'> http://www.joomlaitalia.com</a><br />
Thanks to Gmassi <a href="http://sviluppare-in-rete.blogspot.com/" target='blank'> http://sviluppare-in-rete.blogspot.com</a><br />
<h3 align="right">July 04th, 2009. Component developed by AlexRed & Ste & Vamba- <a href="http://oziogallery.joomla.it">http://oziogallery.joomla.it</a></h3><br />		
		</td>
	</tr>
</table>
			</td>
		</tr>
	</table>