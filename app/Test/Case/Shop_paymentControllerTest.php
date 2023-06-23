<?php

/*
 * Suite di test per il controller del modulo shop_payment
 */
class Shop_paymentControllerTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('shop_payment->Controller');
		$suite->addTestDirectory(dirname(__FILE__) . '/shop_payment/controller/');
		return $suite;
	}
}
