<?php
/**
* This file is part of Ozio Gallery 2.
*
* Ozio Gallery 2 is free software: you can redistribute it and/or modify
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
