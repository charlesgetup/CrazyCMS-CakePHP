<?php
/**
 * This shell should be added into cron job list
 *
 * Cron job: 0 *\/12 * * * cd /full/path/to/app && Console/cake EmailMarketing.cleanup_h_t_m_l2_canvas_tmp_images -u 1 (User ID 1 is a system admin user)
 */

$maxExec = (int) ini_get('max_execution_time');
define('MAX_EXEC', $maxExec < 1 ? 0 : ($maxExec - 5));//reduces 5 seconds to ensure the execution of the DEBUG
define('INIT_EXEC', time());
define('SECPREFIX', 'h2c_');

App::uses ( 'EmailMarketingAppShell', 'EmailMarketing.Console/Command' );
class CleanupHTML2CanvasTmpImagesShell extends EmailMarketingAppShell {

	public $tmpImagesFolder;

	public function initialize(){
		parent::initialize();

		$this->tmpImagesFolder = WWW_ROOT .'assets' .DS .'html2canvas' .DS .'tmp-remote-img';
	}

	public function startup(){
		parent::startup();

		$this->removeOldFiles();
	}

/**
 * Remove old files defined by CCACHE
 *
 * This function and constants are from html2canvas-proxy.php
 *
 * @return void           return always void
 */
	private function removeOldFiles()
	{
		$p = $this->tmpImagesFolder . DS;
		if (
		(MAX_EXEC === 0 || (time() - INIT_EXEC) < MAX_EXEC) && //prevents this function locks the process that was completed
		(file_exists($p) || is_dir($p))
		) {
			$h = opendir($p);
			if (false !== $h) {
				while (false !== ($f = readdir($h))) {
					if (
					is_file($p . $f) && is_dir($p . $f) === false &&
					strpos($f, SECPREFIX) !== false &&
					(INIT_EXEC - filectime($p . $f)) > (CCACHE * 2)
					) {
						unlink($p . $f);
					}
				}
			}
		}
	}
}
?>