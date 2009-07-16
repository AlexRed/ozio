<?php
/*
+-------------------------------------------------+
| Joomla Ozio Gallery Component for Joomla 1.5
| oziogallery.php
+-------------------------------------------------+
| Author:                    
|	AlexRed
|	http://www.joomla.it
|
| Based on:
|    Joomla WebcamXP Component for Joomla 1.5 by Jooglar (jwebcamxp@jooglar.com) http://jooglar.com
|    &
|    WebcamXP Component for Joomla 1.0.x & Mambo 4.5.x by Andy Stewart (andy@troozers.com) http://www.troozers.com
|
| License:
|     Released under GNU/GPL License
|     http://www.gnu.org/copyleft/gpl.html
|
+-------------------------------------------------+
*/
defined('_JEXEC') or die( 'Direct Access to this location is not allowed.' );

jimport( 'joomla.application.component.view');
jimport( 'joomla.html.pane' );

class HTML_oziogallery2
{
	function showIntro ( $option )
	{
	
	?>
	<form action="index.php" method="post" name="adminForm" id="adminForm">
		<h1><?php echo JText::_('TITLE');	?></h1><br />
		<table cellspacing="0" cellpadding="0" bgcolor="#ffffcc"><tbody><tr><td>
</td></tr></tbody></table>
<?php
        $pane = & JPane::getInstance('tabs');
	    echo $pane->startPane( 'ozio-pane' );
		echo $pane->startPanel( JText::_('Descrizione'), 'Nuovo-tab1' );
?>
<table class="admintable">
	<tr>
		<td align="left"><?php echo JText::_('COMPONENT DESCRIPTION');?></td>
	</tr>
</table>
<?php
	echo $pane->endPanel();
	echo $pane->startPanel( 'Info di sistema', 'Nuovo-tab2' );
?>	
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
<?php
	echo $pane->endPanel();
	echo $pane->startPanel( 'Istruzioni', 'Nuovo-tab3' );
?>	
<table class="admintable">
	<tr>
		<td align="left"><?php echo JText::_('COMPONENT INSTRUCTIONS');	?></td>
	</tr>
</table>
<?php
	echo $pane->endPanel();
	echo $pane->startPanel( 'Modulo', 'Nuovo-tab4' );
?>	
<table class="admintable">
	<tr>
		<td align="left"><?php echo JText::_('COMPONENT CATEGORIES');	?></td>
	</tr>
</table>
<?php
	echo $pane->endPanel();
	echo $pane->startPanel( 'Lingua', 'Nuovo-tab5' );
?>	
<table class="admintable">
	<tr>
		<td align="left"><?php echo JText::_('COMPONENT LANGUAGE');	?></td>
	</tr>
</table>
<?php
	echo $pane->endPanel();
	echo $pane->startPanel( 'Voto', 'Nuovo-tab6' );
?>	
<table class="admintable">
	<tr>
		<td align="left"><?php echo JText::_('COMPONENT VOTE');	?></td>
	</tr>
</table>
<?php
	echo $pane->endPanel();
?>
<?php
	echo $pane->endPanel();
	echo $pane->startPanel( 'Crediti', 'Nuovo-tab67' );
?>	
<table class="admintable">
	<tr>
		<td align="left">
<b><a href="http://forum.joomla.it/index.php/board,73.0.html" target="blank">Ozio Gallery FORUM</a></b><br /><br />
Credits:
<br />
This component is based on:<br />
Joomla WebcamXP Component for Joomla 1.5 by Jooglar <a href="http://jooglar.com" target='blank'> http://jooglar.com</a><br />
WebcamXP Component for Joomla 1.0.x & Mambo 4.5.x by Andy Stewart (andy@troozers.com) <a href="http://www.troozers.com" target='blank'>http://www.troozers.com</a><br />
<br />Flash is based on:
<br />- <a href="http://www.airtightinteractive.com/projects/tiltviewer/" target='blank'>TiltViewer</a> Design/Development by <a href="http://www.airtightinteractive.com" target='blank'>Airtight</a>, Sound Design by <a href="http://www.earganic.com/" target='blank'>Earganic Studios</a>, <a href="http://blog.deconcept.com/flashobject/" target='blank'>FlashObject</a> Javascript embed code by Geoff Stearns.
<br />TiltViewer-Pro is also available for purchase with increased customization options and the TiltViewer logo not included. To purchase it <a href="http://www.airtightinteractive.com/projects/tiltviewer/pro/" target='blank'>click here</a>. To upgrade existing TiltViewer galleries to TiltViewer-Pro, replace the TiltViewer.swf file in your existing folder (at components/com_oziogallery2/TiltViewer.swf) with the one that comes in the Pro download.
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
<h3 align="right">July 04th, 2009. Component developed by AlexRed & Ste - <a href="http://oziogallery.joomla.it">http://oziogallery.joomla.it</a></h3><br />		
		</td>
	</tr>
</table>
<?php
	echo $pane->endPanel();
?>	
		<input type="hidden" name="option"value="<?php echo $option;?>" />
		<input type="hidden" name="task"value="" />
		</form>
	<?php
	}
	
	function showFAQ( $option , &$lists )
	{
	
		?>
		<form action="index.php" method="post" name="adminForm">
		<?php
		foreach($lists["faq"] as $faq)
		{
			echo '<h2>'.$faq["question"].'</h2>';
			echo '<p>'.$faq["answer"].'</p>';
		}
		?>
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="" />
		</form>
		<?php
	}
	
	
	function showReset( $option )
	{
	    JHTML::_('behavior.tooltip');
		$link = 'index.php?option=com_oziogallery2&task=imagerotator';
		$link2 = 'index.php?option=com_oziogallery2&task=tilt';
		$link3 = 'index.php?option=com_oziogallery2&task=accordion';
		$link4 = 'index.php?option=com_oziogallery2&task=carousel';		
		$link5 = 'index.php?option=com_oziogallery2&task=flashgallery';
        $img = JURI::root().'administrator/images/delete_f2.png';
		$VAMBpathAssoluto = JPATH_SITE;
		$VAMBpathAssoluto = str_replace("\\", "/" , $VAMBpathAssoluto);	
		$Directory = $VAMBpathAssoluto.'/components/com_oziogallery2/skin/imagerotator/xml/';
		$Directory1 = $VAMBpathAssoluto.'/components/com_oziogallery2/skin/carousel/xml/';		
		$Directory2 = $VAMBpathAssoluto.'/components/com_oziogallery2/skin/accordion/xml/';
		$Directory3 = $VAMBpathAssoluto.'/components/com_oziogallery2/skin/flashgallery/xml/';
		$Directory4 = $VAMBpathAssoluto.'/components/com_oziogallery2/skin/tiltviewer/xml/';		
		?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
		<fieldset class="adminform">
		<legend><?php echo JText::_( 'Reset' ); ?></legend>
		<table  class="admintable">
		<th align="center"><?php echo JText::_( 'Galleria' ); ?></th>
		<th></th>
		<th></th>
		<th><?php echo JText::_( 'File' ); ?></th>	
		<th align="right"><?php echo JText::_( 'Comando' ); ?></th>
		<th></th>			
		<tr>
			<td class="key">
				<label for="title">
					<span class="editlinktip hasTip" title="<?php echo JText::_ ( 'NOTES' ); ?>::<?php echo JText::_ ( 'Accordion Reset' );?>">						
							<?php echo JText::_( 'Accordion' ).':'; ?>
					</span>				
				</label>
			</td>
			<td>
			</td>
			<td class="key">
				<label for="title">
							<?php echo JText::_( 'Actual Files' ).':'; ?>
				</label>
			</td>
			<td valign="top" width="50%">
<?php
      if(is_dir($Directory2))
      {
          $dir = opendir($Directory2);
          echo "<pre>";
          while(false !== ($file = readdir($dir)))
          {
		  if($file != '.' && $file != '..' && $file != 'index.html') 
		  {
              $size = filesize($Directory2 ."/". $file);
              echo "$file | $size kb \n";			  
          }			  
          }
          closedir($dir);
          echo "</pre>";
      }
      else
      {
          echo $Directory2 .  JText::_ ( 'La directory non esiste' );
      }	

?>
			</td>
			<td class="key">
				<label for="title">
							<?php echo JText::_( 'Click for Reset' ).':'; ?>
				</label>
			</td>
			<td>
				<a href="<?php echo $link3 ?>"> <img src="<?php echo $img?>" alt="<?php echo JText::_( 'Svuota' ); ?>" title="<?php echo JText::_( 'Svuota' ); ?>"> </a>
			</td>			
		</tr>
		<tr>
			<td class="key">
				<label for="title">
					<span class="editlinktip hasTip" title="<?php echo JText::_ ( 'NOTES' ); ?>::<?php echo JText::_ ( 'Carousel Reset' );?>">						
							<?php echo JText::_( 'Carousel' ).':'; ?>
					</span>				
				</label>
			</td>
			<td>
			</td>
			<td class="key">
				<label for="title">
							<?php echo JText::_( 'Actual Files' ).':'; ?>
				</label>
			</td>
			<td valign="top">
<?php
      if(is_dir($Directory1))
      {
          $dir = opendir($Directory1);
          echo "<pre>";
          while(false !== ($file = readdir($dir)))
          {
		  if($file != '.' && $file != '..' && $file != 'index.html') 
		  {
              $size = filesize($Directory1 ."/". $file);
              echo "$file | $size kb \n";			  
          }			  
          }
          closedir($dir);
          echo "</pre>";
      }
      else
      {
          echo $Directory1 . JText::_ ( 'La directory non esiste' );
      }	

?>
			</td>
			<td class="key" >
				<label for="title">
							<?php echo JText::_( 'Click for Reset' ).':'; ?>
				</label>
			</td>
			<td>
				<a href="<?php echo $link4 ?>"> <img src="<?php echo $img?>" alt="<?php echo JText::_( 'Svuota' ); ?>" title="<?php echo JText::_( 'Svuota' ); ?>"> </a>
			</td>			
		</tr>		
		<tr>
			<td class="key">
				<label for="title">
					<span class="editlinktip hasTip" title="<?php echo JText::_ ( 'NOTES' ); ?>::<?php echo JText::_ ( 'FlashGallery Reset' );?>">						
							<?php echo JText::_( 'FlashGallery' ).':'; ?>
					</span>				
				</label>
			</td>
			<td>
			</td>
			<td class="key">
				<label for="title">
							<?php echo JText::_( 'Actual Files' ).':'; ?>
				</label>
			</td>
			<td valign="top">
<?php
      if(is_dir($Directory3))
      {
          $dir = opendir($Directory3);
          echo "<pre>";
          while(false !== ($file = readdir($dir)))
          {
		  if($file != '.' && $file != '..' && $file != 'index.html') 
		  {
              $size = filesize($Directory3 ."/". $file);
              echo "$file | $size kb \n";			  
          }			  
          }
          closedir($dir);
          echo "</pre>";
      }
      else
      {
          echo $Directory3 . JText::_ ( 'La directory non esiste' );
      }	

?>
			</td>
			<td class="key">
				<label for="title">
							<?php echo JText::_( 'Click for Reset' ).':'; ?>
				</label>
			</td>
			<td>
				<a href="<?php echo $link5 ?>"> <img src="<?php echo $img?>" alt="<?php echo JText::_( 'Svuota' ); ?>" title="<?php echo JText::_( 'Svuota' ); ?>"> </a>
			</td>			
		</tr>

		<tr>
			<td class="key">
				<label for="title">
					<span class="editlinktip hasTip" title="<?php echo JText::_ ( 'NOTES' ); ?>::<?php echo JText::_ ( 'Imagerotator Reset' );?>">						
							<?php echo JText::_( 'Imagerotator' ).':'; ?>
					</span>				
				</label>
			</td>
			<td>
			</td>
			<td class="key">
				<label for="title">
							<?php echo JText::_( 'Actual Files' ).':'; ?>
				</label>
			</td>
			<td valign="top">
<?php
      if(is_dir($Directory))
      {
          $dir = opendir($Directory);
          echo "<pre>";
          while(false !== ($file = readdir($dir)))
          {
		  if($file != '.' && $file != '..' && $file != 'index.html') 
		  {
              $size = filesize($Directory ."/". $file);
              echo "$file | $size kb \n";			  
          }			  
          }
          closedir($dir);
          echo "</pre>";
      }
      else
      {
          echo $Directory . JText::_ ( 'La directory non esiste' );
      }	

?>
			</td>
			<td class="key">
				<label for="title">
							<?php echo JText::_( 'Click for Reset' ).':'; ?>
				</label>
			</td>
			<td>
				<a href="<?php echo $link ?>"> <img src="<?php echo $img?>" alt="<?php echo JText::_( 'Svuota' ); ?>" title="<?php echo JText::_( 'Svuota' ); ?>"> </a>
			</td>			
		</tr>
		<tr>
			<td class="key">
				<label for="title">
					<span class="editlinktip hasTip" title="<?php echo JText::_ ( 'NOTES' ); ?>::<?php echo JText::_ ( 'Tild 3D Gallery Reset' );?>">						
							<?php echo JText::_( 'Tild 3D Gallery' ).':'; ?>
					</span>				
				</label>
			</td>
			<td>
			</td>
			<td class="key">
				<label for="title">
							<?php echo JText::_( 'Actual Files' ).':'; ?>
				</label>
			</td>
			<td valign="top">
<?php
      if(is_dir($Directory4))
      {
          $dir = opendir($Directory4);
          echo "<pre>";
          while(false !== ($file = readdir($dir)))
          {
		  if($file != '.' && $file != '..' && $file != 'index.html') 
		  {
              $size = filesize($Directory4 ."/". $file);
              echo "$file | $size kb \n";			  
          }			  
          }
          closedir($dir);
          echo "</pre>";
      }
      else
      {
          echo $Directory4 . JText::_ ( 'La directory non esiste' );
      }	

?>
			</td>
			<td class="key">
				<label for="title">
							<?php echo JText::_( 'Click for Reset' ).':'; ?>
				</label>
			</td>
			<td>
				<a href="<?php echo $link2 ?>"> <img src="<?php echo $img?>" alt="<?php echo JText::_( 'Svuota' ); ?>" title="<?php echo JText::_( 'Svuota' ); ?>"> </a>
			</td>			
		</tr>		
	
		</table>
		</fieldset>		
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="" />
		</form>
		<?php	

	
	}	
	
}
?>