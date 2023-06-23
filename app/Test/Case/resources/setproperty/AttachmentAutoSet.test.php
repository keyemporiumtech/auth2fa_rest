<?php
App::uses("AttachmentBS", "modules/resources/business");
App::uses("MysqlUtilityTest", "Test/utility");
App::uses("FileUtility", "modules/coreutils/utility");

class AttachmentAutoSetTest extends CakeTestCase {

	function testGet() {
		$bs= new AttachmentBS();
		$obj= $bs->unique(1);
		$this->assertEquals($obj ['Attachment'] ['id'], 1);
		// campi calcolati
		$path= $obj ['Attachment'] ['path'];
		$this->assertEquals($obj ['Attachment'] ['name'], FileUtility::getNameByPath($path));
		$this->assertEquals($obj ['Attachment'] ['size'], FileUtility::getSizeByPath($path));
		$this->assertEquals($obj ['Attachment'] ['ext'], FileUtility::getExtensionByPath($path));
		$this->assertEquals($obj ['Attachment'] ['mimetype'], FileUtility::getMimeTypeByPath($path));
		$this->assertEquals(! empty($obj ['Attachment'] ['content']), true);
	}

	function testAvoidContent() {
		$bs= new AttachmentBS();
		$bs->addPropertyDao("avoidContent", true);
		$obj= $bs->unique(1);
		$this->assertEquals(empty($obj ['Attachment'] ['content']), true);
	}
}