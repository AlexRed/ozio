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

		function resize ($path, $new_path, $w, $h, $kind) 
		{
			$src_img = imagecreatefromjpeg($path);
			// $system = explode(".", $original_name);
			// if (preg_match("/jpg|jpeg|JPG/", $system[1]) ) $src_img=imagecreatefromjpeg($path.$original_name);
			// if (preg_match("/png|PNG/", $system[1]) ) $src_img=imagecreatefrompng($path.$original_name);
			
			$src_w = imageSX($src_img);
			$src_h = imageSY($src_img);
			$src_x = 0; // source x and y (fit: 0,0), (fill: recalculate to center)
			$src_y = 0;
			
			$dst_w = (int)$w; // destination width and height
			$dst_h = (int)$h;
			$dst_x = 0; // destination x and y: 0,0 all the time
			$dst_y = 0;
			
			
			// Resize only if original is bigger than the new desired size
			if ($src_w>(int)$w || $src_h>(int)$h) {
				
				if ($src_w/(int)$w > $src_h/(int)$h) {
					// larger on width axis
					if ($kind=="fill") {
						// resize then crop in center
						$org_w = $src_w;//original w,h use when calculating the new x,y with the new w,h
						$org_h = $src_h;
						//
						$src_h = $org_h;
						$src_w = $org_h * $dst_w/$dst_h;
					}
					else {
						// resize the image to fit the smaller border
						$dst_w = (int)$w;
						$dst_h = $src_h * (int)$w/$src_w;
					}
				}
				else {
					// larger on height axis
					if ($kind=="fill") {
						// resize then crop in center
						$org_w = $src_w;//original w,h use when calculating the new x,y with the new w,h
						$org_h = $src_h;
						//
						$src_w = $org_w;
						$src_h = $org_w * $dst_h/$dst_w;
					}
					else {
						$dst_h = (int)$h;
						$dst_w = $src_w * (int)$h/$src_h;
					}
				}
				
				// copy pixels from center
				if ($kind=="fill") {
					$src_x = ($org_w - $src_w)/2;
					$src_y = ($org_h - $src_h)/2;
				}
				
				
				$dst_img = ImageCreateTrueColor($dst_w, $dst_h);
				// In other words, imagecopyresampled() will take an rectangular area from src_image of width src_w and height src_h at position (src_x ,src_y ) and place it in a rectangular area of dst_image of width dst_w and height dst_h at position (dst_x ,dst_y ).
				imagecopyresampled($dst_img, $src_img, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h); 
				imagejpeg($dst_img, $new_path, 90);
				
				imagedestroy($dst_img);
				imagedestroy($src_img);
			}
		}


		function LastModifiedAscending($a, $b) 
		{
			return ($a[1] < $b[1]) ? -1:1;
		}
		function LastModifiedDescending($a, $b) 
		{
			return ($a[1] > $b[1]) ? -1:1;
		}
		function Ascending($a, $b) 
		{
			return ($a[0] < $b[0]) ? -1:1;
		}
		function Descending($a, $b) 
		{
			return ($a[0] > $b[0]) ? -1:1;
		}


		$path = "../../".$_POST["path"];
		$sort = isset($_POST["sort"]) ? $_POST["sort"] : "LastModifiedDescending";
		(int)$w = $_POST['w'];
		(int)$h = $_POST['h'];
		$resize = $_POST['resize'];// fill, fit


		$allowed_to_resize = array('jpg', 'jpeg', 'png');
		$not_allowed_to_read = array('.', '..', '.DS_Store', '_vti_cnf', 'Thumbs.db', '_thumb.jpg', 'index.html',);
		$thumb_sufix = ".th.";

		// Read Directory
		$dir = opendir($path) or die("error::Unable to open $path");

			while ($filename = readdir($dir)) 
			{
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
		usort($files_arr, $sort);

		// Create a new array with filenames only.
		// Originaly they are stored into a multidimensional array with [filename, last modified date]
		foreach ($files_arr as $filename)
			$filenames_arr[] = $filename[0];

		// Make Thumbs
		if (function_exists("imageCreateFromJpeg") &&
			function_exists("imageCreateTrueColor") &&
			($resize=="fit" || $resize=="fill") && isset($w) && isset($h)) 
			{
				foreach ($filenames_arr as $filename) 
				{
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