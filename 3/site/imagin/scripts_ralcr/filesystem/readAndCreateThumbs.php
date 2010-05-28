<?php
/**
* This file is part of Ozio Gallery 3
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

$path = "../../".$_POST["path"];
$sort = isset($_POST["sort"]) ? $_POST["sort"] : "LastModifiedDescending";
$w = $_POST['w'];
$h = $_POST['h'];
$resize = $_POST['resize'];// fill, fit

$allowed_to_resize = array('jpg', 'jpeg', 'png');
$not_allowed_to_read = array('.', '..', '.DS_Store', '_vti_cnf', 'Thumbs.db', '_thumb.jpg', 'index.html',);
$thumb_sufix = ".th.";

// Read Directory
$dir = opendir($path) or die("error::Unable to open $path");

while ($filename = readdir($dir)) {
	if (in_array($filename, $not_allowed_to_read)) continue;
	
	if (strpos($filename, $thumb_sufix) === false) {
		// Any file wich not a thumb
		$LastModified = filemtime($path.$filename);
		$files_arr[] = array($filename, $LastModified);
	}
	else {
		// Create an array with existing thumbs so we do not resize them again
		$thumbs_arr[] = $filename;
	}
}
closedir($dir);

// Sort Files
include "sort.php";
usort($files_arr, $sort);

// Create a new array with filenames only.
// Originaly they are stored into a multidimensional array with [filename, last modified date]
foreach ($files_arr as $filename)
	$filenames_arr[] = $filename[0];



// Make Thumbs
if (function_exists("imageCreateFromJpeg") &&
	function_exists("imageCreateTrueColor") &&
	($resize=="fit" || $resize=="fill") && isset($w) && isset($h)) {
	
	include "resize.php";
	
	foreach ($filenames_arr as $filename) {
		$f = explode(".", $filename);
		$extension = (sizeof($f) > 1) ? array_pop($f) : "";
		
		if (in_array(strtolower($extension), $allowed_to_resize)) {
			// if the extension is allowed to resize, continue
			$thumb_name = implode(".", $f).".th.".$extension;
			
			if (!in_array($thumb_name, $thumbs_arr)) {
				// original name, new name, width, height, kind of resize: "fit", "fill"
				resize ($path.$filename, $path.$thumb_name, $w, $h, $resize);
			}
		}
	}
	
}

echo "[FILES::".implode("*", $filenames_arr)."::FILES]";