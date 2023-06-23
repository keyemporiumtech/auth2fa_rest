<?php
App::uses('AppController', 'Controller');
App::uses("QrcodeUtility", "modules/util_printcodes/utility");
App::uses("BarcodeUtility", "modules/util_printcodes/utility");

class UtilprintcodesController extends AppController {

	public function home() {
	}

	public function viewQrcode($text= false) {
		parent::evalParam($text, "text");
		$this->set("text", $text);
		$attachment= QrcodeUtility::getAttachmentByQrcode($text, "Test_Qrcode");
		$this->set("attachment", $attachment);
	}

	public function viewBarcode($text= false) {
		parent::evalParam($text, "text");
		$this->set("text", $text);
		$attachment= BarcodeUtility::getAttachmentByBarcode($text, "Test_Qrcode");
		$this->set("attachment", $attachment);
	}
}