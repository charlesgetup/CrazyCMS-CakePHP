<?php
App::uses('Component', 'Controller');
App::uses('ExtendPHP', 'Util');

class UtilComponent extends Component {

	private $extendPHP;

/**
 * Called automatically after controller beforeFilter
 * Stores refernece to controller object
 * Merges Settings.history array in $config with default settings
 *
 * @param object $controller
 */
	public function startup(Controller $controller) {
		$this->Controller = $controller;
		$this->extendPHP = new ExtendPHP();
	}

/**
 * Change verb to past tense, e.g. import => imported
 *
 * @return string
 */
	public function changeToPastTense($verb) {

        //TODO now only very simple case is implementd here. Update this method when new requirement comes in
        if(!empty($verb)){

        	$verb = strtolower($verb);

        	/* Change verb for special cases */
        	switch($verb){
        		case "edit":
        			$verb = "update";
        			break;
        	}

        	$verb .= $this->extendPHP->endsWith($verb, 'e') ? 'd' : 'ed';
        }

        return $verb;
	}

/**
 * Check whether haystack starts with the needle
 *
 * @return boolean
 */
    public function startsWith($haystack, $needle){
        return $this->extendPHP->startsWith($haystack, $needle);
    }

/**
 * Check whether haystack ends with the needle
 *
 * @return boolean
 */
    public function endsWith($haystack, $needle){
        return $this->extendPHP->endsWith($haystack, $needle);
    }

/**
 * PHP's strip_tags() function will remove tags, but it
 * doesn't remove scripts, styles, and other unwanted
 * invisible text between tags.  Also, as a prelude to
 * tokenizing the text, we need to insure that when
 * block-level tags (such as <p> or <div>) are removed,
 * neighboring words aren't joined.
 *
 * @param string $html
 * @param array $removeAllInvisibleTagsExcept
 * @param string $removeAllTags
 * @return string
 */
    public function stripHTMLTags($html, $removeAllInvisibleTagsExcept = array(), $removeAllTags = true ){
    	if($removeAllTags == true && !empty($removeAllInvisibleTagsExcept)){ $removeAllTags = false; }
    	$stripPatterns = array(
    		// Remove invisible content
    		in_array("head", $removeAllInvisibleTagsExcept) 	? "" : '@<head[^>]*?>.*?</head>@siu',
    		in_array("style", $removeAllInvisibleTagsExcept) 	? "" : '@<style[^>]*?>.*?</style>@siu',
    		in_array("script", $removeAllInvisibleTagsExcept) 	? "" : '@<script[^>]*?.*?</script>@siu',
    		in_array("object", $removeAllInvisibleTagsExcept) 	? "" : '@<object[^>]*?.*?</object>@siu',
    		in_array("embed", $removeAllInvisibleTagsExcept) 	? "" : '@<embed[^>]*?.*?</embed>@siu',
    		in_array("applet", $removeAllInvisibleTagsExcept) 	? "" : '@<applet[^>]*?.*?</applet>@siu',
    		in_array("noframes", $removeAllInvisibleTagsExcept) ? "" : '@<noframes[^>]*?.*?</noframes>@siu',
    		in_array("noscript", $removeAllInvisibleTagsExcept) ? "" : '@<noscript[^>]*?.*?</noscript>@siu',
    		in_array("noembed", $removeAllInvisibleTagsExcept) 	? "" : '@<noembed[^>]*?.*?</noembed>@siu',
    		in_array("iframe", $removeAllInvisibleTagsExcept) 	? "" : '@<iframe[^>]*?.*?</iframe>@siu',

    		// Add line breaks before & after blocks
    		'@<((br)|(hr))@iu',
    		'@</?((address)|(blockquote)|(center)|(del))@iu',
    		'@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
    		'@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
    		'@</?((table)|(th)|(td)|(caption))@iu',
    		'@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
    		'@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
    		'@</?((frameset)|(frame)|(iframe))@iu',
	    );
    	$stripPatterns = array_filter($stripPatterns);
    	$stripreplacement = array("\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0","\n\$0", "\n\$0",);
    	$needReplaceToEmptyStrCount = count($stripPatterns) - 8;
    	for($i = 0; $i < $needReplaceToEmptyStrCount; $i++){
    		array_unshift($stripreplacement, ' ');
    	}
    	$html = preg_replace($stripPatterns, $stripreplacement, $html);

    	return $removeAllTags ? strip_tags( $html ) : $html;
    }

/**
 * Remove white space from HTML
 *
 * @param String $buffer
 * @return stirng
 */
    public static function sanitizeHTML($buffer) {
    	$search = array (
    			'/\>[^\S ]+/s', // strip whitespaces after tags, except space
    			'/[^\S ]+\</s', // strip whitespaces before tags, except space
    			'/(\s)+/s'  // shorten multiple whitespace sequences
    	);

    	$replace = array (
    			'>',
    			'<',
    			'\\1'
    	);

    	$buffer = preg_replace ( $search, $replace, $buffer );

    	return $buffer;
    }

/**
 * Encode an email address to display on your website
 * @param string $email
 * @return string
 */
    public function encodeEmailAddress( $email ) {
    	$output = '';
    	for ($i = 0; $i < strlen($email); $i++){
    		$output .= '&#'.ord($email[$i]).';';
    	}
    	return $output;
    }
}

?>