<?php

/*
 * Suite di test per il modulo shop_payment
 */
class Shop_paymentTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('shop_payment');
		$suite->addTestDirectory(dirname(__FILE__) . '/shop_payment/');
		$suite->addTestDirectory(dirname(__FILE__) . '/shop_payment/business/');
		$suite->addTestDirectory(dirname(__FILE__) . '/shop_payment/delegate/');
		$suite->addTestDirectory(dirname(__FILE__) . '/shop_payment/controller/');
		return $suite;
	}
}
