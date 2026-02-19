<?php
class ExtendPHP {

/**
 * Check whether haystack starts with the needle
 *
 * @return boolean
 */
	public function startsWith($haystack, $needle){
		return $needle === "" || strpos(strtolower($haystack), strtolower($needle)) === 0;
	}

/**
 * Check whether haystack ends with the needle
 *
 * @return boolean
 */
	public function endsWith($haystack, $needle){
		return $needle === "" || substr(strtolower($haystack), -strlen(strtolower($needle))) === $needle;
	}

/**
 *
 * @param string $html
 * @param array $tags e.g.array('p', 'a')
 * @return string
 */
	public function stripTagsWithContent($html, $tags){

		return preg_replace('#<(' . implode( '|', $tags) . ')(?:[^>]+)?>.*?</\1>#s', '', $html);
	}

/**
 * Check whether an URL is valid
 * @param string $url
 * @return boolean
 */
	public function isUrlExists($url){

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_exec($ch);

		$responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		curl_close($ch);

		return $responseCode == 200;
	}

/**
 * Remove non empty dir
 * @param string $dir
 */
	public function rrmdir($dir) {
		if (is_dir($dir)) {
			$objects = scandir($dir);
			foreach ($objects as $object) {
				if ($object != "." && $object != "..") {
					if (filetype($dir."/".$object) == "dir"){
						$this->rrmdir($dir."/".$object);
					}else{
						unlink($dir."/".$object);
					}
				}
			}
			reset($objects);
			rmdir($dir);
		}
	}
}
?>