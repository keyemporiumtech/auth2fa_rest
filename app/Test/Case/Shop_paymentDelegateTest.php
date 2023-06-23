<?php

/*
 * Suite di test per il delegate del modulo shop_payment
 */
class Shop_paymentDelegateTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('shop_payment->Delegate');
		$suite->addTestDirectory(dirname(__FILE__) . '/shop_payment/delegate/');
		return $suite;
	}
}
