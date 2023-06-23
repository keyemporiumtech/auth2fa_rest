<?php
/*
 * Suite di test per l'utility PageUtility
 */
class CoreutilsPageUtilityTest extends PHPUnit_Framework_TestSuite {
	public static function suite() {
		$suite = new CakeTestSuite('coreutils->PageUtility');				
		$suite->addTestDirectory(dirname(__FILE__) . '/coreutils/page/');
		return $suite;
	}
}