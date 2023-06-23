<?php

/*
 * Suite di test per l'utility StringUtility
 */
class CoreutilsStringUtilityTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('coreutils->StringUtility');
		$suite->addTestDirectory(dirname(__FILE__) . '/coreutils/string/');
		return $suite;
	}
}