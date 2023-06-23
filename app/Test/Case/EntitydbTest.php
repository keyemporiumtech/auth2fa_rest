<?php
/*
 * Suite di test per i modulo coreutils
 */
class EntitydbTest extends PHPUnit_Framework_TestSuite {
	public static function suite() {
		$suite = new CakeTestSuite('entitydb');		
		$suite->addTestDirectory(dirname(__FILE__) . '/entitydb/');		
		return $suite;
	}
}