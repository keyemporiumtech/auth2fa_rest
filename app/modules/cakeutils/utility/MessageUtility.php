<?php
App::uses("Enables", "Config/system");
App::uses("Codes", "Config/system");
App::uses("ArrayUtility", "modules/coreutils/utility");
App::uses("LogUtility", "modules/coreutils/utility");
App::uses("TranslatorUtility", "modules/cakeutils/utility");
App::uses("ObjCodMessage", "modules/cakeutils/classes");
App::uses("EnumResponseCode", "modules/cakeutils/config");

class MessageUtility {

	static function messageExceptionByException(Exception $exception, $type= null) {
		return new ObjCodMessage($exception->getCode(), $exception->getMessage(), $type, "EXCEPTION");
	}

	static function messageException($key, $file= null, $cod= null, $args= null, $type= null) {
		return new ObjCodMessage(empty($cod) ? Codes::get("EXCEPTION_GENERIC") : $cod, ArrayUtility::isEmpty($args) ? TranslatorUtility::__translate($key, $file) : TranslatorUtility::__translate_args($key, $args, $file), $type, "EXCEPTION");
	}

	static function messageInternal($key, $file= null, $cod= null, $args= null, $type= null) {
		return new ObjCodMessage(empty($cod) ? Codes::get("INTERNAL_GENERIC") : $cod, ArrayUtility::isEmpty($args) ? TranslatorUtility::__translate($key, $file) : TranslatorUtility::__translate_args($key, $args, $file), $type, "INTERNAL");
	}

	static function messageInfo($key, $file= null, $cod= null, $args= null, $type= null) {
		return new ObjCodMessage(empty($cod) ? Codes::get("INFO_GENERIC") : $cod, ArrayUtility::isEmpty($args) ? TranslatorUtility::__translate($key, $file) : TranslatorUtility::__translate_args($key, $args, $file), $type, "MESSAGE");
	}

	static function messageGeneric($cod, $key, $file, $args= null, $type= null, $category= null) {
		return new ObjCodMessage($cod, ArrayUtility::isEmpty($args) ? TranslatorUtility::__translate($key, $file) : TranslatorUtility::__translate_args($key, $args, $file), $type, $category);
	}

	static function logSource($class, $function) {
		return " [CLASS]: " . $class . " [FUNCTION]: " . $function;
	}

	/**
	 * Logga un messaggio basato su un ObjCodMessage di categoria INFORMATION 
	 * @param integer $cod codice del messaggio
	 * @param string $message messaggio da loggare
	 * @param string $flag nome della variabile enable
	 * @param string $log nome del file di log
	 * @param string $source informazione sulla sorgente (classe e funzione) del messaggio	
	 * @param integer $response_cod codice della response (di default 200) @see EnumResponseCode
	 */
	static function logMessage($cod, $message, $flag, $log, $source= null, $response_cod= null) {
		MessageUtility::logMessageByObject(new ObjCodMessage($cod, $message), $flag, $log, $source, $response_cod);
	}

	/**
	 * Logga un messaggio basato su un ObjCodMessage come input
	 * @param ObjCodMessage $obj oggetto contenente il codice e il messaggio
	 * @param string $flag nome della variabile enable
	 * @param string $log nome del file di log
	 * @param string $source informazione sulla sorgente (classe e funzione) del messaggio	
	 * @param integer $response_cod codice della response (di default 200) @see EnumResponseCode
	 */
	static function logMessageByObject(ObjCodMessage $obj, $flag, $log, $source= null, $response_cod= null) {
		if (Enables::get($flag)) {
			$message= (! empty($source) ? $source . " " : "") . "[" . $obj->printType() . "]\n";
			$message.= "[" . $obj->printCategory() . "]: (" . $obj->cod . ") " . $obj->message . "\n";
			$cod= ! empty($response_cod) ? $response_cod : EnumResponseCode::OK;
			LogUtility::write($log, $cod, $message);
		}
	}

	static function logMessageByMEI(ObjCodMessage $objMessage, ObjCodMessage $objException, ObjCodMessage $objInternal, $flag, $log, $source= null, $response_cod= null) {
		if (Enables::get($flag)) {
			$message= (! empty($source) ? $source . " " : "") . "[" . $objMessage->printType() . "]\n";
			$cod= ! empty($response_cod) ? $response_cod : EnumResponseCode::INTERNAL_SERVER_ERROR;
			if (! empty($objMessage)) {
				$message.= "[MESSAGE]: (" . $objMessage->cod . ") " . $objMessage->message . "\n";
			}
			if (! empty($objException)) {
				$message.= "[EXCEPTION]: (" . $objException->cod . ") " . $objException->message . "\n";
			}
			if (! empty($objInternal)) {
				$message.= "[INTERNAL]: (" . $objInternal->cod . ") " . $objInternal->message . "\n";
			}
			LogUtility::write($log, $cod, $message);
		}
	}

	static function logMessageByArrayObjects($objMessages= array(), $flag, $log, $source= null, $response_cod= null) {
		if (Enables::get($flag) && ! ArrayUtility::isEmpty($objMessages)) {
			$message= (! empty($source) ? $source . " " : "") . "[" . $objMessages [0]->printType() . "]\n";
			$cod= ! empty($response_cod) ? $response_cod : EnumResponseCode::INTERNAL_SERVER_ERROR;
			
			foreach ( $objMessages as $obj ) {
				$message.= "[" . $obj->printCategory() . "]: (" . $obj->cod . ") " . $obj->message . "\n";
			}
			LogUtility::write($log, $cod, $message);
		}
	}
}