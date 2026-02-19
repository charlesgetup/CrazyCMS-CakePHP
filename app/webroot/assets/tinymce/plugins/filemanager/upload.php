<?php
session_start ();
if ($_SESSION ["verify"] != "FileManager4TinyMCE")
	die ( 'forbiden' );

include ('config.php');
include ('utils.php');

$ds = DIRECTORY_SEPARATOR;

$storeFolder = $_POST ['path'];
$storeFolderThumb = $_POST ['path_thumb'];

if (! empty ( $_FILES ) && $upload_files) {

	$tempFile = $_FILES ['file'] ['tmp_name'];

	$targetPath = $base_url. $ds . $storeFolder . $ds;
	$targetPathThumb = $storeFolderThumb . $ds;

	$targetFile = $targetPath . $_FILES ['file'] ['name'];
	$targetFileThumb = $targetPathThumb . $_FILES ['file'] ['name'];

	if(stristr($targetFile, $base_url)){
		$targetFile = str_ireplace($base_url, "", $targetFile);
	}
	$targetFile = str_ireplace("//", "/", $targetFile);

	$tempFileName = basename($tempFile);
	if(stristr($targetFile, "/{$tempFileName}")){
		$targetFile = str_ireplace("/{$tempFileName}", "", $targetFile);
	}

	$AmazonS3->amazonHeaders = array(
		'x-amz-acl' => 'public-read'
	);
	$AmazonS3->put ( $tempFile, $targetFile );

	if (in_array ( strtolower ( substr ( strrchr ( $_FILES ['file'] ['name'], '.' ), 1 ) ), $ext_img ))
		$is_img = true;
	else
		$is_img = false;

	// Temproray save it and let it has mine type
	$localTmpImg = create_local_tmp_folder() .$_FILES ['file'] ['name'];
	if(file_exists($tempFile)){
		move_uploaded_file ( $tempFile, $localTmpImg );
		$targetFile = $localTmpImg;
	}
	$targetFileThumb = str_ireplace("//", "/", $targetFileThumb);

	if ($is_img) {
		create_img_gd ( $targetFile, $targetFileThumb, 122, 91 );

		$imginfo = getimagesize ( $targetFile );
		$srcWidth = $imginfo [0];
		$srcHeight = $imginfo [1];

		if ($image_resizing) {

			if ($image_width == 0) {
				if ($image_height == 0) {
					$image_width = $srcWidth;
					$image_height = $srcHeight;
				} else {
					$image_width = $image_height * $srcWidth / $srcHeight;
				}
			} elseif ($image_height == 0) {
				$image_height = $image_width * $srcHeight / $srcWidth;
			}
			$srcWidth = $image_width;
			$srcHeight = $image_height;
			create_img_gd ( $targetFile, $targetFile, $image_width, $image_height );
		}

		// max resizing limit control
		$resize = false;
		if ($image_max_width != 0 && $srcWidth > $image_max_width) {
			$resize = true;
			$srcHeight = $image_max_width * $srcHeight / $srcWidth;
			$srcWidth = $image_max_width;
		}

		if ($image_max_height != 0 && $srcHeight > $image_max_height) {
			$resize = true;
			$srcWidth = $image_max_height * $srcWidth / $srcHeight;
			$srcHeight = $image_max_height;
		}
		if ($resize){
			create_img_gd ( $targetFile, $targetFile, $srcWidth, $srcHeight );
		}
	}

	if(file_exists($localTmpImg)){
		unlink($localTmpImg);
	}
}
if (isset ( $_POST ['submit'] )) {
	$query = http_build_query ( array (
			'type' => $_POST ['type'],
			'lang' => $_POST ['lang'],
			'subfolder' => $_POST ['subfolder'],
			'popup' => $_POST ['popup'],
			'field_id' => $_POST ['field_id'],
			'editor' => $_POST ['editor'],
			'fldr' => $_POST ['fldr']
	) );
	header ( "location: dialog.php?" . $query );
}

?>
