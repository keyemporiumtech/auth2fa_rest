<?php
/*
 * Suite di test per i modulo coreutils
 */
class CoreutilsTest extends PHPUnit_Framework_TestSuite {
	public static function suite() {
		$suite = new CakeTestSuite('coreutils');		
		$suite->addTestDirectory(dirname(__FILE__) . '/coreutils/');
		$suite->addTestDirectory(dirname(__FILE__) . '/coreutils/array/');
		$suite->addTestDirectory(dirname(__FILE__) . '/coreutils/date/');
		$suite->addTestDirectory(dirname(__FILE__) . '/coreutils/page/');
		$suite->addTestDirectory(dirname(__FILE__) . '/coreutils/string/');
		return $suite;
	}
}