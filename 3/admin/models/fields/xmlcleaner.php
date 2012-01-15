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

defined('JPATH_BASE') or die;

// 13/01/2011 DP Written a class for automatic xml reset

abstract class OSSXMLCleaner extends JFormField
{
	public function __construct($form = null)
	{
		parent::__construct($form);
		$this->clean();
	}

	protected function clean()
	{
		$com_name = basename(realpath(dirname(__FILE__) . DS . '..' . DS . '..')); // component name
		$xml_path = JPATH_ROOT . DS . "components" . DS . $com_name . DS . "skin" . DS . $this->dir_name . DS . "xml" . DS;
		jimport('joomla.filesystem.folder');
		// Locate xml files
		$files = JFolder::files($xml_path, ".ozio");
		// and remove each one
		foreach ($files as $file) JFile::delete($xml_path . $file);
	}

}

?>
