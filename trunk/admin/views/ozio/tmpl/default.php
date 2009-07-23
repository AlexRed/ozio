<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
JHTML::_('behavior.tooltip');
JHTML::_('behavior.modal');
?>
<table cellspacing="0" cellpadding="0" border="0" width="100%">
	<tr>
		<td valign="top">
			<table class="adminlist">
				<tr>
					<td valign="top">
						<div id="cpanel">
							<table cellspacing="0" cellpadding="0" border="0" width="100%">
								<tr>
									<td valign="top">
										<table class="adminlist">
											<tr>
												<td align="left" width="50%" valign="top"><?php echo JText::_('COMPONENT DESCRIPTION');?></td>
											</tr>
											<tr>
												<td align="left" valign="top"><?php echo JText::_('COMPONENT INSTRUCTIONS');	?></td>
											</tr>											
											<tr>	
												<td align="left" width="50%" valign="top"><?php echo JText::_('COMPONENT VOTE');	?></td>
											</tr>
											<tr>
												<td align="left" valign="top"><?php echo JText::_('COMPONENT LANGUAGE');	?></td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</div>			
					</td>
					<td valign="top" width="50%" style="padding: 7px 0 0 5px">										
						<?php
							$title = JText::_( 'SYSTEM INFORMATION' );
							echo $this->pane->startPane( 'stat-pane' );
							echo $this->pane->startPanel( $title, 'System' );
						?>

<table class="adminlist">
	<?php echo JText::_('XML TEST1');	?>  
	<?php echo JText::_('XML TEST2');	?>
<p><?php echo JText::_('MUST BE GREEN');	?></p>
	<thead>
		<tr>
			<th width="4%" class="title" align="center">#</th>
			<th width="58%" class="title" align="center"><?php echo JText::_( 'CARTELLA' ); ?></th>
			<th width="38%" class="title" align="center"><?php echo JText::_( 'PERMISSIONS' ); ?></th>
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
		</tr>	
			<tr>
			<td align="center">9</td>
			<td align="center">plugins/content/ozio.php</td>
			<td align="center"><?php echo is_file(JPATH_SITE.DS.'plugins'.DS.'content'.DS.'ozio.php') ? 
			'<strong><font color="green">'. JText::_( 'Installed' ) .'</font></strong>' : 
			'<strong><font color="red">'. JText::_( 'Not Installed' ) .'</font></strong>'; ?></td>
		</tr>			
	</tbody>
</table>
				<?php
				$title = JText::_( 'Gallerie Pubblicate' );
				echo $this->pane->endPanel();
				echo $this->pane->startPanel( $title, 'gallerie' );

				?>	
	<table class="adminlist">
					<th></th>
					<th></th>
					<th><?php echo JText::_( 'Name' )?></th>
					<th><?php echo JText::_( 'Plugin Code' )?></th>					
				<?php
					$k = 0;
					$n = count($this->pubblicate);
					for ($i=0, $n; $i < $n; $i++) {
					$row = $this->pubblicate[$i];
					$link 		= 'index.php?option=com_menus&menutype=mainmenu&task=edit&cid[]='. $row->id;
					$gall 		= JURI::root().$row->link .'&Itemid='. $row->id;
					$pcode 		= $row->link .'&Itemid='. $row->id;
					$pcode 		= str_replace( 'index.php?option=com_oziogallery2&view=', '', $pcode );
					$img		= JURI::root().'administrator/templates/khepri/images/menu/icon-16-config.png';
					$img1		= JURI::root().'administrator/templates/khepri/images/menu/icon-16-menu.png';					
				?>
					<tr>
						<td width="20">
							<span class="editlinktip hasTip" title="<?php echo JText::_( 'Edit' ) .' - '. $row->name;?>::<?php echo JText::_( 'Clicca per effetuare delle modifiche alla voce di menu' );?>">
							<?php echo '<a href="'. $link .'"> '?>
								<img style="margin-top:4px; padding:0 6px 0 4px;" src="<?php echo $img; ?>">
							 </a> 
							</span> 
						</td>							
						<td width="20">							
							<span class="editlinktip hasTip" title="<?php echo JText::_( 'Preview' ) .' - '. $row->name;?>::<?php echo JText::_( 'Anteprima Galleria' );?>">
								<a href="<?php echo $gall.'&amp;tmpl=component'; ?>" style="cursor:pointer" class="modal" rel="{handler: 'iframe', size: {x: 850, y: 580}}"							
									<img style="margin-top:4px; padding:0 6px 0 4px;" src="<?php echo $img1; ?>">
							</span> 
						</td>
                        <td width="50%">						
							<span class="editlinktip hasTip" title="<?php echo JText::_( 'Edit' ) .' - '. $row->name;?>::<?php echo JText::_( 'Clicca per effetuare delle modifiche alla voce di menu' );?>">
							<span style="font-size:14px; padding: 0 0 0 5px; margin-top:-3px">
								<?php echo '<a href="'. $link .'"> '  . htmlspecialchars($row->name, ENT_QUOTES, 'UTF-8'); ?>
								</a>
							</span>	
							</span>							
						</td>
                        <td width="49%">						
							<span class="editlinktip hasTip" title="<?php echo JText::_( 'Content Plugin Code' ) .' - '. $row->name;?>::<?php echo JText::_( 'Copia e incolla questo codice negli articoli' );?>">
							<span style="font-size:13px; padding: 0 0 0 5px; margin-top:-3px;">
								{oziogallery <?php echo $row->id; ?>}
							</span>	
							</span>							
						</td>						
					</tr>
					<?php $k = 1 - $k; } ?>
	</table>
	
				<?php
				$title = JText::_( 'Gallerie Non Pubblicate' );
				echo $this->pane->endPanel();
				echo $this->pane->startPanel( $title, 'gallerie2' );

				?>	
	<table class="adminlist">
				<?php
					$k = 0;
					$n = count($this->nonpubblicate);
					for ($i=0, $n; $i < $n; $i++) {
					$row = $this->nonpubblicate[$i];
					$link 		= 'index.php?option=com_menus&menutype=mainmenu&task=edit&cid[]='. $row->id;
					$img		= JURI::root().'administrator/templates/khepri/images/menu/icon-16-config.png';
				?>
					<tr>
						<td width="20">
							<span class="editlinktip hasTip" title="<?php echo JText::_( 'Edit' ) .' - '. $row->name;?>::<?php echo JText::_( 'Clicca per effetuare delle modifiche alla voce di menu' );?>">
							<?php echo '<a href="'. $link .'"> '?>
								<img style="margin-top:4px; padding:0 6px 0 4px;" src="<?php echo $img; ?>">
							 </a> 
							</span> 
						</td>
                        <td width="98%">						
							<span class="editlinktip hasTip" title="<?php echo JText::_( 'Edit' ) .' - '. $row->name;?>::<?php echo JText::_( 'Clicca per effetuare delle modifiche alla voce di menu' );?>">
							<span style="font-size:14px; padding: 0 0 0 5px; margin-top:-3px">
								<?php echo '<a href="'. $link .'"> '  . htmlspecialchars($row->name, ENT_QUOTES, 'UTF-8'); ?>
								</a>
							</span>	
							</span>							
						</td>
					</tr>
					<?php $k = 1 - $k; } ?>
	</table>	
	
	
			<?php	
				echo $this->pane->endPanel();
				echo $this->pane->endPane();
			?>


			</td>
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