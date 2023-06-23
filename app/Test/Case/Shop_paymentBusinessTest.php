<?php

/*
 * Suite di test per il business del modulo shop_payment
 */
class Shop_paymentBusinessTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('shop_payment->Business');
		$suite->addTestDirectory(dirname(__FILE__) . '/shop_payment/business/');
		return $suite;
	}
}
