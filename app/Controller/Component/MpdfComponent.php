<?php
/**
 * Component for working with mPDF class.
 * mPDF has to be in the vendors directory.
 */
App::uses('Component', 'Controller');
class MpdfComponent extends Component {

    /**
     * Instance of mPDF class
     * @var object
     */
    protected $pdf;

    /**
     * Default values for mPDF constructor
     * @var array
     */
    protected $_configuration = array(
        // mode: 'c' for core fonts only, 'utf8-s' for subset etc.
        'mode' 			=> 'utf8-s',
        // page format: 'A0' - 'A10', if suffixed with '-L', force landscape
        'format' 		=> 'A4',
         // default font size in points (pt)
        'font_size' 	=> 0,
        // default font
        'font' 			=> NULL,
        // page margins in mm
        'margin_left' 	=> 15,
        'margin_right' 	=> 15,
        'margin_top'	=> 5,
        'margin_bottom' => 5,
        'margin_header' => 0,
        'margin_footer' => 0
    );

    /**
     * Flag set to true if mPDF was initialized
     * @var bool
     */
    protected $_init = false;

    /**
     * Name of the file on the output
     * @var string
     */
    protected $_filename = NULL;

    /**
     * Destination - posible values are I, D, F, S
     * @var string
     */
    protected $_output = 'I';

    /**
     * Override mPDF class public properties
     */
    public $CSSselectMedia;

    /**
     * Initialize
     * Add vendor and define mPDF class.
     */
    public function init($configuration = array()) {
        // mPDF class has many notices - suppress them
        // import mPDF
        App::import('Vendor', 'Mpdf/mpdf');
        if (!class_exists('mPDF'))
            throw new CakeException('Vendor class mPDF not found!');
        // override default values
        $c = array();
        foreach ($this->_configuration as $key => $val)
            $c[$key] = array_key_exists($key, $configuration) ? $configuration[$key] : $val;
        // initialize
        $this->pdf = new mPDF($c['mode'], $c['format'], $c['font_size'], $c['font'], $c['margin_left'], $c['margin_right'], $c['margin_top'], $c['margin_bottom'], $c['margin_header'], $c['margin_footer']);
        $this->_init = true;
    }

    /**
     * Set filename of the output file
     */
    public function setFilename($filename) {
        $this->_filename = (string)$filename;
    }

    /**
     * Set destination of the output
     */
    public function setOutput($output) {
        if (in_array($output, array('I', 'D', 'F', 'S')))
            $this->_output = $output;
    }

    public function saveFile(Controller $controller) {
        if ($this->_init) {
        	$this->pdf->keep_table_proportions = true;
        	$this->pdf->use_kwt = true;

        	// Force select a css media, and make the PDF output not responsive. default value: print
        	if(!empty($this->CSSselectMedia)){
        		$this->__set('CSSselectMedia', $this->CSSselectMedia);
        	}

        	$output = $this->__getExternalCSSFileContent((string)$controller->response);

            $this->pdf->WriteHTML($output);
            if($this->_output == 'S'){
                return $this->pdf->Output($this->_filename, $this->_output);
            }
            $this->pdf->Output($this->_filename, $this->_output);
            exit;
        }
    }

    /**
     * Shutdown of the component
     * View is rendered but not yet sent to browser.
     */
    public function shutdown(Controller $controller) {
        if ($this->_init) {
            $this->pdf->WriteHTML((string)$controller->response);
            $this->pdf->Output($this->_filename, $this->_output);
            exit;
        }
    }

    /**
     * Passing method calls and variable setting to mPDF library.
     */
    public function __set($name, $value) {
    	if ($this->_init) {
    		$this->pdf->$name = $value;
    	}
    }

    public function __get($name) {
        return $this->_init ? $this->pdf->$name : null;
    }

    public function __isset($name) {
        return $this->_init ? isset($this->pdf->$name) : false;
    }

    public function __unset($name) {
    	if($this->__isset($name)){
    		unset($this->pdf->$name);
    	}
    }

    public function __call($name, $arguments) {
        if($this->_init){
        	call_user_func_array(array($this->pdf, $name), $arguments);
        }
    }

    /**
     * Parse HTML and get external CSS file content and use CSS content to replace those external tags
     * @param string $html
     */
    private function __getExternalCSSFileContent($html) {

		$pattern = '/<link rel="stylesheet" type="text\/css" href="(.+)" \/>/';
		$matches = array();
		preg_match_all($pattern, $html, $matches);

		if(!empty($matches) && count($matches) == 2 && !empty($matches[1]) && is_array($matches[1])){

			// Prepare CSS file content
			$cssFiles = array();
			foreach($matches[1] as $cssPath){

				$url = FULL_BASE_URL .$cssPath; // Handle include path or URL

				// Remove query string
				if(stristr($cssPath, ".css?") !== FALSE){
					$cssPathChecker = stristr($cssPath, ".css?", true) .".css";
				}
				$cssChecker = DS ."css" .DS;

				if(file_exists($cssPathChecker)){

					// Handle absolute path
					$url = $cssPathChecker;

				}elseif(substr($cssPathChecker, 0, 5) != $cssChecker && stristr($cssPathChecker, $cssChecker)){

					// Handle incorrect path (This happened in unit tests)
					$cssPathChecker = explode($cssChecker, $cssPathChecker);
					$url = WWW_ROOT ."css" .DS .$cssPathChecker[1];

				}

				$fileContent = file_get_contents($url);
				array_push($cssFiles, $fileContent);
			}

			// Replace External CSS tag with content
			for($i = 0; $i < count($matches[0]); $i++){
				$tag = $matches[0][$i];
				$cssContent = '<style>' .$cssFiles[$i] .'</style>';
				$html = str_replace($tag, $cssContent, $html);
			}
		}

		return $html;
    }
}