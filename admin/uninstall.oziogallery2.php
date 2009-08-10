<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
/*
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
*/
function com_uninstall(){
  
	$db=JFactory::getDBO();
    unlink(JPATH_ROOT . DS . 'plugins' . DS . 'content' . DS . 'ozio.php');
    unlink(JPATH_ROOT . DS . 'plugins' . DS . 'content' . DS . 'ozio.xml');
    $db->setQuery("DELETE FROM #__plugins WHERE name='Content - OzioGallery2'");
    $db->query();
	

?>  
<div class="header">The component Ozio Gallery and Plugin Content - OzioGallery2 has been uninstalled successfully.</div>
<?php
}
?>
