<?php

/*
 * Suite di test per il business del modulo shop_warehouse
 */
class Shop_warehouseBusinessTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('shop_warehouse->Business');
		$suite->addTestDirectory(dirname(__FILE__) . '/shop_warehouse/business/');
		return $suite;
	}
}
