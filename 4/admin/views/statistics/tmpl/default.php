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
						$albumvisibility='public';
						if ($albumvisibility=='limited'){
							//lo aggiungo come gli altri g_parameters
							
							$row->params=array(
								'userid'=>$result->get("ozio_nano_userID", ""),
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
						$albumvisibility="public";
						if ($kind=='picasa' && $albumvisibility=='limited'){
							//lo aggiungo come gli altri g_parameters
							
							$row->params=array(
								'userid'=>$result->get("ozio_nano_userID", ""),
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
						$albumvisibility="public";
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
											userID: <?php echo json_encode($item->params->get("ozio_nano_userID", "")); ?>,
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
	