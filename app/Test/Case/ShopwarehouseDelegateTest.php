<?php

/*
 * Suite di test per il delegate del modulo shop_warehouse
 */
class ShopwarehouseDelegateTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('shop_warehouse->Delegate');
		$suite->addTestDirectory(dirname(__FILE__) . '/shop_warehouse/delegate/');
		return $suite;
	}
}
