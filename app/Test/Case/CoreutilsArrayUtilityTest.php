<?php
/*
 * Suite di test per l'utility ArrayUtility
 */
class CoreutilsArrayUtilityTest extends PHPUnit_Framework_TestSuite {
	public static function suite() {
		$suite = new CakeTestSuite('coreutils->ArrayUtility');				
		$suite->addTestDirectory(dirname(__FILE__) . '/coreutils/array/');
		return $suite;
	}
}