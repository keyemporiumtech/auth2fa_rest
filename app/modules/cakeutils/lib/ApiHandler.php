<?php

class ApiHandler {

	public static function handleException(Exception $exception) {
		echo "handleException-ERROR_CODE: {$exception->getCode()} DESCRIPTION: {$exception->getMessage()} FILE: {$exception->getFile()} LINE: {$exception->getLine()}\n<br/>";
		throw ($exception);
	}

	public static function handleError($code, $description, $file= null, $line= null, $context= null) {
		echo "handleError-ERROR_CODE: {$code} DESCRIPTION: {$description} FILE: {$file} LINE: {$line}\n<br/>";
		throw (new Exception("Handle Error: {$description}", $code));
	}

	public static function handleFatalError($code, $description, $file, $line) {
		echo "handleFatalError-ERROR_CODE: {$code} DESCRIPTION: {$description} FILE: {$file} LINE: {$line}\n<br/>";
		throw (new Exception("Handle Fatal Error: {$description}", $code));
	}
}