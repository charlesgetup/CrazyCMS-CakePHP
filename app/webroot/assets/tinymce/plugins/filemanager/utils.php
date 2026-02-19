<?php

if($_SESSION["verify"] != "FileManager4TinyMCE") die('forbiden');

App::uses('AmazonS3', 'AmazonS3.Lib');
$AmazonS3 = new AmazonS3(array(Configure::read('System.aws.s3.accesskey'), Configure::read('System.aws.s3.secret'), Configure::read('System.aws.s3.bucket.name')));

function deleteDir($dir) {

	if(!$AmazonS3){
		App::uses('AmazonS3', 'AmazonS3.Lib');
		$AmazonS3 = new AmazonS3(array(Configure::read('System.aws.s3.accesskey'), Configure::read('System.aws.s3.secret'), Configure::read('System.aws.s3.bucket.name')));
	}

	$files = $AmazonS3->listBucket('?prefix=' .$dir);

    if (empty($files)) return true;

    if(!is_array($files)) $files = [$files];

    if(empty($base_url)){
    	$base_url = Configure::read('System.aws.s3.bucket.link.prefix');
    }

    // Delete all the files under this dir
    foreach($files as $file){

    	if(strpos($file,$base_url)!==FALSE){
    		$file = str_replace($base_url, "", $file);
    	}
    	$file = str_replace(" ", "+", $file);

    	try {
    		$AmazonS3->delete($file);

    	} catch(InvalidArgumentException $iae){

    		// If the given file is not a "file", then it may be a dir
    		deleteDir($file);

    	} catch (Exception $e) {

    		return false;
    	}

    }

    return true;
}

App::uses('AppModel','Model');
function create_local_tmp_folder(){

	$appModel = new AppModel();

	$absoluteFilePath = $appModel->getUserFileSavedPath() .$_SESSION["Auth"]["User"]["id"] ."/EmailMarketing/media/";

	if (!is_dir($absoluteFilePath)) {
		if (!mkdir($absoluteFilePath, 0777, true)) {
			throw new ForbiddenException(__("Cannot create local media folder"));
		}
	}
	return $absoluteFilePath;
}

/**
 *
 * @param string $imgfile
 * @param string $imgthumb	This should be the remote path (no file name) and we will assign a local tmp path to generate the image
 * @param string $newwidth
 * @param string $newheight
 */
function create_img_gd($imgfile, $imgthumb, $newwidth, $newheight="") {

	if(!$AmazonS3){
		App::uses('AmazonS3', 'AmazonS3.Lib');
		$AmazonS3 = new AmazonS3(array(Configure::read('System.aws.s3.accesskey'), Configure::read('System.aws.s3.secret'), Configure::read('System.aws.s3.bucket.name')));
	}

    require_once('php_image_magician.php');
    $magicianObj = new imageLib($imgfile);

    // *** Resize to best fit then crop
    $magicianObj -> resizeImage($newwidth, $newheight, 'crop');

    // *** Save resized image as a PNG
    $localTmpPath = create_local_tmp_folder();
    $imgfileBaseName = basename($imgfile);
    $localTmpPath .= $imgfileBaseName;

    $magicianObj -> saveImage($localTmpPath);

    // *** Put the saved local image to S3
    $imgthumbArr = pathinfo($imgthumb);
    $imgthumb = $imgthumbArr['dirname'];
    if(stristr($imgthumb, $base_url)){
    	$imgthumb = str_ireplace($base_url, "", $imgthumb);
    }

	$AmazonS3->amazonHeaders = array(
		'x-amz-acl' => 'public-read'
	);
    $AmazonS3->put($localTmpPath, $imgthumb);

    // *** Delete local tmp file
    unlink($localTmpPath);
}

function makeSize($size) {
   $units = array('B','KB','MB','GB','TB');
   $u = 0;
   while ( (round($size / 1024) > 0) && ($u < 4) ) {
     $size = $size / 1024;
     $u++;
   }
   return (number_format($size, 1, ',', '') . " " . $units[$u]);
}

function create_folder($path=false,$path_thumbs=false){

	if(!$AmazonS3){
		App::uses('AmazonS3', 'AmazonS3.Lib');
		$AmazonS3 = new AmazonS3(array(Configure::read('System.aws.s3.accesskey'), Configure::read('System.aws.s3.secret'), Configure::read('System.aws.s3.bucket.name')));
	}

	$localTmpFilePath = WWW_ROOT .DS .'files' .DS .'tmp' .DS .'empty';

	$AmazonS3->amazonHeaders = array(
		'x-amz-acl' => 'public-read'
	);

	if($path){
		$AmazonS3->put($localTmpFilePath, $path);
	}

	if($path_thumbs){
		$AmazonS3->put($localTmpFilePath, $path_thumbs);
	}
}

?>