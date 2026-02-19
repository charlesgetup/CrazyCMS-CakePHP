<?php
if($_SESSION["verify"] != "FileManager4TinyMCE") die('forbidden');

/* Load CakePHP env starts */

if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}
if (!defined('ROOT')) {
	define('ROOT', dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))));
}
if (!defined('APP_DIR')) {
	define('APP_DIR', basename(dirname(dirname(dirname(dirname(dirname(__FILE__)))))));
}
if (!defined('WEBROOT_DIR')) {
	define('WEBROOT_DIR', basename(dirname(dirname(dirname(dirname(__FILE__))))));
}
if (!defined('WWW_ROOT')) {
	define('WWW_ROOT', dirname(dirname(dirname(dirname(__FILE__)))) . DS);
}

// for built-in server
if (php_sapi_name() === 'cli-server') {
	if ($_SERVER['REQUEST_URI'] !== '/' && file_exists(WWW_ROOT . $_SERVER['PHP_SELF'])) {
		return false;
	}
	$_SERVER['PHP_SELF'] = '/' . basename(__FILE__);
}

if (!defined('CAKE_CORE_INCLUDE_PATH')) {
	if (function_exists('ini_set')) {
		ini_set('include_path', ROOT . DS . 'lib' . PATH_SEPARATOR . ini_get('include_path'));
	}
	if (!include ROOT . DS . 'lib' . DS . 'Cake' . DS . 'bootstrap.php') {
		$failed = true;
	}
} else {
	if (!include CAKE_CORE_INCLUDE_PATH . DS . 'Cake' . DS . 'bootstrap.php') {
		$failed = true;
	}
}
if (!empty($failed)) {
	trigger_error("CakePHP core could not be found. Check the value of CAKE_CORE_INCLUDE_PATH in APP/webroot/index.php. It should point to the directory containing your " . DS . "cake core directory and your " . DS . "vendors root directory.", E_USER_ERROR);
}

App::uses('Dispatcher', 'Routing');

/* Load CakePHP env ends */

//**********************
//Path configuration
//**********************
// In this configuration the folder tree is
// root
//   |- tinymce
//   |    |- source <- upload folder
//   |    |- js
//   |    |   |- tinymce
//   |    |   |    |- plugins
//   |    |   |    |-   |- filemanager
//   |    |   |    |-   |-      |- thumbs <- folder of thumbs [must have the write permission]

$base_url = Configure::read('System.aws.s3.bucket.link.prefix');
$root = '';

if(isset($_SESSION["Auth"]["User"]["id"]) && !empty($_SESSION["Auth"]["User"]["id"])){

	$upload_root_dir 	= 'email-marketing/' .$_SESSION["Auth"]["User"]["id"]; //TODO hardcode "email-marketing" for now, should provide a way to set different type, like "tasks"
	$upload_dir 		= $upload_root_dir .'/media'; // path from base_url to upload base dir
	$current_path_root 	= $upload_root_dir;
	$current_path 		= $current_path_root .'/media'; // relative path from filemanager folder to upload files folder
}

$MaxSizeUpload=2; //Mb

//**********************
//Image config
//**********************
//set max width pixel or the max height pixel for all images
//If you set dimension limit, automatically the images that exceed this limit are convert to limit, instead
//if the images are lower the dimension is maintained
//if you don't have limit set both to 0
$image_max_width=0;
$image_max_height=0;

//Automatic resizing //
//If you set true $image_resizing the script convert all images uploaded in image_width x image_height resolution
//If you set width or height to 0 the script calcolate automatically the other size
$image_resizing=false;
$image_width=600;
$image_height=0;

//******************
//Permits config
//******************
$delete_file=true;
$create_folder=true;
$delete_folder=true;
$upload_files=true;


//**********************
//Allowed extensions
//**********************
$ext_img = array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff'); //Images
$ext_file = array(/*'doc', 'docx', 'pdf', 'xls', 'xlsx', 'txt', 'csv','html','psd','sql','log','fla','xml','ade','adp','ppt','pptx'*/); //Files
$ext_video = array(/*'mov', 'mpeg', 'mp4', 'avi', 'mpg','wma'*/); //Videos
$ext_music = array(/*'mp3', 'm4a', 'ac3', 'aiff', 'mid'*/); //Music
$ext_misc = array(/*'zip', 'rar','gzip'*/); //Archives


$ext=array_merge($ext_img, $ext_file, $ext_misc, $ext_video,$ext_music); //allowed extensions

?>
