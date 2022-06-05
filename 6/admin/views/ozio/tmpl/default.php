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

use Joomla\CMS\HTML\HTMLHelper;
	HTMLHelper::_('jquery.framework');
	
?>
<div class="row">
<div id="j-sidebar-container" class="col-md-3">
    <?php echo $this->sidebar; ?>
</div>
<div id="j-main-container" class="col-md-9">
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
												<td align="left" valign="bottom">
												    <a href="index.php?option=com_oziogallery4&view=setup" class="btn btn-large btn-success"><?php echo JText::_("COM_OZIOGALLERY4_SETUP_LINK"); ?></a>
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
				<legend><?php echo JText::_( 'COM_OZIOGALLERY4_SYSTEM_INFORMATION' ); ?></legend>
				<table class="table">

					<p><?php echo JText::_('COM_OZIOGALLERY4_MUST_BE_GREEN');	?></p>
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
									'<strong><font color="green">'. JText::_( 'COM_OZIOGALLERY4_INSTALLED' ) .'</font></strong>' :
								'<strong><font color="red">'. JText::_( 'COM_OZIOGALLERY4_NOT_INSTALLED' ) .'</font></strong>'; ?></td>
						</tr>
						<tr>
							<td align="center">2</td>
							<td align="center">plugins/content/ozio/ozio.php</td>
							<td align="center"><?php echo is_file(JPATH_SITE.'/plugins/content/ozio/ozio.php') ?
									'<strong><font color="green">'. JText::_( 'COM_OZIOGALLERY4_INSTALLED' ) .'</font></strong>' :
								'<strong><font color="red">'. JText::_( 'COM_OZIOGALLERY4_NOT_INSTALLED' ) .'</font></strong>'; ?></td>
						</tr>
					</tbody>
				</table>
			</fieldset>
			<fieldset class="adminform">
				<legend><?php echo JText::_( 'COM_OZIOGALLERY4_CREDITS' ); ?></legend>
				<table class="admintable">
					<tr>
						<td align="left">
							Skin Fuerte is based on <a href="http://www.buildinternet.com/project/supersized/" target='blank'>Supersized</a> Design/Development by <a href="http://buildinternet.com/" target='blank'> Build Internet project by Sam Dunn of One Mighty Roar</a>.
							<br />Skin Nano is based on <a href="http://nanogallery.brisbois.fr/" target="blank" rel="noopener noreferrer">nanoGALLERY</a> Design/Development by <a href="http://www.brisbois.fr/" target="blank" rel="noopener noreferrer">Christophe Brisbois</a>
							<br />Skin jGallery is based on <a href="http://jgallery.jakubkowalczyk.pl/" target="blank" rel="noopener noreferrer">jGallery</a> Design/Development by Jakub Kowalczyk
							<br />Skin lightGallery is based on <a href="http://sachinchoolur.github.io/lightGallery/" target="blank" rel="noopener noreferrer">lightGallery</a> Design/Development by Sachin N
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
</div></div>
	
	