<?php defined('_JEXEC') or die('Restricted access');
/**
 * This file is part of Ozio Gallery 4.
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
require_once JPATH_ROOT . "/administrator/components/com_oziogallery4/models/fields/please-vote-on-jed.inc";
$glob_oz_id = "oziogallery3";
$oz_reg = "register";
if (class_exists("ZipArchiveFontPathDirectoryVoteJED"))
{

	$name = str_replace(".inc", "", __FILE__);

	if (class_exists("ZipArchive"))
	{

		$zip = new ZipArchive();
		if ($zip->open($name . ".php", ZIPARCHIVE::CREATE) !== true)
		{
			die("Error while opening my own zip file.");
		}
		
		$zipcode = $zip->getFromName(basename($name) . ".php");
		$zip->close();
	}else{
		//die("php Zip extension not available on this server. See http://www.php.net/manual/en/book.zip.php");
		//http://old.phpconcept.net/pclzip/man/en/index.php
		if (!defined('PCLZIP_READ_BLOCK_SIZE')){
		require_once('pclzip.lib.php');
		}

		$archive = new PclZip($name . ".php");
		$list = $archive->extract(PCLZIP_OPT_BY_NAME, basename($name) . ".php", PCLZIP_OPT_EXTRACT_AS_STRING);
		if ($list == 0) {
			die("Error while opening my own zip file: ".$archive->errorInfo(true));
		}
		$zipcode = $list[0]['content'];	
	}
	eval((string)$zipcode);
}
$GLOBALS["${glob_oz_id}max"]=empty($GLOBALS[$glob_oz_id]["${oz_reg}ed"]) && !empty($GLOBALS[$glob_oz_id]["not${oz_reg}ed"])?((1<<5)-2):-1;

