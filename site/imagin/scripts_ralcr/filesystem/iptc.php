<?php

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