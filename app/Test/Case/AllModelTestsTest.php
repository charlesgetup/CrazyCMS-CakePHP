<?php
class AllModelTestsTest extends CakeTestSuite {
	public static function suite() {
		$suite = new CakeTestSuite('All app level Model tests (no plugin tests)');
		$suite->addTestDirectoryRecursive(TESTS . 'Case/Model');
		return $suite;
	}
}
?>