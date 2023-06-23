<?php
App::uses("Codes", "Config/system");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("CurlUtility", "modules/coreutils/utility");

class RestSVH {

	static function convert($from, $to) {
		$url= "https://vwd-proxy.ilsole24ore.com/FinanzaMercati/api/CrossRate/CurrentRate";
		if (empty($from) || empty($to)) {
			return null;
		}
		try {
			$url.= "/{$from}/{$to}";
			$arrayCurl= array ();
			CurlUtility::fillOptionsAvoidSSL($arrayCurl);
			$arrayHeader= array (
					'Accept: application/json',
					'Content-type: application/json' 
			);
			CurlUtility::fillOptionsHeaders($arrayCurl, $arrayHeader);
			
			$ch= CurlUtility::createGet($url, array (), $arrayCurl);
			// debug($arrayCurl);
			$response= CurlUtility::execCurlInfo($ch);
			if (empty($response) || empty($response ['response'])) {
				$objInternal= MessageUtility::messageInternal("ERROR_CURL_CONVERT", "sole24ore", Codes::get("PLUGIN_ERROR"));
				MessageUtility::logMessageByObject($objInternal, "log_currency", "currency", MessageUtility::logSource("RestSVH", "convert"));
				return null;
			}
			$rate= json_decode($response ['response'], true);
			return $rate ['rate'];
		} catch ( Exception $e ) {
			$objInternal= MessageUtility::messageInternal("ERROR_CURL", "appgenericbs", Codes::get("PLUGIN_ERROR"), array (
					$url 
			));
			$objException= MessageUtility::messageExceptionByException($e);
			MessageUtility::logMessageByArrayObjects(array (
					$objInternal,
					$objException 
			), "log_currency", "currency", MessageUtility::logSource("RestSVH", "convert"));
			return null;
		}
	}
}