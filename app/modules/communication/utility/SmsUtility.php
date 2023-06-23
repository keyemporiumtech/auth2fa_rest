<?php
class SmsUtility {

	static function evalNumberWithPlus($number= null) {
		try {
			if (empty($number)) {
				throw new Exception("NUMBER IS NULL");
			}
			$result= strval($number);
			if (substr($result, 0, 1) != "+") {
				$result= "+" . $result;
			}
			return $result;
		} catch ( Exception $e ) {
			throw $e;
		}
	}
}
