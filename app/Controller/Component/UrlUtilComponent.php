<?php
App::uses('Component', 'Controller');
class UrlUtilComponent extends Component {

	public $Util = null; // Load Util components

	public $Configuration = NULL; // Use Configuration model

	/**
	 * Called automatically after controller beforeFilter
	 * Stores refernece to controller object
	 * Merges Settings.history array in $config with default settings
	 *
	 * @param object $controller
	 */
	public function startup(Controller $controller) {
		$this->Controller = $controller;

		// Load other related components
		App::import('Component', 'Util');
		$this->Util = new UtilComponent(new ComponentCollection());
		$this->Util->startup($this->Controller);

		// Use model
		$this->Configuration = ClassRegistry::init('Configuration');
	}

    /**
     * Check whether the given URL is valid or not
     *
     * @return boolean
     */
	public function validate($url) {
	  	$code = 500;

	  	$companyName = $this->Configuration->findConfiguration('CompanyName', Configure::read('Config.type.system'));
	  	$companyWebsiteURL = 'http://' .$this->Configuration->findConfiguration('CompanyDomain', Configure::read('Config.type.system'));

	  	$curl = curl_init();
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	    curl_setopt($curl, CURLOPT_HEADER, 0);
	    curl_setopt($curl, CURLOPT_DNS_USE_GLOBAL_CACHE, true);
	    curl_setopt($curl, CURLOPT_USERAGENT, $companyName .' (' .$companyWebsiteURL .')');
	    $raw_result = curl_exec($curl);
	    $code = curl_getinfo($curl,CURLINFO_HTTP_CODE);

	  	return $code == 200;
	}

	/**
	 * Fetch web page content through URL
	 *
	 * @return string
	 */
	public function fetchContent($url){
		$content = '';
		$url = str_ireplace('&amp;','&',$url);
		$remote_charset = 'UTF-8';
		$timeout = 600;

		if (!preg_match('/^http/i',$url)) {
			if($this->Util->startsWith($url, "//")){
				$url = 'http:'.$url;							// Handles external references, e.g. //example.com/some/page
			}elseif($this->Util->startsWith($url, "/")){
				$url = Router::url('/', true) .substr($url, 1); // Handles internal URL, e.g. /pages/about
			}else{
				$url = 'http://'.$url;							// Handles some typo, e.g. example.com/some/page
			}
		}

		$companyName = $this->Configuration->findConfiguration('CompanyName', Configure::read('Config.type.system'));
	  	$companyWebsiteURL = 'http://' .$this->Configuration->findConfiguration('CompanyDomain', Configure::read('Config.type.system'));

		// Get web page content
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_DNS_USE_GLOBAL_CACHE, true);
		curl_setopt($curl, CURLOPT_USERAGENT, $companyName .' (' .$companyWebsiteURL .')');
		$rawResult = curl_exec($curl);
		$status = curl_getinfo($curl,CURLINFO_HTTP_CODE);
		curl_close($curl);
		$content = $this->addAbsoluteResources($rawResult,$url);

		// Recursively find content linked through embedded URLs and use the content to replace the embedded URL
		// Now we only get external CSS content and we will strip all JS, IFRAME and any other invisible tags, like <object>, for security reason.
		//TODO make sure the JS and IFRAME are safe and then we can allow client to add them to the email content
		$styles = array();
		if(preg_match_all('/<link(.*?)((\/>)|(>))/is', $content, $styles) > 0){
			$getRemoteContentAndReplaceStyleLink = function($component, $baseContent, $styleTag, $styleLink){
				if(stripos($baseContent, $styleTag) !== false){
					$remoteContent = $component->fetchContent($styleLink); // Only replace <link> tag, not recursively search all the HTTP links
					if(!empty($remoteContent)){

						// Try to get external CSS styles referenced in CSS files
						if(preg_match_all('/\@import(.*?);/is', $remoteContent, $refCss) > 0){
							$refIndex = 0;
							foreach ($refCss[1] as $cssFileName){
								if(stripos($remoteContent, $refCss[0][$refIndex]) !== false){
									$styleLinkPieces = explode("/", $styleLink);
									array_pop($styleLinkPieces);
									array_push($styleLinkPieces, preg_replace('/(")|(\')|(\s)/', '', $cssFileName));
									$styleRefLink = implode("/", $styleLinkPieces);
									$remoteCss = $component->fetchContent($styleRefLink);
									$remoteContent = str_replace($refCss[0][$refIndex], $remoteCss, $remoteContent);
									$refIndex++;
								}
							}
						}

						$baseContent = str_replace($styleTag, "<style>{$remoteContent}</style>", $baseContent);
					}
				}
				return $baseContent;
			};
			foreach($styles[0] as $style){
				if(stripos($style, ".css") !== false){
					if(preg_match('/href="(.*?)"/is', $style, $styleLink) === 1){
						$content = $getRemoteContentAndReplaceStyleLink($this, $content, $style, $styleLink[1]);
					}elseif (preg_match('/href=\'(.*?)\'/is', $style, $styleLink) === 1){
						$content = $getRemoteContentAndReplaceStyleLink($this, $content, $style, $styleLink[1]);
					}
				}else{
					$content = str_replace($style, " ", $content);
				}
			}
		}

		$content = $this->Util->stripHTMLTags($content, array("head","style"));
		return $content;
	}

	/**
	 * Change the relative resource path to absolute path
	 *
	 * @return string
	 */
	public function addAbsoluteResources($text,$url) {
  		$parts = parse_url($url);
  		$tags = array('src\s*=\s*','href\s*=\s*','action\s*=\s*','background\s*=\s*','@import\s+','@import\s+url\(');
  		foreach ($tags as $tag) {
			// we're only handling nicely formatted src="something" and not src=something, ie quotes are required
			// bit of a nightmare to not handle it with quotes.
    		preg_match_all('/('.$tag.')"([^"|\#]*)"/Uim', $text, $foundtags);
    		for ($i=0; $i< count($foundtags[0]); $i++) {
      			$match = $foundtags[2][$i];
      			$tagmatch = $foundtags[1][$i];
      			if (preg_match("#^(http|javascript|https|ftp|mailto):#i",$match)) {
        			// scheme exists, leave it alone
      			} elseif (preg_match("#\[.*\]#U",$match)) {
        			// placeholders used, leave alone as well
      			} elseif (preg_match("/^\//",$match)) {
        			// starts with /
        			$text = preg_replace('#'.preg_quote($foundtags[0][$i]).'#im',$tagmatch.'"'.$parts["scheme"].'://'.$parts["host"].$match.'"',$text,1);
      			} else {
        			$path = '';
        			if (isset($parts['path'])) {
          				$path = $parts["path"];
        			}
        			if (!preg_match('#/$#',$path)) {
          				$pathparts = explode('/',$path);
          				array_pop($pathparts);
          				$path = join('/',$pathparts);
          				$path .= '/';
        			}
        			$text = preg_replace('#'.preg_quote($foundtags[0][$i]).'#im',$tagmatch.'"'.$parts["scheme"].'://'.$parts["host"].$path.$match.'"',$text,1);
      			}
    		}
  		}
  		return $text;
	}

	/**
	 * Parse query stirng to key value pairs
	 * @param string $str
	 * @return multitype:|multitype:string multitype:
	 */
	public function parseQueryString($str) {
		if (empty($str)){ return array(); }
		$op = array();
		$pairs = explode("&", $str);
		foreach ($pairs as $pair) {
			if (strpos($pair,'=') !== false) {
				list($k, $v) = array_map("urldecode", explode("=", $pair));
				$op[$k] = $v;
			} else {
				$op[$pair] = '';
			}
		}
		return $op;
	}

	/**
	 * Remove disallowed params
	 * @param string $url
	 * @param array $disallowedParams
	 * @return string
	 */
	public function cleanUrl($url,$disallowedParams = array('PHPSESSID')) {
		$parsed = @parse_url($url);

		$uri = !empty($parsed['scheme']) ? $parsed['scheme'] .':' .((strtolower($parsed['scheme']) == 'mailto') ? '' : '//') : '';
  		$uri .= !empty($parsed['user']) ? $parsed['user'] .(!empty($parsed['pass']) ? ':' .$parsed['pass'] : '') .'@' : '';
	  	$uri .= !empty($parsed['host']) ? $parsed['host'] : '';
	  	$uri .= !empty($parsed['port']) ? ':' .$parsed['port'] : '';
	  	$uri .= !empty($parsed['path']) ? $parsed['path'] : '';
// 		$uri .= $parsed['query'] ? '?'.$parsed['query'] : '';

	  	// Handle query string below
	  	$params = array();
	  	if (empty($parsed['query'])) {
	  		$parsed['query'] = '';
	  	}
	  	if (strpos($parsed['query'],'&amp;')) {
	  		$pairs = explode('&amp;',$parsed['query']);
	  		foreach ($pairs as $pair) {
	  			if (strpos($pair,'=') !== false) {
	  				list($key,$val) = explode('=',$pair);
	  				$params[$key] = $val;
	  			} else {
	  				$params[$pair] = '';
	  			}
	  		}
	  	} else {
	  		// parse_str turns . into _ which is wrong
	  		// parse_str($parsed['query'],$params);
	  		$params= $this->parseQueryString($parsed['query']);
	  	}
		$query = '';
		foreach ($params as $key => $val) {
			if (!in_array($key,$disallowedParams)) {
				//0008980: Link Conversion for Click Tracking. no = will be added if key is empty.
				$query .= $key . ( $val != "" ? '=' . $val . '&' : '&' );
			}
		}
		$query = substr($query,0,-1);
		$uri .= $query ? '?'.$query : '';
		$uri .= !empty($parsed['fragment']) ? '#'.$parsed['fragment'] : '';

		return $uri;
	}
}
?>