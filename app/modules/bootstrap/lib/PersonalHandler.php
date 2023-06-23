<?php

class PersonalHandler {

	public static function handleException(Exception $exception) {
		echo 'Ho beccato una eccezione ' . $exception->getCode() . " " . $exception->getMessage();
	}

	public static function handleError($code, $description, $file= null, $line= null, $context= null) {
		echo 'Ho beccato un errore : <br/>';
		echo 'CODICE : ' . $code . ' <br/>';
		echo 'DESCRIZIONE : ' . $description . ' <br/>';
		echo 'FILE : ' . $file . ' <br/>';
		echo 'LINE : ' . $line . ' <br/>';
	}

	public static function handleFatalError($code, $description, $file, $line) {
		echo 'Ho beccato un errore fatale : <br/>';
		echo 'CODICE : ' . $code . ' <br/>';
		echo 'DESCRIZIONE : ' . $description . ' <br/>';
		echo 'FILE : ' . $file . ' <br/>';
		echo 'LINE : ' . $line . ' <br/>';
	}
}