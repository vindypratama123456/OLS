<?php
array_multisort(array_map('filemdate', ($files = glob("*.jpg*"))), SORT_DESC, $files);
foreach($files as $namafile){
	$id_product = str_replace(".jpg", "", $namafile);
	echo "update product set images = 1 where id_product = '$id_product';<br />";
}