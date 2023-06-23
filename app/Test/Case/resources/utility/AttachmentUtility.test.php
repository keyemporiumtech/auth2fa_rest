<?php
App::uses("AttachmentUtility", "modules/resources/utility");
App::uses("AttachmentBS", "modules/resources/business");
App::uses("MimetypeBS", "modules/resources/business");

class AttachmentUtilityTest extends CakeTestCase {

	function testObjByPath() {
		$path= WWW_ROOT . "\img\logo.png";
		$pathAttachment= AttachmentUtility::getObjByPath($path);
		$this->assertEquals($pathAttachment ['Attachment'] ['name'], "logo");
		$this->assertEquals($pathAttachment ['Attachment'] ['ext'], "png");
	}

	function testObjByUrl() {
		$url= "https://www.google.com/images/branding/googlelogo/1x/googlelogo_color_272x92dp.png";
		$urlAttachment= AttachmentUtility::getObjByUrl($url);
		$this->assertEquals($urlAttachment ['Attachment'] ['name'], "googlelogo_color_272x92dp");
		$this->assertEquals($urlAttachment ['Attachment'] ['ext'], "png");
	}

	function testAttachmentMapping() {
		$bs= new AttachmentBS();
		$bs->addPropertyDao("avoidContent", true);
		$obj= $bs->unique(1);
		$this->assertEquals($obj ['Attachment'] ['cod'], "MYLOGO");
		$this->assertEquals($obj ['Attachment'] ['name'], "logo");
		$this->assertEquals($obj ['Attachment'] ['ext'], "png");
		$this->assertEquals($obj ['Attachment'] ['content'], null);
		
		$mimetypeBS= new MimetypeBS();
		$mimetypeBS->addCondition("ext", "png");
		$mimetype= $mimetypeBS->unique();
		
		$this->assertEquals($obj ['Attachment'] ['mimetype'], $mimetype ['Mimetype'] ['value']);
		$this->assertEquals($obj ['Attachment'] ['type'], $mimetype ['Mimetype'] ['type']);
	}
}