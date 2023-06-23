<?php
App::uses("StringUtility", "modules/coreutils/utility");

/**
 * Utility per le operazioni matematiche
 *
 * @author Giuseppe Sassone
 */
class MathUtility {

	/**
	 * Ritorna la percentuale di un valore "value" contenuta nel totale "total"
	 * @param float $value valore contenuto in "total"
	 * @param float $total massimo valore consentito
	 * @return float la percentuale di un valore "value" contenuta nel valore massimo "total"
	 */
	static function getPercent($value, $total) {
		if (empty($value) || empty($total) || $value == 0.00 || $total == 0.00) {
			return 0;
		} else {
			$percent= ($value * 100) / $total;
			return round($percent, 2);
		}
	}

	/**
	 * Ritorna il valore percentuale di un totale (esempio : percent=20 , total=100 -> ritorna il valore che assume il 20% di 100 )
	 * @param float $percent percentuale contenuta in "total"
	 * @param float $total massimo valore consentito
	 * @return float il valore del "percent" contenuto nel valore massimo "total"
	 */
	static function getPercentValue($percent, $total) {
		if (empty($percent) || empty($total) || $percent == 0.00 || $total == 0.00) {
			return 0;
		} else {
			$value= ($percent * $total) / 100;
			return round($value, 2);
		}
	}

	/**
	 * Ritorna il valore percentuale sommato ad 1 (es. 20% = 1,20)
	 * @param float $value valore percentuale
	 * @return float  il valore percentuale sommato ad 1 (es. 20% = 1,20)
	 */
	static function getUnitPercentValue($value) {
		if (empty($value) || $value == 0.00) {
			return 0;
		} else {
			$percent= ($value) / 100;
			return round(($percent + 1), 2);
		}
	}
	
	/**
	 * Ritorna il valore percentuale levando l'unità 1 (es. 1,20 = 20% )
	 * @param float $value valore interno
	 * @return float il valore percentuale su percentuale unitaria (es. 1,20 = 20%)
	 */
	static function getPercentByUnitValue($value) {
		if (empty($value) || $value == 0.00) {
			return 0;
		} else {
			$percent= ($value - 1) * 100;
			return round($percent, 2);
		}
	}

	/**
	 * Ritorna il valore scorporato dalla percentuale (esempio: percent=10, total=110 -> ritorna 110 meno 10%, ovvero 100)
	 * @param float $value percentuale da rimuovere
	 * @param float $total totale su cui rimuovere la percentuale
	 * @return float il valore scorporato dalla percentuale (esempio: percent=10, total=110 -> ritorna 110 meno 10%, ovvero 100)
	 */
	static function removePercent($percent, $total) {
		if (empty($percent) || empty($total) || $percent == 0.00 || $total == 0.00) {
			return 0;
		} else {
			$value= (100 * $total) / ($percent + 100);
			return round($value, 2);
		}
	}

	/**
	 * Ritorna la percentuale esistente tra due valori (esempio: first = 20, total = 100 -> in percentuale first risulta il 20% di total )
	 * @param float $first valore parte del totale        	
	 * @param float $total totale su cui calcolare la perentuale di "first"        	
	 * @return float percentuale di "first" contenuta in "total"
	 */
	static function getPercentualBetween($first, $total) {
		if (empty($first) || empty($total)) {
			return 0;
		} else {
			$percent= ($first * 100) / $total;
			return round($percent, 2);
		}
	}

	/**
	 * Ritorna un double da una stringa numerica
	 * @param string $numeric stringa indicante un valore decimale
	 * @return float double di "numeric"
	 */
	static function getDoubleByString($numeric) {
		$ret= $numeric;
		if (StringUtility::contains($numeric, ","))
			$ret= str_replace(",", ".", $numeric);
		return (double) $ret;
	}

	/**
	 * Ritorna una stringa da un valore double
	 * @param float $double valore double
	 * @param float $decimal numero di decimali da ritornare (di default "2")
	 * @return string stringa del valore "double"
	 */
	static function getStringByDouble($double, $decimal= 2) {
		if (empty($double)) {
			return "0.00";
		}
		return number_format($double, $decimal, '.', '');
	}

	/**
	 * Dato un numero "number" ritorna un array contenente il valore arrotondato del numero e la frazione rimanente dopo l'arrotondamento
	 * @param float $number numero da troncare
	 * @param boolean $returnUnsigned se true bisogna includere il segno "-" per valori negativi, altrimenti è in valore assoluto (di default true)
	 * @return float[] array contenente il valore arrotondato del numero e la frazione rimanente dopo l'arrotondamento
	 */
	static function getNumberFractions($number, $returnUnsigned= false) {
		$negative= 1;
		if ($number < 0) {
			$negative= - 1;
			$number*= - 1;
		}
		
		if ($returnUnsigned) {
			return array (
					floor($number),
					($number - floor($number)) 
			);
		}
		
		return array (
				floor($number) * $negative,
				($number - floor($number)) * $negative 
		);
	}

	/**
	 * Restituisce true se il valore passato non è un valore decimale o è 0
	 * @param float|string $val valore da analizzare
	 * @return boolean true se il valore passato non è un valore decimale o è 0
	 */
	static function isEmptyDecimal($val) {
		if (empty($val) || MathUtility::getStringByDouble($val) == "0.00") {
			return true;
		}
		return false;
	}

	/**
	 * Restituisce true se il valore passato non è un valore intero o è 0
	 * @param float|string $val valore da analizzare
	 * @return boolean true se il valore passato non è un valore intero o è 0
	 */
	static function isEmptyNumber($val) {
		if (empty($val) || "" . $val == "0") {
			return true;
		}
		return false;
	}

	/**
	 * Restituisce la parte intera o decimale di un double
	 * @param float|string $val valore da analizzare
	 * @param float $type 1=parte intera, 2=parte decimale
	 * @return la parte intera o decimale di un double
	 */
	static function getPartOfDecimal($value, $type= 1) {
		$val= MathUtility::getStringByDouble($value);
		$arr= array ();
		if (StringUtility::contains($val, ".")) {
			$arr= explode(".", $val);
		} elseif (StringUtility::contains($val, ",")) {
			$arr= explode(",", $val);
		}
		if (! ArrayUtility::isEmpty($arr)) {
			if ($type == 1) {
				return $arr [0];
			} elseif ($type == 2) {
				return $arr [1];
			}
		}
		return null;
	}

	/**
	 * Restituisce true se il valore passato ha una cifra decimale minore o uguale a 30, che è il numero massimo di giorni rappresentati da un mese
	 * @param float $val valore da analizzare
	 * @return true se "val" ha una cifra decimale minore o uguale a 30, che è il numero massimo di giorni rappresentati da un mese
	 */
	static function isMonthsDecimal($val) {
		$decimal= MathUtility::getPartOfDecimal($val, 2);
		if (! empty($decimal) && $decimal <= 30) {
			return true;
		}
		return false;
	}

	/**
	 * Ritorna la media di un array di numeri
	 * @param float[] $list array di numeri
	 * @param boolean $flgempty se true indica che bisogna calcolare nella media anche i valori nulli (di default false)
	 * @return float la media di un array di numeri
	 */
	static function getAverageString($list= array(), $flgempty= false) {
		$average= 0.0;
		if (count($list) > 0) {
			$sum= 0.0;
			$cnt= 0;
			foreach ( $list as $num ) {
				$el= MathUtility::getDoubleByString($num);
				if (! empty($el) || ($flgempty && empty($el))) {
					$sum+= $el;
					$cnt ++;
				}
			}
			if ($cnt != 0) {
				$average= ($sum / $cnt);
			}
		}
		return MathUtility::getStringByDouble($average);
	}

	/**
	 * Converte un valore numero 0|1 in boolean, con false in caso di valore diverso da 0 e 1
	 * @param int $intero valore da convertire
	 * @return boolean un valore numero 0|1 in boolean, con false in caso di valore diverso da 0 e 1
	 */
	static function getBoolean($intero) {
		if (empty($intero) || $intero == "0") {
			return false;
		} elseif ($intero == 1 || $intero == "1") {
			return true;
		} else {
			return false;
		}
	}
}
