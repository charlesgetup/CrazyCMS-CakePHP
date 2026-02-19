<?php

session_start();
if($_SESSION["verify"] != "FileManager4TinyMCE") die('forbiden');
include 'config.php';

$path=$_POST['path'];
$name=$_POST['name'];

if(strpos($path,$upload_dir)===FALSE) die('wrong path');

if(strpos($path,$base_url)===FALSE){
	$path = $base_url .$path;
}

$head = array_change_key_case(get_headers($path, TRUE));
$filesize = $head['content-length'];

header('Pragma: private');
header('Cache-control: private, must-revalidate');
header("Content-Type: application/octet-stream");
header("Content-Length: " .$filesize );
header('Content-Disposition: attachment; filename="'.($name).'"');
readfile($path);
exit;
?>