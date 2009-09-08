<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.filesystem.folder' );

function deleteDir($dir){
    if (substr($dir, strlen($dir)-1, 1)!= '/')
        $dir .= '/';
    if ($handle = opendir($dir))
    {
        while ($obj = readdir($handle))
        {
            if ($obj!= '.' && $obj!= '..')
            {
                if (is_dir($dir.$obj))
                {
                    if (!deleteDir($dir.$obj))
                        return false;
                }
                elseif (is_file($dir.$obj))
                {
                    if (!unlink($dir.$obj))
                        return false;
                }
            }
        }
        closedir($handle);
        if (!@rmdir($dir))
            return false;
        return true;
    }
    return false;
}

function com_install() 

{
	$folder[0][0]	=	'images' . DS . 'oziogallery2' . DS ;
	$folder[0][1]	= 	JPATH_ROOT . DS .  $folder[0][0];
	$folder[0][0]	=	'images' . DS . 'oziodownload' . DS ;
	$folder[0][2]	= 	JPATH_ROOT . DS .  $folder[0][0];
	$folder[0][0]	=	'modules' . DS . 'mod_ozio2' . DS ;
	$folder[0][3]	= 	JPATH_ROOT . DS .  $folder[0][0];
	$folder[0][0]	=	'modules' . DS . 'mod_ozio2' . DS . 'tmpl' . DS ;
	$folder[0][4]	= 	JPATH_ROOT . DS .  $folder[0][0];		
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
		
		if (!JFolder::exists( $value[3]))
		{
			if (JFolder::create( $value[3], 0755 ) && JFolder::create( $value[4], 0755 ))
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

	jimport('joomla.filesystem.file');
	jimport('joomla.filesystem.folder');

	if(!JFile::copy(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_oziogallery2'.DS.'plugins'.DS.'ozio.xm', JPATH_SITE.DS.'plugins'.DS.'content'.DS.'ozio.xml')){
		echo JText::_('<b>Failed</b> to copy plugin xml file<br />');
	}

	if(!JFile::copy(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_oziogallery2'.DS.'plugins'.DS.'ozio.php', JPATH_SITE.DS.'plugins'.DS.'content'.DS.'ozio.php')){
		echo JText::_('<b>Failed</b> to copy plugin php file<br />');
	}	

	$plugin = JTable::getInstance( 'plugin' );
	$plugin->name = 'Content - OzioGallery2';
	$plugin->element = 'ozio';
	$plugin->folder = 'content';
	$plugin->ordering = 1;
	$plugin->published = 1;
	
	if (!$plugin->store()) {
		echo JText::_('Plugin install failed:') .$plugin->getError().'<br />';
	}	
	
    deleteDir(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_oziogallery2'.DS.'plugins');	

	if(!JFile::copy(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_oziogallery2'.DS.'modules'.DS.'mod_ozio2.xm', JPATH_SITE.DS.'modules'.DS.'mod_ozio2'.DS.'mod_ozio2.xml')){
		echo JText::_('<b>Failed</b> to copy modules xml file<br />');
	}

	if(!JFile::copy(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_oziogallery2'.DS.'modules'.DS.'mod_ozio2.php', JPATH_SITE.DS.'modules'.DS.'mod_ozio2'.DS.'mod_ozio2.php')){
		echo JText::_('<b>Failed</b> to copy modules php file<br />');
	}

	if(!JFile::copy(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_oziogallery2'.DS.'modules'.DS.'helper.php', JPATH_SITE.DS.'modules'.DS.'mod_ozio2'.DS.'helper.php')){
		echo JText::_('<b>Failed</b> to copy helper php file<br />');
	}	

	if(!JFile::copy(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_oziogallery2'.DS.'modules'.DS.'index.html', JPATH_SITE.DS.'modules'.DS.'mod_ozio2'.DS.'index.html')){
		echo JText::_('<b>Failed</b> to copy index.html file<br />');
	}

	if(!JFile::copy(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_oziogallery2'.DS.'modules'.DS.'index.html', JPATH_SITE.DS.'modules'.DS.'mod_ozio2'.DS.'index.html')){
		echo JText::_('<b>Failed</b> to copy index.html file<br />');
	}	
	
	if(!JFile::copy(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_oziogallery2'.DS.'modules'.DS.'tmpl'.DS.'default.php', JPATH_SITE.DS.'modules'.DS.'mod_ozio2'.DS.'tmpl'.DS.'default.php')){
		echo JText::_('<b>Failed</b> to copy default.php file<br />');
	}		
	
	if(!JFile::copy(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_oziogallery2'.DS.'modules'.DS.'tmpl'.DS.'index.html', JPATH_SITE.DS.'modules'.DS.'mod_ozio2'.DS.'tmpl'.DS.'index.html')){
		echo JText::_('<b>Failed</b> to copy index.html file<br />');
	}		
	
	
    deleteDir(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_oziogallery2'.DS.'modules');	
}

{
?>
<div class="header">Congratulations! Joomla Ozio Gallery Component has been installed successfully</div>
<?php
}
?>
