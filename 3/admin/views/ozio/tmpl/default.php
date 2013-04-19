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
											<?php if (!$GLOBALS["oziogallery3"]["registered"]) { ?>
												<tr><td>
														<div style="float:left;margin-right:16px;margin-left:10px;">
															<a href="http://www.opensourcesolutions.es/ext/ozio-gallery.html" target="_blank">
																<img src="<?php echo JURI::base(true) . "/components/com_oziogallery3/assets/images/buy_now.jpg"; ?>" border="0" alt="Buy now">
															</a>
														</div>
														<p><strong><?php echo JText::_("COM_OZIOGALLERY3_PURCHASE"); ?></strong></p>
													</td></tr>
												<?php } ?>
											<tr>
												<td align="left" width="50%" valign="top"><?php echo JText::_('COM_OZIOGALLERY3_COMPONENT_DESCRIPTION');?></td>
											</tr>
											<tr>
												<td align="left" valign="top"><?php echo JText::_('COM_OZIOGALLERY3_COMPONENT_INSTRUCTIONS');	?></td>
											</tr>
											<tr>
												<td align="left" width="50%" valign="top"><?php echo JText::_('COM_OZIOGALLERY3_COMPONENT_VOTE');	?></td>
											</tr>
											<tr>
												<td align="left" valign="bottom">
													<b><a href="http://forum.joomla.it/index.php/board,73.0.html" target="blank">Ozio Gallery FORUM</a></b>  -  <b><a href="http://code.google.com/p/oziogallery2/" target="blank">Ozio Gallery Google Code Project</a></b>  -  <b><a href="http://www.opensourcesolutions.es/ext/ozio-gallery.html" target="blank">Ozio Gallery download and info page</a></b>  -  <b><a href="http://www.opensourcesolutions.es/ozio" target="blank">Ozio Gallery DEMO</a></b>
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</div>
					</td>
					<td valign="top" width="60%" style="padding: 7px 0 0 5px">
						<?php
							$title = JText::_( 'COM_OZIOGALLERY3_GALLERIES_PUBLISHED' );
							echo $this->pane->startPane( 'stat-pane' );
							echo $this->pane->startPanel( $title, 'gallerie' );
						?>
						<table class="adminlist">
							<th></th>
							<th></th>
							<th><?php echo JText::_( 'COM_OZIOGALLERY3_NAME' )?></th>
							<th><?php echo JText::_( 'Gallery Skin' )?></th>
							<th><?php echo JText::_( 'COM_OZIOGALLERY3_PLUGIN_CODE' )?></th>
							<th><?php echo JText::_( 'COM_OZIOGALLERY3_MENU_GROUP' )?></th>
							<th><?php echo JText::_( 'COM_OZIOGALLERY3_XML' )?></th>
							<?php
								$k = 0;
								$n = count($this->pubblicate);
								for ($i=0, $n; $i < $n; $i++) {
									$row = $this->pubblicate[$i];
									$link 		= 'index.php?option=com_menus&menutype='.$row->menutype.'&task=item.edit&id='. (int) $row->id;
									$link2 		= 'index.php?option=com_menus&view=items&menutype='. $row->menutype;
									$link3 = NULL;
									if ($row->link == 'index.php?option=com_oziogallery3&view=01tilt3d') :
										$link3	= 'index.php?option=com_oziogallery3&amp;task=resetTilt&amp;tmpl=component';
										elseif ($row->link == 'index.php?option=com_oziogallery3&view=02flashgallery') :
										$link3	= 'index.php?option=com_oziogallery3&amp;task=resetFLG&amp;tmpl=component';
										elseif ($row->link == 'index.php?option=com_oziogallery3&view=03futura') :
										$link3	= 'index.php?option=com_oziogallery3&amp;task=resetfutura&amp;tmpl=component';
										elseif ($row->link == 'index.php?option=com_oziogallery3&view=04carousel') :
										$link3	= 'index.php?option=com_oziogallery3&amp;task=resetCar&amp;tmpl=component';
										elseif ($row->link == 'index.php?option=com_oziogallery3&view=05imagerotator') :
										$link3	= 'index.php?option=com_oziogallery3&amp;task=resetImg&amp;tmpl=component';
										elseif ($row->link == 'index.php?option=com_oziogallery3&view=06accordion') :
										$link3	= 'index.php?option=com_oziogallery3&amp;task=resetAcc&amp;tmpl=component';
										elseif ($row->link == 'index.php?option=com_oziogallery3&view=09mediagallery') :
										$link3	= 'index.php?option=com_oziogallery3&amp;task=resetmediagallery&amp;tmpl=component';
										elseif ($row->link == 'index.php?option=com_oziogallery3&view=10cooliris') :
										$link3	= 'index.php?option=com_oziogallery3&amp;task=resetcooliris&amp;tmpl=component';
										endif;
									$gall 		= JURI::root().$row->link .'&Itemid='. (int) $row->id;
									$pcode 		= $row->link .'&Itemid='. (int) $row->id;
									$pcode 		= str_replace( 'index.php?option=com_oziogallery3&view=', '', $pcode );
									$img		= JURI::root().'administrator/components/com_oziogallery3/assets/images/icon-16-config.png';
									$img1		= JURI::root().'administrator/components/com_oziogallery3/assets/images/icon-16-menu.png';

									if ($row->link == 'index.php?option=com_oziogallery3&view=01tilt3d') :
										$image	= 'logotilt.gif';
										elseif ($row->link == 'index.php?option=com_oziogallery3&view=02flashgallery') :
										$image	= 'logoflashgallery.gif';
										elseif ($row->link == 'index.php?option=com_oziogallery3&view=03futura') :
										$image	= 'logofutura.gif';
										elseif ($row->link == 'index.php?option=com_oziogallery3&view=04carousel') :
										$image	= 'logocarousel.gif';
										elseif ($row->link == 'index.php?option=com_oziogallery3&view=05imagerotator') :
										$image	= 'logorotator.gif';
										elseif ($row->link == 'index.php?option=com_oziogallery3&view=06accordion') :
										$image	= 'logoaccordion.gif';
										elseif ($row->link == 'index.php?option=com_oziogallery3&view=07flickrslidershow') :
										$image	= 'logoflic.gif';
										elseif ($row->link == 'index.php?option=com_oziogallery3&view=08flickrphoto') :
										$image	= 'logoflic.gif';
										elseif ($row->link == 'index.php?option=com_oziogallery3&view=09mediagallery') :
										$image	= 'logomediagallery.gif';
										elseif ($row->link == 'index.php?option=com_oziogallery3&view=10cooliris') :
										$image	= 'logocooliris.gif';
										elseif ($row->link == 'index.php?option=com_oziogallery3&view=11pictobrowser') :
										$image	= 'logoflickr.gif';
										elseif ($row->link == 'index.php?option=com_oziogallery3&view=12pictobrowser2') :
										$image	= 'logopicasa.gif';
										elseif ($row->link == 'index.php?option=com_oziogallery3&view=14pupngoo' || $row->link == 'index.php?option=com_oziogallery3&view=00fuerte') :
										$image	= 'logogoogle.gif';
										endif;
								?>
								<tr>
									<td width="16">
										<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_OZIOGALLERY3_EDIT' ) .' - '. $row->title;?>::<?php echo JText::_( 'COM_OZIOGALLERY3_CLICK_TO_EDIT_THE_MENU_ITEM' );?>">
											<a href="<?php echo $link ?>">
												<img style="margin-top:4px; padding:0 6px 0 4px;" src="<?php echo $img; ?>">
											</a>
										</span>
									</td>
									<td width="16">
										<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_OZIOGALLERY3_PREVIEW' ) .' - '. $row->title;?>::<?php echo JText::_( 'COM_OZIOGALLERY3_GALLERY_PREVIEW' );?>">
											<a href="<?php echo $gall.'&amp;tmpl=component'; ?>" style="cursor:pointer" class="modal" rel="{handler: 'iframe', size: {x: 850, y: 580}}"	>
											<img style="margin-top:4px; padding:0 6px 0 4px;" src="<?php echo $img1; ?>">
										</span>
									</td>
									<td width="37%">
										<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_OZIOGALLERY3_EDIT' ) .' - '. $row->title;?>::<?php echo JText::_( 'COM_OZIOGALLERY3_CLICK_TO_EDIT_THE_MENU_ITEM' );?>">
											<span style="font-size:13px; padding: 0 0 0 5px; margin-top:-3px">
												<a href="<?php echo $link ?>"><?php echo htmlspecialchars($row->title, ENT_QUOTES, 'UTF-8'); ?>
												</a>
											</span>
										</span>
									</td>
									<td width="75">
										<img style="margin-top:4px; padding:0 6px 0 4px; width: 65px;" src="<?php echo JURI::root().'administrator/components/com_oziogallery3/assets/images/'.$image; ?>">
									</td>
									<td width="18%">
										<?php echo is_file(JPATH_SITE.DS.'plugins'.DS.'content'.DS.'ozio'.DS.'ozio.php') ?
											'<span class="editlinktip hasTip" title="'. JText::_( 'COM_OZIOGALLERY3_CONTENT_PLUGIN_CODE' ) .' - '. $row->title .' :: '. JText::_( 'COM_OZIOGALLERY3_COPY_AND_PASTE_THIS_CODE_IN_THE_ARTICLES' ) .'">
											<input class="text_area" type="text" size="20" value="{oziogallery '. $row->id .'}" />
											</span>' :
											'<span class="editlinktip hasTip" title="'. JText::_( 'COM_OZIOGALLERY3_CONTENT_PLUGIN_CODE_NO' ) .' :: '. JText::_( 'COM_OZIOGALLERY3_COPY_AND_PASTE_THIS_CODE_IN_THE_ARTICLES_NO' ) .'"><strong><font color="red">'. JText::_( 'COM_OZIOGALLERY3_NOT_INSTALLED' ) .'</font></strong>'; ?>
									</td>
									<td width="25%">
										<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_OZIOGALLERY3_EDIT' ) .' - '. $row->nomemenu;?>::<?php echo JText::_( 'COM_OZIOGALLERY3_CLICK_TO_EDIT_THE_MENU_GROUP' );?>">
											<span style="font-size:12px; padding: 0 0 0 5px; margin-top:-3px">
												<?php echo '<a href="'. $link2 .'"> '  . htmlspecialchars($row->nomemenu, ENT_QUOTES, 'UTF-8'); ?>
												</a>
											</span>
										</span>
									</td>
									<td width="5%">
										<div class="button2-left">
											<?php if ($link3) { ?>
												<div class="blank"><a class="modal" title="<?php echo JText::_('COM_OZIOGALLERY3_RESET_XML') ?>"  href="<?php echo $link3 ?>" rel="{handler: 'iframe', size: {x: 450, y: 115}}"><?php echo JText::_('COM_OZIOGALLERY3_RESET') ?></a></div>
												<?php } ?>
										</div>
									</td>
								</tr>
								<div class="clr"><br /></div>
								<?php $k = 1 - $k; } ?>
							<div class="clr"><br /></div>
						</table>

						<?php
							$title = JText::_( 'COM_OZIOGALLERY3_GALLERIES_NOT_PUBLISHED' );
							echo $this->pane->endPanel();
							echo $this->pane->startPanel( $title, 'gallerie2' );

						?>
						<table class="adminlist">
							<th></th>
							<th><?php echo JText::_( 'COM_OZIOGALLERY3_NAME' )?></th>
							<?php
								$k = 0;
								$n = count($this->nonpubblicate);
								for ($i=0, $n; $i < $n; $i++) {
									$row = $this->nonpubblicate[$i];

									$link 		= 'index.php?option=com_menus&menutype='.$row->menutype.'&task=item.edit&id='. (int) $row->id;
									$img		= JURI::root().'administrator/components/com_oziogallery3/assets/images/icon-16-config.png';
								?>
								<tr>
									<td width="20">
										<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_OZIOGALLERY3_EDIT' ) .' - '. $row->title;?>::<?php echo JText::_( 'COM_OZIOGALLERY3_CLICK_TO_EDIT_THE_MENU_ITEM' );?>">
											<a href="<?php echo $link ?>">
												<img style="margin-top:4px; padding:0 6px 0 4px;" src="<?php echo $img; ?>">
											</a>
										</span>
									</td>
									<td width="98%">
										<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_OZIOGALLERY3_EDIT' ) .' - '. $row->title;?>::<?php echo JText::_( 'COM_OZIOGALLERY3_CLICK_TO_EDIT_THE_MENU_ITEM' );?>">
											<span style="font-size:14px; padding: 0 0 0 5px; margin-top:-3px">
												<a href="<?php echo $link ?>"><?php echo htmlspecialchars($row->title, ENT_QUOTES, 'UTF-8'); ?>
												</a>
											</span>
										</span>
									</td>
								</tr>
								<div class="clr"><br /></div>
								<?php $k = 1 - $k; } ?>
							<div class="clr"><br /></div>
						</table>


						<?php
							$title = JText::_( 'COM_OZIOGALLERY3_GALLERIES_TRASHED' );
							echo $this->pane->endPanel();
							echo $this->pane->startPanel( $title, 'gallerie3' );

						?>
						<table class="adminlist">
							<th></th>
							<th><?php echo JText::_( 'COM_OZIOGALLERY3_NAME' )?></th>
							<?php
								$k = 0;
								$n = count($this->cestinate);
								for ($i=0, $n; $i < $n; $i++) {
									$row = $this->cestinate[$i];
									$link 		= 'index.php?option=com_menus&menutype='.$row->menutype.'&task=item.edit&id='. (int) $row->id;
									$img		= JURI::root().'administrator/components/com_oziogallery3/assets/images/icon-16-config.png';
								?>
								<tr>
									<td width="20">
										<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_OZIOGALLERY3_EDIT' ) .' - '. $row->title;?>::<?php echo JText::_( 'COM_OZIOGALLERY3_CLICK_TO_EDIT_THE_MENU_ITEM' );?>">
											<a href="<?php echo $link ?>">
												<img style="margin-top:4px; padding:0 6px 0 4px;" src="<?php echo $img; ?>">
											</a>
										</span>
									</td>
									<td width="98%">
										<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_OZIOGALLERY3_EDIT' ) .' - '. $row->title;?>::<?php echo JText::_( 'COM_OZIOGALLERY3_CLICK_TO_EDIT_THE_MENU_ITEM' );?>">
											<span style="font-size:14px; padding: 0 0 0 5px; margin-top:-3px">
												<a href="<?php echo $link ?>"><?php echo htmlspecialchars($row->title, ENT_QUOTES, 'UTF-8'); ?>
												</a>
											</span>
										</span>
									</td>
								</tr>
								<div class="clr"><br /></div>
								<?php $k = 1 - $k; } ?>
							<div class="clr"><br /></div>
						</table>


						<?php
							echo $this->pane->endPanel();
							echo $this->pane->endPane();
						?>

					</td>
				</tr>
			</table>
			<fieldset class="adminform">
				<legend><?php echo JText::_( 'COM_OZIOGALLERY3_SYSTEM_INFORMATION' ); ?></legend>
				<table class="adminlist">

					<p><?php echo JText::_('COM_OZIOGALLERY3_MUST_BE_GREEN');	?></p>
					<thead>
						<tr>
							<th width="4%" class="title" align="center">#</th>
							<th width="58%" class="title" align="center">Server info</th>
							<th width="38%" class="title" align="center"></th>
						</tr>
					</thead>
					<tbody>

						<tr>
							<td align="center">1</td>
							<td align="center">plugins/editors-xtd/oziogallery/oziogallery.php</td>
							<td align="center"><?php echo is_file(JPATH_SITE.DS.'plugins'.DS.'editors-xtd'.DS.'oziogallery'.DS.'oziogallery.php') ?
									'<strong><font color="green">'. JText::_( 'COM_OZIOGALLERY3_INSTALLED' ) .'</font></strong>' :
								'<strong><font color="red">'. JText::_( 'COM_OZIOGALLERY3_NOT_INSTALLED' ) .'</font></strong>'; ?></td>
						</tr>
						<tr>
							<td align="center">2</td>
							<td align="center">plugins/content/ozio/ozio.php</td>
							<td align="center"><?php echo is_file(JPATH_SITE.DS.'plugins'.DS.'content'.DS.'ozio'.DS.'ozio.php') ?
									'<strong><font color="green">'. JText::_( 'COM_OZIOGALLERY3_INSTALLED' ) .'</font></strong>' :
								'<strong><font color="red">'. JText::_( 'COM_OZIOGALLERY3_NOT_INSTALLED' ) .'</font></strong>'; ?></td>
						</tr>
					</tbody>
				</table>
			</fieldset>
			<fieldset class="adminform">
				<legend><?php echo JText::_( 'COM_OZIOGALLERY3_CREDITS' ); ?></legend>
				<table class="admintable">
					<tr>
						<td align="left">

							<br />Skin Fuerte is based on <a href="http://www.buildinternet.com/project/supersized/" target='blank'>Supersized</a> Design/Development by <a href="http://buildinternet.com/" target='blank'> Build Internet project by Sam Dunn of One Mighty Roar</a>.
							<br />
							<br /><br />
							Thanks to Vamba <a href="http://www.joomlaitalia.com" target='blank'> http://www.joomlaitalia.com</a><br />
							Thanks to Gmassi <a href="http://sviluppare-in-rete.blogspot.com/" target='blank'> http://sviluppare-in-rete.blogspot.com</a><br />
							<h3 align="right">June 02nd, 2010. Component developed by AlexRed & Ste & Vamba - <a href="http://www.opensourcesolutions.es/ext/ozio-gallery.html">opensourcesolutions.es</a></h3><br />
						</td>
					</tr>
				</table>
			</fieldset>
		</td>
	</tr>
	</table>
