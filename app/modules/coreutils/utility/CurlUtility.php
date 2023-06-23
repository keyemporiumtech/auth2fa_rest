<?php
App::uses("CryptingUtility", "modules/crypting/utility");

class CurlUtility {

	static function fillOptionsForGet(&$arrayCurl, $url, $params= array(), $return= true) {
		$arrayCurl [CURLOPT_URL]= sprintf("%s?%s", $url, http_build_query($params));
		$arrayCurl [CURLOPT_CUSTOMREQUEST]= "GET";
		if ($return) {
			$arrayCurl [CURLOPT_RETURNTRANSFER]= 1;
		}
		// return $arrayCurl;
	}

	static function fillOptionsForPost(&$arrayCurl, $url, $params= array(), $return= true) {
		$arrayCurl [CURLOPT_URL]= $url;
		$arrayCurl [CURLOPT_POST]= 1;
		$arrayCurl [CURLOPT_POSTFIELDS]= $params;
		if ($return) {
			$arrayCurl [CURLOPT_RETURNTRANSFER]= 1;
		}
		// return $arrayCurl;
	}

	static function fillOptionsForPut(&$arrayCurl, $url, $params= array(), $return= true) {
		$arrayCurl [CURLOPT_URL]= $url;
		$arrayCurl [CURLOPT_CUSTOMREQUEST]= "PUT";
		$arrayCurl [CURLOPT_POSTFIELDS]= $params;
		if ($return) {
			$arrayCurl [CURLOPT_RETURNTRANSFER]= 1;
		}
		// return $arrayCurl;
	}

	static function fillOptionsForDelete(&$arrayCurl, $url, $params= array(), $return= true) {
		$arrayCurl [CURLOPT_URL]= $url;
		$arrayCurl [CURLOPT_CUSTOMREQUEST]= "DELETE";
		$arrayCurl [CURLOPT_POSTFIELDS]= $params;
		if ($return) {
			$arrayCurl [CURLOPT_RETURNTRANSFER]= 1;
		}
		// return $arrayCurl;
	}
	
	// UTILITY
	static function fillOptionsForBinary(&$arrayCurl) {
		// $arrayCurl [CURLOPT_HEADER]= 1;
		$arrayCurl [CURLOPT_BINARYTRANSFER]= 1;
		// return $arrayCurl;
	}

	static function fillOptionsHeaders(&$arrayCurl, $headers= array(), $tokenBearer= null) {
		if (! empty($token)) {
			array_push($headers, "Authorization: Bearer " . $token);
		}
		$arrayCurl [CURLOPT_HTTPHEADER]= $headers;
		// return $arrayCurl;
	}	

	static function fillOptionsAuthenticationBasic(&$arrayCurl, $userpwdJoin, $typeCrypt) {
		$arrayCurl [CURLOPT_HTTPAUTH]= CURLAUTH_BASIC;
		$arrayCurl [CURLOPT_USERPWD]= CryptingUtility::decryptByType($userpwdJoin, $typeCrypt);
		// return $arrayCurl;
	}

	static function fillOptionsAvoidSSL(&$arrayCurl) {
		$arrayCurl [CURLOPT_SSL_VERIFYPEER]= 0;
		$arrayCurl [CURLOPT_SSL_VERIFYHOST]= 0;
		// return $arrayCurl;
	}

	static function fillOptionsWithProxy(&$arrayCurl, $proxy, $port, $typeConnection, $userpwdJoin, $typeCrypt) {
		$arrayCurl [CURLOPT_PROXY]= $proxy;
		$arrayCurl [CURLOPT_PROXYPORT]= $port;
		$arrayCurl [CURLOPT_PROXYTYPE]= $typeConnection; // HTTP, HTTPS
		$arrayCurl [CURLOPT_PROXYUSERPWD]= CryptingUtility::decryptByType($userpwdJoin, $typeCrypt);
		// return $arrayCurl;
	}

	static function fillOptionsWithCookie(&$arrayCurl, $cookieString) {
		$arrayCurl [CURLOPT_COOKIE]= $cookieString;
		// return $arrayCurl;
	}

	static function createGET($url, $params= array(), &$arrayCurl= array()) {
		$ch= curl_init();
		CurlUtility::fillOptionsForGet($arrayCurl, $url, $params);
		curl_setopt_array($ch, $arrayCurl);
		return $ch;
	}

	static function createPOST($url, $params= array(), &$arrayCurl= array()) {
		$ch= curl_init();
		CurlUtility::fillOptionsForPost($arrayCurl, $url, $params);
		curl_setopt_array($ch, $arrayCurl);
		return $ch;
	}

	static function createPUT($url, $params= array(), &$arrayCurl= array()) {
		$ch= curl_init();
		CurlUtility::fillOptionsForPut($arrayCurl, $url, $params);
		curl_setopt_array($ch, $arrayCurl);
		return $ch;
	}

	static function createDELETE($url, $params= array(), &$arrayCurl= array()) {
		$ch= curl_init();
		CurlUtility::fillOptionsForDelete($arrayCurl, $url, $params);
		curl_setopt_array($ch, $arrayCurl);
		return $ch;
	}

	static function resolveBinary($curlResponse) {
		$file_array= explode("\n\r", $curlResponse, 2);
		// debug($file_array[0]);
		$header_array= explode("\n", $file_array [0]);
		$headers= array ();
		foreach ( $header_array as $header_value ) {
			$header_pieces= explode(':', $header_value);
			if (count($header_pieces) == 2) {
				$headers [$header_pieces [0]]= trim($header_pieces [1]);
			}
		}
		/*
		 * $result= array (
		 * "Content-type" => $headers ['Content-Type'],
		 * "Content-Disposition" => $headers ['Content-Disposition'],
		 * "file" => $file_array [1]
		 * );
		 */
		// header('Content-type: ' . $headers ['Content-Type']);
		// header('Content-Disposition: ' . $headers ['Content-Disposition']);
		// echo substr($file_array [1], 1);
		return $file_array [1];
	}

	static function execCurl($curl) {
		try {
			$result= curl_exec($curl);
			
			// Check the return value of curl_exec(), too
			if ($result == false) {
				throw new Exception(curl_error($curl), curl_errno($curl));
			}
			
			curl_close($curl);
			
			return $result;
		} catch ( Exception $e ) {
			throw $e;
		}
	}

	static function execCurlInfo($curl) {
		try {
			$result= curl_exec($curl);
			$info= curl_getinfo($curl);
			// Check the return value of curl_exec(), too
			if ($result == false) {
				throw new Exception(curl_error($curl), curl_errno($curl));
			}
			
			curl_close($curl);
			
			return array (
					"info" => $info,
					"response" => $result 
			);
		} catch ( Exception $e ) {
			throw $e;
		}
	}
}