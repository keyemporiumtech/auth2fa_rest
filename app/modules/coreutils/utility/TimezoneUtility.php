<?php
App::uses('Defaults', 'Config/system');

class TimezoneUtility {

	/**
	 * Aggiunge ad una data il valore in cifre del timezone impostato a sistema
	 * @param string $dtaString data a cui aggiungere il timezone
	 * @return string data con l'aggiunta del timezone in cifre impostato a sistema
	 */
	static function appendTimezone($dtaString) {
		$time= strtotime($dtaString);
		$newformat= date('Y-m-d H:i:s', $time);
		return $newformat . date('P');
	}

	/**
	 * Aggiunge ad una data il valore in cifre di un timezone specifico
	 * @param string $dtaString data a cui aggiungere il timezone
	 * @param string $timezone_name nome del timezone da aggiungere alla data
	 * @return string data con l'aggiunta del timezone in cifre specificato
	 */
	static function appendTimezoneSpecific($dtaString, $timezone_name) {
		$dateTime= new DateTime($dtaString, new DateTimeZone($timezone_name));
		return $dtaString . $dateTime->format('P');
	}

	/**
	 * Ritorna la data corrente con il timezone in cifre, quello specificato o quello di sistema se "timezone" è nullo
	 * @param string $timezone nome del timezone da aggiungere alla data corrente. Se non è specificato aggiunge quello di sistema (di default null)
	 * @return string data corrente con il timezone in cifre
	 */
	static function getCurrentDateTimezone($timezone= null) {
		$data= date('Y-m-d H:i:s');
		return ! empty($timezone) ? TimezoneUtility::appendTimezoneSpecific($data, $timezone) : TimezoneUtility::appendTimezone($data);
	}

	/**
	 * converte una data da un timezone di partenza "from_timezone" ad un timezone di arrivo "to_timezone"
	 * @param string $value data da convertire
	 * @param string $from_timezone nome del timezone di partenza della data
	 * @param string $to_timezone nome del timezone di destinazione della data
	 * @param string $format formato di conversione della data (default "Y-m-d H:i:s")
	 * @return string data nel formato $format in timezone $to_timezone
	 */
	static function convertDateTimezone($value, $from_timezone, $to_timezone, $format= 'Y-m-d H:i:s') {
		$dateTime= new DateTime($value, new DateTimeZone($from_timezone));
		$dateTime->setTimezone(new DateTimeZone($to_timezone));
		$value= $dateTime->format($format);
		return $value;
	}

	static function convertStringWithTimezoneInDate($value) {
		if (empty($value)) {
			return null;
		}
		$dateTimezoneHH= str_replace("T", " ", $value);
		return $dateTimezoneHH . ":00";
	}

	/**
	 * Converte una data da uno specifico "from_timezone" nel timezone di sistema
	 * @param string $value data da convertire
	 * @param string $from_timezone nome del timezone di partenza
	 * @param string $withP se è true indica che bisogna mostrare il timezone in cifre (di default "true")
	 * @param string $format formato di conversione della data (default "Y-m-d H:i:s")
	 * @return string data convertita da uno specifico "from_timezone" nel timezone di sistema
	 */
	static function convertDateTimezoneToServer($value, $from_timezone, $withP= true, $format= 'Y-m-d H:i:s') {
		$dateConvert= TimezoneUtility::convertDateTimezone($value, $from_timezone, Defaults::get("timezone"));
		return $withP ? TimezoneUtility::appendTimezoneSpecific($dateConvert, Defaults::get("timezone")) : $dateConvert;
	}

	/**
	 * Converte una data da uno specifico "from_timezone" nel timezone "to_timezone"
	 * @param string $value data da convertire
	 * @param string $from_timezone nome del timezone di partenza
	 * @param string $to_timezone nome del timezone di destinazione
	 * @param string se è true indica che bisogna mostrare il timezone in cifre (di default "true")
	 * @param string $format formato di conversione della data (default "Y-m-d H:i:s")
	 * @return string data convertita da uno specifico "from_timezone" nel timezone "to_timezone"
	 */
	static function convertDateTimezoneToTimezone($value, $from_timezone, $to_timezone, $withP= true, $format= 'Y-m-d H:i:s') {
		$dateConvert= TimezoneUtility::convertDateTimezone($value, $from_timezone, $to_timezone);
		return $withP ? TimezoneUtility::appendTimezoneSpecific($dateConvert, $to_timezone) : $dateConvert;
	}
}