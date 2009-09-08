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

    unlink(JPATH_ROOT . DS . 'modules' . DS . 'mod_ozio2' . DS . 'tmpl' . DS . 'default.php');
    unlink(JPATH_ROOT . DS . 'modules' . DS . 'mod_ozio2' . DS . 'tmpl' . DS . 'index.html');
    rmdir(JPATH_ROOT . DS . 'modules' . DS . 'mod_ozio2' . DS . 'tmpl');
    unlink(JPATH_ROOT . DS . 'modules' . DS . 'mod_ozio2' . DS . 'index.html');	
    unlink(JPATH_ROOT . DS . 'modules' . DS . 'mod_ozio2' . DS . 'helper.php');
    unlink(JPATH_ROOT . DS . 'modules' . DS . 'mod_ozio2' . DS . 'mod_ozio2.php');
    unlink(JPATH_ROOT . DS . 'modules' . DS . 'mod_ozio2' . DS . 'mod_ozio2.xml');	
    rmdir(JPATH_ROOT . DS . 'modules' . DS . 'mod_ozio2');
	

?>  
<div class="header">The component Ozio Gallery and Plugin Content - OzioGallery2 has been uninstalled successfully.</div>
<?php
}
?>
