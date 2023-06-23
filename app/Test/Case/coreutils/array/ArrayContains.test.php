<?php
App::uses("ArrayUtility", "modules/coreutils/utility");

class ArrayContainsTest extends CakeTestCase {
	public $list;
	public $simple;
	public $simpleValue;
	public $arrayObjects;

	public function setUp() {
		parent::setUp();
		$this->simple= array (
				"key1" => array (
						"id" => 1,
						"title" => "primo" 
				),
				"key2" => array (
						"id" => 2,
						"title" => "secondo" 
				),
				"key3" => array (
						"id" => 3,
						"title" => "terzo" 
				) 
		);
		$this->simpleValue= array (
				"key1" => 1,
				"key2" => 2,
				"key3" => 3 
		);
		$this->list= array (
				(int) 0 => array (
						'Test' => array (
								'id' => '1',
								'cod' => 'ENTITY001',
								'title' => 'PRIMA ENTITY',
								'description' => 'RIGA DA VERIFICARE AL PRIMO TEST ENTITY',
								'result' => '1',
								'created' => '2020-09-08 17:27:55',
								'modified' => null 
						) 
				),
				(int) 1 => array (
						'Test' => array (
								'id' => '2',
								'cod' => 'ENTITY002',
								'title' => 'SECONDA ENTITY',
								'description' => 'RIGA DA VERIFICARE AL SECONDO TEST ENTITY',
								'result' => '1',
								'created' => '2020-09-08 17:27:55',
								'modified' => null 
						) 
				) 
		);
		
		$this->arrayObjects= array (
				0 => new TestContainsClass(3, "terzo"),
				1 => new TestContainsClass(1, "primo"),
				2 => new TestContainsClass(2, "secondo") 
		);
	}

	function testContainsArrayKey() {
		$result1= ArrayUtility::containsArrayKey($this->list, "Test");
		$result2= ArrayUtility::containsArrayKey($this->list, "Test", "inesistente");
		$result3= ArrayUtility::containsArrayKey($this->list, "TestERROR", "id");
		$this->assertEquals($result1, true);
		$this->assertEquals($result2, false);
		$this->assertEquals($result3, false);
		
		$result4= ArrayUtility::containsArrayKey($this->simple, null, "id");
		$result5= ArrayUtility::containsArrayKey($this->simple, null, "inesistente");
		$result6= ArrayUtility::containsArrayKey($this->simple, "Test", "id");
		$this->assertEquals($result4, true);
		$this->assertEquals($result5, false);
		$this->assertEquals($result6, false);
	}

	function testContainsArrayKeyByValue() {
		$result1= ArrayUtility::containsArrayKeyByValue($this->list, 1, "Test");
		$result2= ArrayUtility::containsArrayKeyByValue($this->list, 1, "Test", "inesistente");
		$result3= ArrayUtility::containsArrayKeyByValue($this->list, 1, "TestERROR", "id");
		$result4= ArrayUtility::containsArrayKeyByValue($this->list, 7, "Test");
		$this->assertEquals($result1, true);
		$this->assertEquals($result2, false);
		$this->assertEquals($result3, false);
		$this->assertEquals($result4, false);
		
		$result5= ArrayUtility::containsArrayKeyByValue($this->simple, 1, null, "id");
		$result6= ArrayUtility::containsArrayKeyByValue($this->simple, 1, null, "inesistente");
		$result7= ArrayUtility::containsArrayKeyByValue($this->simple, 1, "Test", "id");
		$result8= ArrayUtility::containsArrayKeyByValue($this->simple, 7, null);
		$this->assertEquals($result5, true);
		$this->assertEquals($result6, false);
		$this->assertEquals($result7, false);
		$this->assertEquals($result8, false);
	}

	function testContainsObjectField() {
		$result1= ArrayUtility::containsObjectField($this->arrayObjects, "title");
		$result2= ArrayUtility::containsObjectField($this->arrayObjects, "inesistente");
		$this->assertEquals($result1, true);
		$this->assertEquals($result2, false);
	}

	function testContainsObjectFieldByValue() {
		$result1= ArrayUtility::containsObjectFieldByValue($this->arrayObjects, "primo", "title");
		$result2= ArrayUtility::containsObjectFieldByValue($this->arrayObjects, "quarto", "title");
		$result3= ArrayUtility::containsObjectFieldByValue($this->arrayObjects, "primo", "inesistente");
		$this->assertEquals($result1, true);
		$this->assertEquals($result2, false);
		$this->assertEquals($result3, false);
	}

	function testContains() {
		$result1= ArrayUtility::contains($this->simpleValue, 1);
		$result2= ArrayUtility::contains($this->simpleValue, 4);
		$this->assertEquals($result1, true);
		$this->assertEquals($result2, false);
		
		$result3= ArrayUtility::contains($this->simple ['key1'], 1);
		$result4= ArrayUtility::contains($this->simple ['key1'], 4);
		$this->assertEquals($result3, true);
		$this->assertEquals($result4, false);
	}

	function testGetContainsSuffix() {
		$result1= ArrayUtility::getContainsSuffix($this->simple ['key1'], "im");
		$result2= ArrayUtility::getContainsSuffix($this->simple ['key1'], "primo");
		$result3= ArrayUtility::getContainsSuffix($this->simple ['key2'], "ond");
		$result4= ArrayUtility::getContainsSuffix($this->simple ['key2'], "terzo");
		$this->assertEquals($result1, "primo");
		$this->assertEquals($result2, "primo");
		$this->assertEquals($result3, "secondo");
		$this->assertEquals($result4, null);
	}

	function testGetContainsSuffixKey() {
		$result1= ArrayUtility::getContainsSuffixKey($this->simple ['key1'], "itl");
		$result2= ArrayUtility::getContainsSuffixKey($this->simple ['key1'], "title");
		$result3= ArrayUtility::getContainsSuffixKey($this->simple ['key2'], "tit");
		$result4= ArrayUtility::getContainsSuffixKey($this->simple ['key2'], "cod");
		$this->assertEquals($result1, "primo");
		$this->assertEquals($result2, "primo");
		$this->assertEquals($result3, "secondo");
		$this->assertEquals($result4, null);
	}
}

class TestContainsClass {
	public $id;
	public $title;

	function __construct($id, $title) {
		$this->id= $id;
		$this->title= $title;
	}
}