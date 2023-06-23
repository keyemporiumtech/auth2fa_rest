<?php

/*
 * Suite di test per il controller del modulo shop_warehouse
 */
class ShopwarehouseControllerTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('shop_warehouse->Controller');
		$suite->addTestDirectory(dirname(__FILE__) . '/shop_warehouse/controller/');
		return $suite;
	}
}
