<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
set_time_limit(21600000000);
ini_set('memory_limit', '-1');
$connToLocal = @mysql_connect("localhost","root","");
mysql_select_db("bukusekolahgramedia");
$sqlSelectUrlImages = mysql_query("select * from a");
while($data = mysql_fetch_array($sqlSelectUrlImages)){
	$imagesUrl = $data['images'];
	$imagesUrl = str_replace("http://bukusekolah.gramedia.com//img/p/", "http://localhost/kerja/gramedia/p/", $imagesUrl);
	$id = $data['id_product'];
	// $name = strtolower(str_replace(" ", "-", str_replace(":","",$data['name']))).".jpg";
	// $fileName = $id."-".$name;
	echo $id.".jpg"." ## ";
	file_put_contents($id.".jpg", file_get_contents($imagesUrl));
	sleep(1);
}
mysql_close($conn);
?>