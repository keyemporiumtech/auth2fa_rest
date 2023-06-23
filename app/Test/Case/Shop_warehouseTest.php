<?php

/*
 * Suite di test per il modulo shop_warehouse
 */
class Shop_warehouseTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('shop_warehouse');
		$suite->addTestDirectory(dirname(__FILE__) . '/shop_warehouse/');
		$suite->addTestDirectory(dirname(__FILE__) . '/shop_warehouse/business/');
		$suite->addTestDirectory(dirname(__FILE__) . '/shop_warehouse/delegate/');
		$suite->addTestDirectory(dirname(__FILE__) . '/shop_warehouse/controller/');
		return $suite;
	}
}
