<?php
App::uses('ArrayUtility', 'modules/coreutils/utility');
App::uses('CurlUtility', 'modules/coreutils/utility');

class RestUtility {
	
	// Method: POST, PUT, GET etc
	// Data: array("param" => "value") ==> index.php?param=value
	/**
	 * Chiama un'api e ne ritorna il valore
	 *  
	 * @param string $method Metodo della richiesta  <code>GET,POST,PUT,DELETE</code>
	 * @param string $url url da invocare
	 * @param string $data parametri in body o params
	 * @param string $headers array di opzioni header <code>('Content-Type:text/xml', 'apiKey:token' ...)</code> 
	 * @return string|mixed risultato della chiamata
	 */
	static function CallAPI($method, $url, $data= false, $headers= null) {
		try {
			$arrayCurl= array ();
			CurlUtility::fillOptionsAvoidSSL($arrayCurl);
			if (! ArrayUtility::isEmpty($headers)) {
				CurlUtility::fillOptionsHeaders($arrayCurl, $headers);
			}
			switch ($method) {
				case 'POST' :
					$curl= CurlUtility::createPOST($url, $data, $arrayCurl);
					break;
				case 'GET' :
					$curl= CurlUtility::createGet($url, $data, $arrayCurl);
					break;
				case 'PUT' :
					$curl= CurlUtility::createPUT($url, $data, $arrayCurl);
					break;
				case 'DELETE' :
					$curl= CurlUtility::createDELETE($url, $data, $arrayCurl);
					break;
				default :
					throw new Exception("METHOD NOT FOUND", 404);
			}
			
			$result= CurlUtility::execCurl($curl);
			// Check the return value of curl_exec(), too
			if ($result == false) {
				throw new Exception(curl_error($curl), curl_errno($curl));
			}
			
			return $result;
		} catch ( Exception $e ) {
			throw $e;
		}
	}
}