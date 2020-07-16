<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<form method="post" enctype="multipart/form-data" action="<?php echo base_url('backoffice/product/uploadFiles'); ?>">
	pilih file excel : <input type="file" name="mikon_file">
	<br>
    Pilih folder gambar : <input type="file" name="files[]" id="files" multiple="" directory="" webkitdirectory="" mozdirectory="">
    <br>
    <input class="button" type="submit" value="Upload" />
</form>
</body>
</html>