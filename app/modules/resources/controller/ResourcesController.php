<?php
App::uses('AppController', 'Controller');
App::uses("AttachmentUtility", "modules/resources/utility");
App::uses("AttachmentUI", "modules/resources/delegate");

class ResourcesController extends AppController {

	public function home() {
		$url= "https://www.google.com/images/branding/googlelogo/1x/googlelogo_color_272x92dp.png";
		$path= WWW_ROOT . "\img\logo.png";
		
		$this->set("url", $url);
		$urlAttachment= AttachmentUtility::getObjByUrl($url);
		$this->set("urlAttachment", $urlAttachment);
		
		$this->set("path", $path);
		$pathAttachment= AttachmentUtility::getObjByPath($path);
		$this->set("pathAttachment", $pathAttachment);
		
		$ui= new AttachmentUI();
		$this->set("attachment", $ui->get(1));
	}
}