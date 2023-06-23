<?php
App::uses("PageUtility", "modules/coreutils/utility");

class PageFormTest extends ControllerTestCase {
	public $arrayParams;
	public $arrayParamsWithNull;
	public $arrayCake;

	public function setUp() {
		parent::setUp();
		
		$this->arrayParams= array (
				"key1" => "page",
				"key2" => 2,
				"key3" => "N" 
		);
		$this->arrayParamsWithNull= array (
				"key1" => "page",
				"key2" => 2,
				"key3" => "N",
				"key4" => null,
				"key5" => "",
				"key6" => "null" 
		);
		$this->arrayCake= array (
				'Test' => array (
						'id' => 1,
						'cod' => 'ENTITY001',
						'title' => 'PRIMA ENTITY',
						'description' => 'RIGA DA VERIFICARE AL PRIMO TEST ENTITY',
						'result' => '1',
						'created' => '2020-09-08 17:27:55',
						'modified' => null 
				) 
		);
	}

	function testGetRequest() {
		$post1= $this->testAction('coreutils/testParams', array (
				'data' => $this->arrayParams,
				'return' => 'view',
				'method' => 'post' 
		));
		$result1= PageUtility::getRequest($this->controller->request);
		$this->assertEquals(count($this->controller->request->query) == 0, true);
		$this->assertEquals(count($this->controller->request->data) > 0, true);
		$this->assertEquals($result1 ['key1'], "page");
		
		$post2= $this->testAction('coreutils/testParams', array (
				'data' => $this->arrayParams,
				'return' => 'view',
				'method' => 'get' 
		));
		$result2= PageUtility::getRequest($this->controller->request);
		$this->assertEquals(count($this->controller->request->query) > 0, true);
		$this->assertEquals(count($this->controller->request->data) == 0, true);
		$this->assertEquals($result2 ['key1'], "page");
	}

	function testGetStringFieldRequest() {
		$post1= $this->testAction('coreutils/testParams', array (
				'data' => $this->arrayParamsWithNull,
				'return' => 'view',
				'method' => 'post' 
		));
		$result1= PageUtility::getStringFieldRequest("key2", $this->controller->request);
		$this->assertEquals($result1, "2");
		$result2= PageUtility::getStringFieldRequest("key4", $this->controller->request);
		$this->assertEquals($result2, "");
		$result3= PageUtility::getStringFieldRequest("key6", $this->controller->request);
		$this->assertEquals($result3, "");
	}

	function testGetObjectFieldRequest() {
		$post1= $this->testAction('coreutils/testParams', array (
				'data' => $this->arrayCake,
				'return' => 'view',
				'method' => 'post' 
		));
		$result1= PageUtility::getObjectFieldRequest("Test", "id", $this->controller->request);
		$this->assertEquals($result1, "1");
	}
}
