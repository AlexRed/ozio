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


function iptc ($image_path) {
	
	$size = getimagesize ($image_path, $info);

	if (is_array($info)) {
		$iptc = iptcparse($info["APP13"]);
		
		$caption = $iptc["2#120"][0];// 2000 chars
		$city = $iptc["2#090"][0];// 32 chars
		$location = $iptc["2#092"][0];// 
		
		return "[IPTC::".$caption."::".$city."::".$location."::IPTC]";
		
		//foreach (array_keys($iptc) as $s) {
		//	$c = count ($iptc[$s]);
		//	$files_arr[] = $s;
			
			//for ($i=0; $i <$c; $i++) {
				//echo $s.' = '.$iptc[$s][$i].'<br>';
				//.'='.$iptc[$s][$i];
			//}
		//}
		
		//return implode(":::", $files_arr);
	}
}