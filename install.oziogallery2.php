<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.filesystem.folder' );
?>
<?php
function com_install() 

{
	$folder[0][0]	=	'images' . DS . 'oziogallery2' . DS ;
	$folder[0][1]	= 	JPATH_ROOT . DS .  $folder[0][0];
	$folder[0][0]	=	'images' . DS . 'oziodownload' . DS ;
	$folder[0][2]	= 	JPATH_ROOT . DS .  $folder[0][0];
	$file 		= "index.html";
	$file2 		= "_preferences.xml";
	$file3 		= "info.png";	
	$source 	= 	JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery2';
	$source2 	= 	JPATH_ROOT . DS . 'components' . DS . 'com_oziogallery2' . DS . 'imagin' . DS . 'imagin';	
	$dest 		=   JPATH_ROOT . DS . 'images' . DS . 'oziogallery2';	
	$dest2 		=   JPATH_ROOT . DS . 'images' . DS . 'oziodownload';
	
	$message = '';
	$error	 = array();
	foreach ($folder as $key => $value)
	{
		if (!JFolder::exists( $value[1]))
		{
			if (JFolder::create( $value[1], 0755 ) && JFolder::create( $value[2], 0755 ) && @copy($source. DS .$file,$dest. DS .$file) && @copy($source. DS .$file,$dest2. DS .$file) && @copy($source2. DS .$file2,$dest. DS .$file2) && @copy($source2. DS .$file3,$dest. DS .$file3))
			{

				$message .= '<p><b><span style="color:#009933">Folder</span> ' . $value[0] 
						   .' <span style="color:#009933">created!</span></b></p>';
				$error[] = 0;
			}	 
			else
			{
				$message .= '<p><b><span style="color:#CC0033">Folder</span> ' . $value[0]
						   .' <span style="color:#CC0033">creation failed!</span></b> Please create it manually.</p>';
				$error[] = 1;
			}
		}
		else//Folder exist
		{
			$message .= '<p><b><span style="color:#009933">Folder</span> ' . $value[0] 
						   .' <span style="color:#009933">exists!</span></b></p>';
			$error[] = 0;
		}
	}
}
{
?>
<div class="header">Congratulations! Joomla Ozio Gallery Component has been installed successfully</div>
<?php
}
?>
