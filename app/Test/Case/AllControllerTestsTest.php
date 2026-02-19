<?php
class AllControllerTestsTest extends CakeTestSuite {
	public static function suite() {
		$suite = new CakeTestSuite('All app level Controller tests (no plugin tests)');
		$suite->addTestDirectoryRecursive(TESTS . 'Case/Controller');
		return $suite;
	}
}
?>