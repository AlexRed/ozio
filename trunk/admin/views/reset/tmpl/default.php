<?php
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
		</table>
		</fieldset>		
			</td>
		</tr>
	</table>