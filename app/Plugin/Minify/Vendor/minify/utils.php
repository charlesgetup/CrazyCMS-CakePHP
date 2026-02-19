<?php
require APP .'Plugin/Minify/Vendor/php-aaencoder/AAEncoder.php';
require APP .'Plugin/Minify/Vendor/php-aaencoder/AADecoder.php';

/**
 * Utility functions for generating URIs in HTML files
 *
 * @warning These functions execute min/groupsConfig.php, sometimes multiple times.
 * You must make sure that functions are not redefined, and if your use custom sources,
 * you must require_once __DIR__ . '/lib/Minify/Source.php' so that
 * class is available.
 *
 * @package Minify
 */

require __DIR__ . '/bootstrap.php';

/*
 * Get an HTML-escaped Minify URI for a group or set of files. By default, URIs
 * will contain timestamps to allow far-future Expires headers.
 *
 * <code>
 * <link rel="stylesheet" type="text/css" href="<?= Minify_getUri('css'); ?>" />
 * <script src="<?= Minify_getUri('js'); ?>"></script>
 * <script src="<?= Minify_getUri(array(
 *      '//scripts/file1.js'
 *      ,'//scripts/file2.js'
 * )); ?>"></script>
 * </code>
 *
 * @param mixed $keyOrFiles a group key or array of file paths/URIs
 * @param array $opts options:
 *   'farExpires' : (default true) append a modified timestamp for cache revving
 *   'debug' : (default false) append debug flag
 *   'charset' : (default 'UTF-8') for htmlspecialchars
 *   'minAppUri' : (default '/min') URI of min directory
 *   'rewriteWorks' : (default true) does mod_rewrite work in min app?
 *   'groupsConfigFile' : specify if different
 * @return string
 */
function Minify_getUri($keyOrFiles, $opts = array())
{
    return Minify_HTML_Helper::getUri($keyOrFiles, $opts);
}


/**
 * Get the last modification time of several source js/css files. If you're
 * caching the output of Minify_getUri(), you might want to know if one of the
 * dependent source files has changed so you can update the HTML.
 *
 * Since this makes a bunch of stat() calls, you might not want to check this
 * on every request.
 *
 * @param array $keysAndFiles group keys and/or file paths/URIs.
 * @return int latest modification time of all given keys/files
 */
function Minify_mtime($keysAndFiles, $groupsConfigFile = null)
{
    $gc = null;
    if (! $groupsConfigFile) {
        $groupsConfigFile = Minify_HTML_Helper::app()->groupsConfigPath;
    }
    $sources = array();
    foreach ($keysAndFiles as $keyOrFile) {
        if (is_object($keyOrFile)
            || 0 === strpos($keyOrFile, '/')
            || 1 === strpos($keyOrFile, ':\\')) {
            // a file/source obj
            $sources[] = $keyOrFile;
        } else {
            if (! $gc) {
                $gc = (require $groupsConfigFile);
            }
            foreach ($gc[$keyOrFile] as $source) {
                $sources[] = $source;
            }
        }
    }
    return Minify_HTML_Helper::getLastModified($sources);
}

/**
 * Remove comments in code base
 * @param string $content
 * @return string
 */
function Minify_removeComments($content){

	$regex = array(
		"`^([\t\s]+)`ism"						=> '',
		"`\/\/(\t|\s|TODO)+(.+?)[\n\r]`ism"		=> "",
		"`^\/\*(.+?)\*\/`ism"					=> "",
		"`^\/\*!(.+?)\*\/`ism"					=> "",
		"`([\n\A;]+)\/\*(.+?)\*\/`ism"			=> "$1",
		"`([\n\A;]+)\/\*!(.+?)\*\/`ism"			=> "$1",
		"`([\n\A;\s]+)//(.+?)[\n\r]`ism"		=> "$1\n",
		"`(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+`ism"	=> "\n"
	);
	$content = preg_replace(array_keys($regex),$regex,$content);

	return $content;
}

/**
 * Additional step to check the minify result
 * @param String $content
 * @param String $type
 */
function Minify_postHandler($content, $type){

	$content = Minify_removeComments($content);


	if($type == Minify::TYPE_CSS){

	}

	if($type == Minify::TYPE_JS){

	}

	return $content;
}

/**
 * Return unicode char by its code
 *
 * @param int $u
 * @return char
 */
function unichr($u) {
	return mb_convert_encoding('&#' . intval($u) . ';', 'UTF-8', 'HTML-ENTITIES');
}

function Minify_encryptor($content, $type, $isInline = false){

	$content = Minify_removeComments($content);

	if($type == Minify::TYPE_JS){

		// Obfuscate plain JS code
		$content = utf8_encode($content);

		// Mangle JS

// 		$errorLogFile	= realpath(ROOT_DIR . '/../error.log'); // Need write permission
// 		$descriptorspec = array(
// 			0 => array("pipe", "r"),
// 			1 => array("pipe", "w"),
// 			2 => array("file", APP ."tmp/logs/sys/error.log", "a"),
// 		);
// 		$cwd = APP ."tmp/cache/minify/";

// 		// Because the JS code is everywhere, we cannot mangle the property value globally.
// 		$command = APP .'Plugin/Minify/Vendor/UglifyJS2-3.6.4/bin/uglifyjs --compress --mangle --toplevel --ie8';
// 		$process = proc_open($command, $descriptorspec, $pipes, $cwd, $_ENV);
// 		if(is_resource($process)){
// 			fwrite($pipes[0], $content);
// 			fclose($pipes[0]);
// 			$mangledJs = stream_get_contents($pipes[1]);
// 			fclose($pipes[1]);
// 			$returnVal = proc_close($process);
// 			error_log("Uglify command returned: " .print_r($returnVal, true));
// 		}else{
// 			error_log("Uglify command: No resource");
// 		}

// 		$packer = new JavascriptPacker($mangledJs, 'Normal', true, false);
// 		$minifiedJs = trim($packer->pack());

// 		if(!empty($minifiedJs)){
// 			$content = utf8_encode($minifiedJs);
// 		}

		// Use AAEncoder to encrypt obfuscated JS
// 		$content = AAEncoder::encode($content, 1);
		if(!$isInline){
			$content = "() => {{$content}};"; // Put the encrypted function in anonymous function to prevent using same name to replace var & function names
		}

	}

	return $content;
}