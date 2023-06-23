<?php

class Cores {

	static function readJson($path= null, $obj= false) {
		if (empty($path)) {
			return null;
		}
		$jsonFile= file_get_contents($path);
		if (! $jsonFile) {
			return null;
		}
		if ($obj) {
			return json_decode($jsonFile);
		}
		return json_decode($jsonFile, true);
	}

	static function isEmpty($val) {
		if (empty($val) || ! $val) {
			return true;
		} elseif (is_array($val) && count($val) == 0) {
			return true;
		}
		return false;
	}
}