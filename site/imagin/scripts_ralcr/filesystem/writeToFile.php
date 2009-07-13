<?php
// foreach($_POST as $key=>$value) {
// 	if (!get_magic_quotes_gpc()) {
// 		$temp = stripslashes($value);
// 		$_POST[$key] = $temp;
// 	}
// }

$file = "../../".$_POST["path"];

$fh = fopen ($file, 'w') or die("error::Can't open file for writing");
echo fwrite ($fh, stripslashes($_POST["raw_data"]));

fclose($fh);