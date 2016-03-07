<?php
	/**
	* This file is part of Ozio Gallery 4
	*
	* Ozio Gallery 4 is free software: you can redistribute it and/or modify
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
	JHtml::_('behavior.tooltip');
	JHtml::_('behavior.modal');
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
												<tr><td><div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">×</button>
														<div style="float:left;margin-right:16px;margin-left:10px;">
															<a href="http://www.opensourcesolutions.es/ext/ozio-gallery.html" target="_blank">
																<img src="<?php echo JUri::base(true) . "/components/com_oziogallery3/assets/images/buy_now.jpg"; ?>" border="0" alt="Buy now">
															</a>
														</div>
														<p><strong><?php echo JText::_("COM_OZIOGALLERY3_PURCHASE"); ?></strong></p></div> 
													</td></tr>
												<?php } ?>
											<tr>
												<td align="left" width="50%" valign="top"><?php echo JText::_('COM_OZIOGALLERY3_COMPONENT_DESCRIPTION');?><br /></td>
											</tr>
											<tr>
												<td align="left" valign="top"><br /><?php echo JText::_('COM_OZIOGALLERY3_COMPONENT_INSTRUCTIONS');	?><br /></td>
											</tr>
											<tr>
												<td align="left" width="50%" valign="top"><br /><div class="alert alert-success"><span aria-hidden="true" class="icon-thumbs-up"></span>  <?php echo JText::_('COM_OZIOGALLERY3_COMPONENT_VOTE');	?></div></td>
											</tr>
											<tr>
												<td align="left" valign="bottom">

												    <a href="http://forum.joomla.it/index.php/board,73.0.html" target="blank" class="btn btn-large btn-warning"><i class="icon-users"></i>  FORUM</a>
												    <a href="https://github.com/AlexRed/ozio" target="blank" class="btn btn-large btn-inverse"><i class="icon-cogs"></i>  GitHub</a>
												    <a href="http://www.opensourcesolutions.es/ext/ozio-gallery.html" target="blank" class="btn btn-large btn-success"><i class="icon-download"></i>  INFO & DOWNLOAD</a>
												    <a href="http://www.opensourcesolutions.es/ext/ozio-gallery/demo.html" target="blank" class="btn btn-large btn-info"><i class="icon-eye"></i>  DEMO</a>
												    <br /><br />
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</div>
					</td>
				</tr>
			</table>
			<fieldset class="adminform">
				<legend><?php echo JText::_( 'COM_OZIOGALLERY3_STATISTICHE' ); ?></legend>
				<?php 
				
				$k = 0;
				$n = count($this->pubblicate);
				$g_parameters=array();
				
				for ($i=0, $n; $i < $n; $i++) {
					$row = $this->pubblicate[$i];
					if ( $row->link == 'index.php?option=com_oziogallery3&view=00fuerte'){
						$row->params=json_decode($row->params,true);
						$row->skin='00fuerte';
						$row->link='index.php?option=com_oziogallery3&view=00fuerte&Itemid='.$row->id;
						$g_parameters[]=$row;
					}
					if ( $row->link == 'index.php?option=com_oziogallery3&view=lightgallery'){
						$result = new JRegistry;
						$result->loadString($row->params);
						if ($result->get("source_kind", "photo")=='photo'){
							$row->params=json_decode($row->params,true);
							$row->params['albumvisibility']='public';
							$row->skin='lightgallery';
							$row->link='index.php?option=com_oziogallery3&view=lightgallery&Itemid='.$row->id;
							$g_parameters[]=$row;
						}
					}
					if ( $row->link == 'index.php?option=com_oziogallery3&view=jgallery'){
						$result = new JRegistry;
						$result->loadString($row->params);
						$albumvisibility=$result->get("albumvisibility", "public");
						if ($albumvisibility=='limited'){
							//lo aggiungo come gli altri g_parameters
							
							$row->params=array(
								'userid'=>$result->get("ozio_nano_userID", "110359559620842741677"),
								'albumvisibility'=>'limited',
								'limitedalbum'=>$result->get("limitedalbum", ""),
								'limitedpassword'=>$result->get("limitedpassword", "")
							);
							$row->skin='jgallery';
							$deeplink='';
							if (intval($result->get("ozio_nano_locationHash", "1"))==1){
								$deeplink='#'.$result->get("limitedalbum", "");
							}							
							$row->link='index.php?option=com_oziogallery3&view=nano&Itemid='.$row->id.$deeplink;

							$g_parameters[]=$row;
						}
					}
					if ( $row->link == 'index.php?option=com_oziogallery3&view=nano'){
						$result = new JRegistry;
						$result->loadString($row->params);
						$kind=$result->get("ozio_nano_kind", "picasa");
						$albumvisibility=$result->get("albumvisibility", "public");
						if ($kind=='picasa' && $albumvisibility=='limited'){
							//lo aggiungo come gli altri g_parameters
							
							$row->params=array(
								'userid'=>$result->get("ozio_nano_userID", "110359559620842741677"),
								'albumvisibility'=>'limited',
								'limitedalbum'=>$result->get("limitedalbum", ""),
								'limitedpassword'=>$result->get("limitedpassword", "")
							);
							$row->skin='nano';
							$deeplink='';
							if (intval($result->get("ozio_nano_locationHash", "1"))==1){
								$deeplink='#nanogallery/nanoGallery/'.$result->get("limitedalbum", "");
							}
								
							$row->link='index.php?option=com_oziogallery3&view=nano&Itemid='.$row->id.$deeplink;

							$g_parameters[]=$row;
						}
						
					}
				}
				echo '<script type="text/javascript">var g_parameters='.json_encode($g_parameters).';</script>';
				
				echo '<script type="text/javascript">g_flickrThumbSizeStr="sq";g_list_nano_options=[];</script>';
				
				echo '<script type="text/javascript">';
				for ($i=0, $n; $i < $n; $i++) {
					$row = $this->pubblicate[$i];
					if ( $row->link == 'index.php?option=com_oziogallery3&view=nano'){
						$item = $row;
						$result = new JRegistry;
						$result->loadString($item->params);
						
						$kind=$result->get("ozio_nano_kind", "picasa");
						$albumvisibility=$result->get("albumvisibility", "public");
						if ($kind=='picasa' && $albumvisibility=='limited'){
							//già aggiunto sopra
						}else{
						
						
							$item->params = $result;
						
							$link = 'index.php?option=com_oziogallery3&view=nano&Itemid='.$item->id;
							?>
									//nano
									g_list_nano_options[g_list_nano_options.length]={
											thumbSize:64,
											album_local_url:<?php echo json_encode($link); ?>,
											g_flickrApiKey:"2f0e634b471fdb47446abcb9c5afebdc",
											locationHash: <?php echo json_encode(intval($item->params->get("ozio_nano_locationHash", "1"))); ?>,
											kind: <?php echo json_encode($item->params->get("ozio_nano_kind", "picasa")); ?>,
											userID: <?php echo json_encode($item->params->get("ozio_nano_userID", "110359559620842741677")); ?>,
											blackList: <?php echo json_encode($item->params->get("ozio_nano_blackList", "Scrapbook|profil|2013-")); ?>,
											whiteList: <?php echo json_encode($item->params->get("ozio_nano_whiteList", "")); ?>,
											<?php
											$non_printable_separator="\x16";
											$new_non_printable_separator="|!|";
											$albumList=$item->params->get("ozio_nano_albumList", array());
											if (!empty($albumList) && is_array($albumList) ){
												if (count($albumList)==1){
													if (strpos($albumList[0],$non_printable_separator)!==FALSE){
														list($albumid,$title)=explode($non_printable_separator,$albumList[0]);
													}else{
														list($albumid,$title)=explode($new_non_printable_separator,$albumList[0]);
													}
													
													
													$kind=$item->params->get("ozio_nano_kind", "picasa");
													if ($kind=='picasa'){
														echo 'album:'.json_encode($albumid).",\n";
													}else{
														echo 'photoset:'.json_encode($albumid).",\n";
													}
												}else{
													$albumTitles=array();
													foreach ($albumList as $a){
														if (strpos($a,$non_printable_separator)!==FALSE){
															list($albumid,$title)=explode($non_printable_separator,$a);
														}else{
															list($albumid,$title)=explode($new_non_printable_separator,$a);
														}
														$albumTitles[]=$title;
													}
													echo 'albumList:'.json_encode(implode('|',$albumTitles)).",\n";
												}
											}		
											?>
										};
									<?php						
						
						}
					}
				}
				echo '</script>';
				
				?>
				<div id="remainingphotos"></div><br />
				<table id="first10albums" class="table">

					<p><?php echo JText::_('COM_OZIOGALLERY3_FIRST_10_ALBUMS');	?></p>
					<thead>
						<tr>
							<th width="80%" class="title" align="center">Title</th>
							<th width="20%" class="title" align="center">Views</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
				<table id="first10photos" class="table">

					<p><?php echo JText::_('COM_OZIOGALLERY3_FIRST_10_PHOTOS');	?></p>
					<thead>
						<tr>
							<th class="title" align="center">Thumb</th>
							<th class="title" align="center">Title</th>
							<th width="40%" class="title" align="center">Summary</th>
							<th width="15%" class="title" align="center">Album</th>
							<th width="5%" class="title" align="center">Views</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
								
			</fieldset>			
			
			<fieldset class="adminform">
				<legend><?php echo JText::_( 'COM_OZIOGALLERY3_SYSTEM_INFORMATION' ); ?></legend>
				<table class="table">

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
							<td align="center"><?php echo is_file(JPATH_SITE.'/plugins/editors-xtd/oziogallery/oziogallery.php') ?
									'<strong><font color="green">'. JText::_( 'COM_OZIOGALLERY3_INSTALLED' ) .'</font></strong>' :
								'<strong><font color="red">'. JText::_( 'COM_OZIOGALLERY3_NOT_INSTALLED' ) .'</font></strong>'; ?></td>
						</tr>
						<tr>
							<td align="center">2</td>
							<td align="center">plugins/content/ozio/ozio.php</td>
							<td align="center"><?php echo is_file(JPATH_SITE.'/plugins/content/ozio/ozio.php') ?
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
							Skin Fuerte is based on <a href="http://www.buildinternet.com/project/supersized/" target='blank'>Supersized</a> Design/Development by <a href="http://buildinternet.com/" target='blank'> Build Internet project by Sam Dunn of One Mighty Roar</a>.
							<br />Skin Nano is based on <a href="http://nanogallery.brisbois.fr/" target="blank">nanoGALLERY</a> Design/Development by <a href="http://www.brisbois.fr/" target="blank">Christophe Brisbois</a>
							<br />Skin jGallery is based on <a href="http://jgallery.jakubkowalczyk.pl/" target="blank">jGallery</a> Design/Development by Jakub Kowalczyk
							<br />Skin lightGallery is based on <a href="http://sachinchoolur.github.io/lightGallery/" target="blank">lightGallery</a> Design/Development by Sachin N
							<br />
							Thanks to Vamba <a href="http://www.joomlaitalia.com" target='blank'> http://www.joomlaitalia.com</a><br />
							Thanks to Gmassi <a href="http://sviluppare-in-rete.blogspot.com/" target='blank'> http://sviluppare-in-rete.blogspot.com</a><br />
							<h3 align="right">June 02nd, 2010. Component developed by AlexRed & Ste & Vamba - <a href="http://www.opensourcesolutions.es/ext/ozio-gallery.html">opensourcesolutions.es</a></h3><br />
							<h3 class="module-title">Lingue, Idiomas, Idiomes, Linguagem, Languages...</h3>
							<div><blockquote>
							<ul>
							<li><strong>Italiano </strong>: <em>- AlexRed - </em> <a  href="http://www.alexred.com">www.alexred.com</a></li>
							<li><strong>English </strong>: <em> - Ste - </em> <a  href="http://www.stellainformatica.com">www.stellainformatica.com</a></li>
							<li><strong>Español </strong>: <em> - Ivan Sola (isolabig) - </em></li>
							<li><strong>Français </strong>: <em> - Franck LÉCUVIER - </em></li>
							<li><strong>Deutsch </strong>: <em> - Alexander Seppi | kreatif multimedia - </em> <a  href="http://www.kreatif-multimedia.com">www.kreatif-multimedia.com</a></li>
							<li><strong>Português </strong>: <em> - Paulo Ferreira - </em></li>
							<li><strong>Russian </strong>: <em> - Vika Marchetti - </em> <a  href="http://www.svadbaitalia.ru">www.svadbaitalia.ru</a></li>
							</ul></blockquote>
							</div>
						</td>
					</tr>
				</table>
			</fieldset>
		</td>
	</tr>
	</table>

	
	
<div id="album-info" class="modal hide fade">
	<div class="modal-header"><button class="close" type="button" data-dismiss="modal">×</button>
		<h3>Info</h3>
	</div>
	<div class="modal-body" style="overflow:hidden;">
	</div>
	<div class="modal-footer">
		<span class="btn" data-dismiss="modal"><i class="icon-cancel"></i> <?php echo JText::_('JLIB_HTML_BEHAVIOR_CLOSE');?></span>
    </div>
</div>
	