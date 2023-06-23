<?php
App::uses("ArrayUtility", "modules/coreutils/utility");

class ArrayStringTest extends CakeTestCase {
	public $simpleValue;
	public $simple;
	public $list;

	public function setUp() {
		parent::setUp();
		$this->simpleValue= array (
				"key1" => 1,
				"key2" => 2,
				"key3" => 3 
		);
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
	}

	function testGetStringByList() {
		$result1= ArrayUtility::getStringByList($this->simpleValue);
		$result2= ArrayUtility::getStringByList($this->simpleValue, false, ",", "*");
		$result3= ArrayUtility::getStringByList($this->simpleValue, true, ";");
		$this->assertEquals($result1, "1,2,3");
		$this->assertEquals($result2, "*1*,*2*,*3*");
		$this->assertEquals($result3, "0;1;2;3");
	}

	function testGetStringIdByArray() {
		$result1= ArrayUtility::getStringIdByArray($this->simple, null, "title");
		$result2= ArrayUtility::getStringIdByArray($this->simple, null, "title", false, ",", "*");
		$result3= ArrayUtility::getStringIdByArray($this->simple, null, "title", true, ";");
		$this->assertEquals($result1, "primo,secondo,terzo");
		$this->assertEquals($result2, "*primo*,*secondo*,*terzo*");
		$this->assertEquals($result3, "0;primo;secondo;terzo");
		
		$result4= ArrayUtility::getStringIdByArray($this->list, "Test", "cod");
		$result5= ArrayUtility::getStringIdByArray($this->list, "Test", "cod", false, ",", "*");
		$result6= ArrayUtility::getStringIdByArray($this->list, "Test", "cod", true, ";");
		$this->assertEquals($result4, "ENTITY001,ENTITY002");
		$this->assertEquals($result5, "*ENTITY001*,*ENTITY002*");
		$this->assertEquals($result6, "0;ENTITY001;ENTITY002");
	}

	function testSplitIdString() {
		$result1= ArrayUtility::splitIdString("ENTITY001,ENTITY002");
		$this->assertEquals(count($result1) > 0, true);
		$this->assertEquals($result1 [0], "ENTITY001");
		
		$result2= ArrayUtility::splitIdString("*ENTITY001*,*ENTITY002*", ",", "*");
		$this->assertEquals(count($result2) > 0, true);
		$this->assertEquals($result2 [0], "ENTITY001");
		
		// split errato
		$result3= ArrayUtility::splitIdString("ENTITY001,ENTITY002", ";");
		$this->assertEquals(count($result3) > 0, true);
		$this->assertEquals($result3 [0], "ENTITY001,ENTITY002");
		
		$result4= ArrayUtility::splitIdString("ENTITY001;ENTITY002", ";");
		$this->assertEquals(count($result4) > 0, true);
		$this->assertEquals($result4 [0], "ENTITY001");
	}

	function testToPrintString() {
		$result1= ArrayUtility::toPrintString($this->simpleValue);
		$result2= ArrayUtility::toPrintString($this->simpleValue, false);
		$result3= ArrayUtility::toPrintString($this->simple);
		$result4= ArrayUtility::toPrintString($this->simple, false);
		$this->assertEquals($result1, "[key1 = 1] [key2 = 2] [key3 = 3] ");
		$this->assertEquals($result2, "[1] [2] [3] ");
		$this->assertEquals($result3, "[key1 = [id = 1] [title = primo] ] [key2 = [id = 2] [title = secondo] ] [key3 = [id = 3] [title = terzo] ] ");
		$this->assertEquals($result4, "[[id = 1] [title = primo] ] [[id = 2] [title = secondo] ] [[id = 3] [title = terzo] ] ");
	}
}

