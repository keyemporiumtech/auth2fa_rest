<?php
App::uses('AppController', 'Controller');
App::uses("CryptingUtility", "modules/crypting/utility");
App::uses("EnumTypeCrypt", "modules/crypting/config");

class CryptingController extends AppController {

	public function home() {
	}

	public function encrypt($value= null, $type= null) {
		if ($this->request->is('post')) {
			$value= $this->request->data ['value'];
			$type= $this->request->data ['type'];
		}
		if (! isset($value) || empty($value)) {
			$record= "Nessun parametro è stato fornito";
		} else if (! isset($type) || empty($type)) {
			$record= "Nessun tipo è stato selezionato";
		} else {
			switch ($type) {
				case EnumTypeCrypt::INNER :
					$record= CryptingUtility::encrypt($value);
					break;
				case EnumTypeCrypt::RIJNDAEL :
					$record= CryptingUtility::encrypt_rijndael($value);
					break;
				case EnumTypeCrypt::AES :
					$record= CryptingUtility::encrypt_aes($value);
					break;
				case EnumTypeCrypt::SHA256 :
					$record= CryptingUtility::encrypt_sha256($value);
					break;
				default :
					$record= "Il tipo di cryptaggio passato non rientra nelle tipologie possibili";
					break;
			}
		}
		$this->set("record", $record);
	}

	public function decrypt($value= null, $type= null) {
		if ($this->request->is('post')) {
			$value= $this->request->data ['value'];
			$type= $this->request->data ['type'];
		}
		if (! isset($value) || empty($value)) {
			$record= "Nessun parametro è stato fornito";
		} else if (! isset($type) || empty($type)) {
			$record= "Nessun tipo è stato selezionato";
		} else {
			switch ($type) {
				case EnumTypeCrypt::INNER :
					$record= CryptingUtility::decrypt($value);
					break;
				case EnumTypeCrypt::RIJNDAEL :
					$record= CryptingUtility::decrypt_rijndael($value);
					break;
				case EnumTypeCrypt::AES :
					$record= CryptingUtility::decrypt_aes($value);
					break;
				case EnumTypeCrypt::SHA256 :
					$record= CryptingUtility::decrypt_sha256($value);
					break;
				default :
					$record= "Il tipo di cryptaggio passato non rientra nelle tipologie possibili";
					break;
			}
		}
		$this->set("record", $record);
	}
}