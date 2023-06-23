<?php
App::uses("Codes", "Config/system");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("CurlUtility", "modules/coreutils/utility");

class RestBI {

	static function latest() {
		$url= "https://tassidicambio.bancaditalia.it/terzevalute-wf-web/rest/v1.0/latestRates";
		try {
			
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
				$objInternal= MessageUtility::messageInternal("ERROR_CURL_LATEST", "bancaditalia", Codes::get("PLUGIN_ERROR"));
				MessageUtility::logMessageByObject($objInternal, "log_currency", "currency", MessageUtility::logSource("RestBI", "latest"));
				return null;
			}
			return $response ['response'];
		} catch ( Exception $e ) {
			$objInternal= MessageUtility::messageInternal("ERROR_CURL", "appgenericbs", Codes::get("PLUGIN_ERROR"), array (
					$url 
			));
			$objException= MessageUtility::messageExceptionByException($e);
			MessageUtility::logMessageByArrayObjects(array (
					$objInternal,
					$objException 
			), "log_currency", "currency", MessageUtility::logSource("RestBI", "latest"));
			return null;
		}
	}
}