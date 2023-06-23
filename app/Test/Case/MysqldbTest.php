<?php
/*
 * Suite di test per i modulo mysqldb
 */
class MysqldbTest extends PHPUnit_Framework_TestSuite {
	public static function suite() {
		$suite = new CakeTestSuite('mysqldb');		
		$suite->addTestDirectory(dirname(__FILE__) . '/mysqldb/');			
		return $suite;
	}
}