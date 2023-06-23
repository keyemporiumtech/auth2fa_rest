<?php
App::uses("AppModel", "Model");
App::uses("EnumTypeCrypt", "modules/crypting/config");

/**
 * Entity Clienttoken
 * 
 * @author Giuseppe Sassone
 *
 */
class Clienttoken extends AppModel {
	public $decrypts= array (
			'token' => EnumTypeCrypt::SHA256 
	);
	public $toCrypts= array (
			'token' => EnumTypeCrypt::SHA256 
	);
}
