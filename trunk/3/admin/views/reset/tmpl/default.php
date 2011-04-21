<?php
/**
* This file is part of Ozio Gallery 3.
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
?>
	<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
			<td valign="top">
		<fieldset class="adminform">
		<legend><?php echo JText::_( 'Reset XML' ); ?></legend>
		<table class="adminlist">
			<thead>
				<tr>
					<th align="left">
						<?php echo JText::_( 'Galleria' ); ?>
					</th>
					<th align="center">
						<?php echo JText::_( 'Comando' ); ?>
					</th>					
					<th align="left">
						<?php echo JText::_( 'File' ); ?>
					</th>	
				</tr>
			</thead>
		<tr>
			<td width="20%" nowrap="nowrap" >
				<label for="title">
					<span class="editlinktip hasTip" title="<?php echo JText::_ ( 'NOTES' ); ?>::<?php echo JText::_ ( 'Accordion Reset' );?>">						
							<span style="font-size:16px; color:#004080; font-weight:bold;"><?php echo JText::_( 'Accordion' ); ?></span>
					</span>				
				</label>
			</td>
			<th width="80" align="center">
				<span class="editlinktip hasTip" title="<?php echo JText::_ ( 'Svuota' ); ?>::<?php echo JText::_ ( 'Accordion' );?> <?php echo JText::_ ( 'XML' );?>">
					<a href="<?php echo $this->link1 ?>"> <img src="<?php echo $this->img?>" width="30"> </a>
				</span>	
			</td>
			<td width="80%" nowrap="nowrap">
			<span class="editlinktip hasTip" title="<?php echo JText::_ ( 'Accordion' ); ?>::<?php echo JText::_ ( 'XML Files' );?>">	
				<?php echo $this->accordion; ?>
			</span>	
			</td>			
			
		</tr>

		<tr>
			<td width="20%" nowrap="nowrap"  >
				<label for="title">
					<span class="editlinktip hasTip" title="<?php echo JText::_ ( 'NOTES' ); ?>::<?php echo JText::_ ( 'Carousel Reset' );?>">						
							<span style="font-size:16px; color:#004080; font-weight:bold;"><?php echo JText::_( 'Carousel' ); ?></span>
					</span>				
				</label>
			</td>
			<th width="80" align="center">
				<span class="editlinktip hasTip" title="<?php echo JText::_ ( 'Svuota' ); ?>::<?php echo JText::_ ( 'Carousel' );?> <?php echo JText::_ ( 'XML' );?>">
					<a href="<?php echo $this->link2 ?>"> <img src="<?php echo $this->img?>" width="30"> </a>
				</span>					
			</td>
			<td width="80%" nowrap="nowrap">
			<span class="editlinktip hasTip" title="<?php echo JText::_ ( 'Carousel' ); ?>::<?php echo JText::_ ( 'XML Files' );?>">
				<?php echo $this->carousel; ?>
	
            </span>					
			</td>			
			
		</tr>
		<tr>
			<td width="20%" nowrap="nowrap"  >
				<label for="title">
					<span class="editlinktip hasTip" title="<?php echo JText::_ ( 'NOTES' ); ?>::<?php echo JText::_ ( 'FlashGallery Reset' );?>">						
							<span style="font-size:16px; color:#004080; font-weight:bold;"><?php echo JText::_( 'FlashGallery' ); ?></span>
					</span>				
				</label>
			</td>
			<th width="80" align="center">
				<span class="editlinktip hasTip" title="<?php echo JText::_ ( 'Svuota' ); ?>::<?php echo JText::_ ( 'FlashGallery' );?> <?php echo JText::_ ( 'XML' );?>">
					<a href="<?php echo $this->link3 ?>"> <img src="<?php echo $this->img?>" width="30"> </a>
				</span>				
			</td>
			<td width="80%" nowrap="nowrap">
			<span class="editlinktip hasTip" title="<?php echo JText::_ ( 'FlashGallery' ); ?>::<?php echo JText::_ ( 'XML Files' );?>">
				<?php echo $this->flashgallery; ?>	
            </span>						
			</td>			
			
		</tr>
		<tr>
			<td width="20%" nowrap="nowrap"  >
				<label for="title">
					<span class="editlinktip hasTip" title="<?php echo JText::_ ( 'NOTES' ); ?>::<?php echo JText::_ ( 'Imagerotator Reset' );?>">						
							<span style="font-size:16px; color:#004080; font-weight:bold;"><?php echo JText::_( 'Imagerotator' ); ?></span>	
					</span>				
				</label>
			</td>
			<th width="80" align="center">
				<span class="editlinktip hasTip" title="<?php echo JText::_ ( 'Svuota' ); ?>::<?php echo JText::_ ( 'Imagerotator' );?> <?php echo JText::_ ( 'XML' );?>">
					<a href="<?php echo $this->link4 ?>"> <img src="<?php echo $this->img?>" width="30"> </a>
				</span>				
			</td>
			<td width="80%" nowrap="nowrap">
			<span class="editlinktip hasTip" title="<?php echo JText::_ ( 'Imagerotator' ); ?>::<?php echo JText::_ ( 'XML Files' );?>">
				<?php echo $this->imagerotator; ?>	
            </span>						
			</td>			
			
		</tr>
		<tr>
			<td width="20%" nowrap="nowrap"  >
				<label for="title">
					<span class="editlinktip hasTip" title="<?php echo JText::_ ( 'NOTES' ); ?>::<?php echo JText::_ ( 'Tilt 3D Gallery Reset' );?>">						
							<span style="font-size:16px; color:#004080; font-weight:bold;"><?php echo JText::_( 'Tilt 3D Gallery' ); ?></span>	
					</span>				
				</label>
			</td>
			<th width="80" align="center">
				<span class="editlinktip hasTip" title="<?php echo JText::_ ( 'Svuota' ); ?>::<?php echo JText::_ ( 'Tilt 3D Gallery' );?> <?php echo JText::_ ( 'XML' );?>">
					<a href="<?php echo $this->link5 ?>"> <img src="<?php echo $this->img?>" width="30"> </a>
				</span>				
			</td>
			<td width="80%" nowrap="nowrap">
			<span class="editlinktip hasTip" title="<?php echo JText::_ ( 'Tild 3D Gallery' ); ?>::<?php echo JText::_ ( 'XML Files' );?>">
				<?php echo $this->tilt; ?>	
            </span>				
			</td>			
			
		</tr>
		<tr>
			<td width="20%" nowrap="nowrap"  >
				<label for="title">
					<span class="editlinktip hasTip" title="<?php echo JText::_ ( 'NOTES' ); ?>::<?php echo JText::_ ( 'mediagallery Reset' );?>">						
							<span style="font-size:16px; color:#004080; font-weight:bold;"><?php echo JText::_( 'mediagallery Gallery' ); ?></span>	
					</span>				
				</label>
			</td>
			<th width="80" align="center">
				<span class="editlinktip hasTip" title="<?php echo JText::_ ( 'Svuota' ); ?>::<?php echo JText::_ ( 'mediagallery Gallery' );?> <?php echo JText::_ ( 'XML' );?>">
					<a href="<?php echo $this->link6 ?>"> <img src="<?php echo $this->img?>" width="30"> </a>
				</span>				
			</td>
			<td width="80%" nowrap="nowrap">
			<span class="editlinktip hasTip" title="<?php echo JText::_ ( 'mediagallery Gallery' ); ?>::<?php echo JText::_ ( 'XML Files' );?>">
				<?php echo $this->mediagallery; ?>	
            </span>				
			</td>			
			
		</tr>
		<tr>
			<td width="20%" nowrap="nowrap"  >
				<label for="title">
					<span class="editlinktip hasTip" title="<?php echo JText::_ ( 'NOTES' ); ?>::<?php echo JText::_ ( 'futura Reset' );?>">						
							<span style="font-size:16px; color:#004080; font-weight:bold;"><?php echo JText::_( 'futura Gallery' ); ?></span>	
					</span>				
				</label>
			</td>
			<th width="80" align="center">
				<span class="editlinktip hasTip" title="<?php echo JText::_ ( 'Svuota' ); ?>::<?php echo JText::_ ( 'futura Gallery' );?> <?php echo JText::_ ( 'XML' );?>">
					<a href="<?php echo $this->link6 ?>"> <img src="<?php echo $this->img?>" width="30"> </a>
				</span>				
			</td>
			<td width="80%" nowrap="nowrap">
			<span class="editlinktip hasTip" title="<?php echo JText::_ ( 'futura Gallery' ); ?>::<?php echo JText::_ ( 'XML Files' );?>">
				<?php echo $this->futura; ?>	
            </span>				
			</td>			
			
		</tr>	
		<tr>
			<td width="20%" nowrap="nowrap"  >
				<label for="title">
					<span class="editlinktip hasTip" title="<?php echo JText::_ ( 'NOTES' ); ?>::<?php echo JText::_ ( 'cooliris Reset' );?>">						
							<span style="font-size:16px; color:#004080; font-weight:bold;"><?php echo JText::_( 'cooliris Gallery' ); ?></span>	
					</span>				
				</label>
			</td>
			<th width="80" align="center">
				<span class="editlinktip hasTip" title="<?php echo JText::_ ( 'Svuota' ); ?>::<?php echo JText::_ ( 'cooliris Gallery' );?> <?php echo JText::_ ( 'XML' );?>">
					<a href="<?php echo $this->link7 ?>"> <img src="<?php echo $this->img?>" width="30"> </a>
				</span>				
			</td>
			<td width="80%" nowrap="nowrap">
			<span class="editlinktip hasTip" title="<?php echo JText::_ ( 'cooliris Gallery' ); ?>::<?php echo JText::_ ( 'XML Files' );?>">
				<?php echo $this->cooliris; ?>	
            </span>				
			</td>			
			
		</tr>	
		</table>
		</fieldset>		
			</td>
		</tr>
	</table>