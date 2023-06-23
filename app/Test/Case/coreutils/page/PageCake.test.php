<?php
App::uses("PageUtility", "modules/coreutils/utility");

class PageCakeTest extends CakeTestCase {
	public $arrayParams;

	public function setUp() {
		parent::setUp();
		
		$this->arrayParams= array (
				"key1" => "page",
				"key2" => 2,
				"key3" => "N" 
		);
	}

	function testGetLinkCake() {
		$result1= PageUtility::getLinkCake("myController", "myAction", $this->arrayParams);
		$this->assertEquals($result1, "/myController/myAction/page/2/N");
	}

	function testGetLinkCakeForRedirect() {
		$result1= PageUtility::getLinkCakeForRedirect("myAction", "myController", $this->arrayParams);
		$this->assertEquals($result1 ['controller'], "myController");
		$this->assertEquals($result1 ['action'], "myAction");
		$this->assertEquals($result1 ['key1'], "page");
	}

	function testGetLinkCakeQueryMode() {
		$result1= PageUtility::getLinkCakeQueryMode("myController", "myAction", $this->arrayParams);
		$this->assertEquals($result1, "/myController/myAction?key1=page&key2=2&key3=N");
	}

	function testGetLinkCakeForRedirectQueryMode() {
		$result1= PageUtility::getLinkCakeForRedirectQueryMode("myAction", "myController", $this->arrayParams);
		$this->assertEquals($result1 ['controller'], "myController");
		$this->assertEquals($result1 ['action'], "myAction");
		$this->assertEquals($result1 ['?'] ['key1'], "page");
	}
}
