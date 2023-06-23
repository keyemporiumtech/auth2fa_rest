<?php
App::uses("PageUtility", "modules/coreutils/utility");

class PagePathTest extends CakeTestCase {
	public $arrayParams;
	public $array;

	public function setUp() {
		parent::setUp();
		
		$this->arrayParams= array (
				"key1" => "page",
				"key2" => 2,
				"key3" => "?cod=1" 
		);
		
		$this->array= array (
				"key1" => "page",
				"key2" => 2,
				"key3" => "N" 
		);
	}

	function testGetUrlByArray() {
		$result1= PageUtility::getUrlByArray($this->arrayParams);
		$result2= PageUtility::getUrlByArray($this->arrayParams, "http://");
		$result3= PageUtility::getUrlByArray($this->arrayParams, "http://", true);
		$this->assertEquals($result1, "page/2?cod=1");
		$this->assertEquals($result2, "http://page/2?cod=1");
		$this->assertEquals($result3, "http://page\\2?cod=1");
	}

	function testGetStringParametersByArray() {
		$result1= PageUtility::getStringParametersByArray($this->array);
		$this->assertEquals($result1, "key1=page&key2=2&key3=N");
	}

	function testGetArrayByStringParameters() {
		$result1= PageUtility::getArrayByStringParameters("key1=1&key2=ciao&key3=2");
		$this->assertEquals(count($result1), 3);
		$this->assertEquals($result1 ['key1'], "1");
		$this->assertEquals($result1 ['key2'], "ciao");
	}
}
