<?php

/*
 * Suite di test per l'utility DateUtility
 */
class CoreutilsDateUtilityTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('coreutils->DateUtility');
		$suite->addTestDirectory(dirname(__FILE__) . '/coreutils/date/');
		return $suite;
	}
}