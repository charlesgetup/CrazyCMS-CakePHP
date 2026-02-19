<?php
class AllEmailMarketingTestsTest extends CakeTestSuite {
	public static function suite() {
		$suite = new CakeTestSuite('All EmailMarketing plugin tests');
		$suite->addTestDirectoryRecursive(APP . 'Plugin' . DS . 'EmailMarketing' . DS . 'Test' . DS . 'Case');
		return $suite;
	}
}
?>