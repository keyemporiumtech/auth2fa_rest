<?php
App::uses("StringUtility", "modules/coreutils/utility");

/**
 * Utility per le date
 * d - The day of the month (from 01 to 31)
 * D - A textual representation of a day (three letters)
 * j - The day of the month without leading zeros (1 to 31)
 * l (lowercase 'L') - A full textual representation of a day
 * N - The ISO-8601 numeric representation of a day (1 for Monday, 7 for Sunday)
 * S - The English ordinal suffix for the day of the month (2 characters st, nd, rd or th. Works well with j)
 * w - A numeric representation of the day (0 for Sunday, 6 for Saturday)
 * z - The day of the year (from 0 through 365)
 * W - The ISO-8601 week number of year (weeks starting on Monday)
 * F - A full textual representation of a month (January through December)
 * m - A numeric representation of a month (from 01 to 12)
 * M - A short textual representation of a month (three letters)
 * n - A numeric representation of a month, without leading zeros (1 to 12)
 * t - The number of days in the given month
 * L - Whether it's a leap year (1 if it is a leap year, 0 otherwise)
 * o - The ISO-8601 year number
 * Y - A four digit representation of a year
 * y - A two digit representation of a year
 * a - Lowercase am or pm
 * A - Uppercase AM or PM
 * B - Swatch Internet time (000 to 999)
 * g - 12-hour format of an hour (1 to 12)
 * G - 24-hour format of an hour (0 to 23)
 * h - 12-hour format of an hour (01 to 12)
 * H - 24-hour format of an hour (00 to 23)
 * i - Minutes with leading zeros (00 to 59)
 * s - Seconds, with leading zeros (00 to 59)
 * u - Microseconds (added in PHP 5.2.2)
 * e - The timezone identifier (Examples: UTC, GMT, Atlantic/Azores)
 * I (capital i) - Whether the date is in daylights savings time (1 if Daylight Savings Time, 0 otherwise)
 * O - Difference to Greenwich time (GMT) in hours (Example: +0100)
 * P - Difference to Greenwich time (GMT) in hours:minutes (added in PHP 5.1.3)
 * T - Timezone abbreviations (Examples: EST, MDT)
 * Z - Timezone offset in seconds. The offset for timezones west of UTC is negative (-43200 to 50400)
 * c - The ISO-8601 date (e.g. 2013-05-05T16:34:42+00:00)
 * r - The RFC 2822 formatted date (e.g. Fri, 12 Apr 2013 12:01:05 +0200)
 * U - The seconds since the Unix Epoch (January 1 1970 00:00:00 GMT)
 * and the following predefined constants can also be used (available since PHP 5.1.0):
 *
 * DATE_ATOM - Atom (example: 2013-04-12T15:52:01+00:00)
 * DATE_COOKIE - HTTP Cookies (example: Friday, 12-Apr-13 15:52:01 UTC)
 * DATE_ISO8601 - ISO-8601 (example: 2013-04-12T15:52:01+0000)
 * DATE_RFC822 - RFC 822 (example: Fri, 12 Apr 13 15:52:01 +0000)
 * DATE_RFC850 - RFC 850 (example: Friday, 12-Apr-13 15:52:01 UTC)
 * DATE_RFC1036 - RFC 1036 (example: Fri, 12 Apr 13 15:52:01 +0000)
 * DATE_RFC1123 - RFC 1123 (example: Fri, 12 Apr 2013 15:52:01 +0000)
 * DATE_RFC2822 - RFC 2822 (Fri, 12 Apr 2013 15:52:01 +0000)
 * DATE_RFC3339 - Same as DATE_ATOM (since PHP 5.1.3)
 * DATE_RSS - RSS (Fri, 12 Aug 2013 15:52:01 +0000)
 * DATE_W3C - World Wide Web Consortium (example: 2013-04-12T15:52:01+00:00)
 *
 * @author Giuseppe Sassone
 */
class DateUtility {
    static $type_date_single = array(
        'd' => 'day',
        'm' => 'month',
        'Y' => 'year',
        'H' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    static $type_date_multi = array(
        'd' => 'days',
        'm' => 'months',
        'Y' => 'years',
        'H' => 'hours',
        'i' => 'minutes',
        's' => 'seconds',
    );

    //------------------------OPERATIONS
    /**
     * Ritorna la data odierna in formato  "Y-m-d H:i:s"
     * @return string data odierna in formato  "Y-m-d H:i:s"
     */
    public static function getCurrentTime() {
        return date("Y-m-d H:i:s");
    }

    /**
     * Ritorna la data odierna in formato  "d/m/Y H:i:s"
     * @return string data odierna in formato  "d/m/Y H:i:s"
     */
    public static function getCurrentDate() {
        return date("d/m/Y H:i:s");
    }

    /**
     * Ritorna una data in formato "d/m/Y" da una data in formato "Y-m-d ..."
     * @param string $timestamp data in formato "Y-m-d ..."
     * @return string date una data in formato "d/m/Y"
     */
    public static function getDateByTimestamp($timestamp) {
        $aa = substr($timestamp, 0, 4);
        $mm = substr($timestamp, 5, 2);
        $dd = substr($timestamp, 8, 2);
        return $dd . "/" . $mm . "/" . $aa;
    }

    /**
     * Ritorna una data in formato "Y-m-d" da una data in formato "d/m/Y ..."
     * @param string $dateHH data in formato "d/m/Y ..."
     * @return string date una data in formato "Y-m-d"
     */
    public static function getTimestampByDate($dateHH) {
        $giorno = substr($dateHH, 0, 2);
        $mese = substr($dateHH, 3, 2);
        $anno = substr($dateHH, 6, 4);
        $date_convert = $anno . "-" . $mese . "-" . $giorno;
        return $date_convert;
    }

    /**
     * Ritorna una data in formato "d/m/Y H:i" da una data in formato "Y-m-d H:i"
     * @param string $timestampHH data in formato "Y-m-d H:i"
     * @return string date una data in formato "d/m/Y H:i"
     */
    public static function getDateByTimestampHH($timestampHH) {
        $anno = substr($timestampHH, 0, 4);
        $mese = substr($timestampHH, 5, 2);
        $giorno = substr($timestampHH, 8, 2);
        $ore = substr($timestampHH, 11, 2);
        $minuti = substr($timestampHH, 14, 2);
        $date_convert = $giorno . "/" . $mese . "/" . $anno . " " . $ore . ":" . $minuti;
        return $date_convert;
    }

    /**
     * Ritorna una data in formato "Y-m-d H:i" da una data in formato "d/m/Y H:i"
     * @param string $dateHH data in formato "d/m/Y H:i"
     * @return string data in formato "Y-m-d H:i"
     */
    public static function getTimestampByDateHH($dateHH) {
        $giorno = substr($dateHH, 0, 2);
        $mese = substr($dateHH, 3, 2);
        $anno = substr($dateHH, 6, 4);
        $ore = substr($dateHH, 11, 2);
        $minuti = substr($dateHH, 14, 2);
        $date_convert = $anno . "-" . $mese . "-" . $giorno . " " . $ore . ":" . $minuti;
        return $date_convert;
    }

    /**
     * Ritorna una data in formato "Ymd" da una data in formato "Y-m-d ..."
     * @param string $timestamp data in formato "Y-m-d ..."
     * @return string date una data in formato "Ymd"
     */
    public static function getDateCalendar($timestamp) {
        $aa = substr($timestamp, 0, 4);
        $mm = substr($timestamp, 5, 2);
        $dd = substr($timestamp, 8, 2);
        return $aa . "" . $mm . "" . $dd;
    }

    /**
     * Ritorna l'ora e i minuti (formato "H.i") da una data in formato "Y-m-d ..." o "d/m/Y ..."
     * @param string $timestamp data in formato "Y-m-d ..." o "d/m/Y ..."
     * @return string l'ora e i minuti (formato "H.i")
     */
    public static function getTimeCalendar($timestamp) {
        $hh = substr($timestamp, 11, 2);
        $min = substr($timestamp, 14, 2);
        return $hh . "." . $min;
    }

    /**
     * Ritorna una data in uno specifico formato
     * @param string $format formato della data
     * @param string $date data da convertire
     * @return string una data in uno specifico formato
     */
    public static function getDateFormat($format, $date) {
        if (empty($format)) {
            $format = "Y-m-d H:i:s";
        }
        if (StringUtility::contains($date, "/")) {
            if (StringUtility::contains($date, ":")) {
                $date = DateUtility::getTimestampByDateHH($date);
            }
            $date = DateUtility::getTimestampByDate($date);
        }
        if (DateUtility::isEmptyTimestamp($date)) {
            return "";
        }
        return date($format, strtotime($date));
    }

    /**
     * Ritorna la data odierna in uno specifico formato
     * @param string $format formato della data odierna
     * @return string la data odierna in uno specifico formato
     */
    public static function getCurrentDateFormat($format) {
        return date($format, strtotime(date("Y-m-d H:i:s")));
    }

    /**
     * Ritorna true se una data è vuota o contiene "1970-01-01"
     * @param string $timestamp data da valutare in formato "Y-m-d ..."
     * @return boolean true se una "timestamp" è vuota o contiene "1970-01-01"
     */
    public static function isEmptyTimestamp($timestamp) {
        if (empty($timestamp) || StringUtility::contains($timestamp, "1970-01-01")) {
            return true;
        }
        return false;
    }

    /**
     * verifica se una stringa è una data in formato timestamp ("Y-m-d ...")
     * @param string $string data da valutare
     * @return boolean true se è in formato timestamp ("Y-m-d ...")
     */
    public static function isTimestamp($string = "") {
        return (bool) strtotime($string);
    }

    /**
     * Ritorna il numero di giorni contenuti in un mese "month" di uno specifico anno "year"
     * @param int $month mese da valutare
     * @param int $year anno da valutare
     * @return int il numero di giorni contenuti in un mese "month" di uno specifico anno "year"
     */
    public static function getNumggMese($month, $year) {
        switch ((int) $month) {
        case 1:
        case 3:
        case 5:
        case 7:
        case 8:
        case 10:
        case 12:
            return 31;
            break;
        case 2:
            if (DateUtility::getAnnoBisestile($year)) {
                return 29;
            } else {
                return 28;
            }
        case 4:
        case 6:
        case 9:
        case 11:
            return 30;
            break;
        }
    }

    /**
     * Ritorna true se uno specifico anno "year" è bisestile
     * @param int $year anno da valutare
     * @return boolean true se uno specifico anno "year" è bisestile
     */
    public static function getAnnoBisestile($year) {
        if (($year % 4 == 0 && $year % 100 != 0) || $year % 400 == 0) {
            return true;
        }
        return false;
    }

    //------------------------COMPARE
    /**
     * Aggiunge o rimuove giorni, mesi, anni, ore, minuti o secondi da una data e ne ritorna il valore stringa se "format" è definito, il long time altrimenti
     * @param string $dateHH data in input
     * @param int $num numero da aggiungere o rimuovere
     * @param string $sign segno "+" per aggiungere e "-" per rimuovere
     * @param string $type_date codice che indicata cosa aggiungere o rimuovere (d,m,Y,H,i,s)
     * @return string|int data con l'aggiunta o la rimozione di giorni, mesi, anni, ore, minuti o secondi
     */
    public static function addToDate($dateHH, $num, $sign, $type_date, $format = null) {
        if ($num > 1) {
            $toadd = $sign . "" . $num . " " . DateUtility::$type_date_single[$type_date];
        } else {
            $toadd = $sign . "" . $num . " " . DateUtility::$type_date_multi[$type_date];
        }

        $date_convert = strtotime($toadd, strtotime($dateHH));
        if (!empty($format)) {
            $dta = $date_convert;
            $date_convert = date($format, $dta);
        }
        return $date_convert;
    }

    /**
     * Ritorna la differenza di giorni, mesi, anni, ore o minuti tra due date
     * @param string $dtainit data iniziale
     * @param string $dtaend data finale
     * @param string $type_date codice che indicata cosa calcolare (d,m,Y,H,i,s)
     * @return number difference number differenza di giorni, mesi, anni, ore o minuti tra "dtainit" e "dtaend"
     */
    public static function diffDate($dtainit, $dtaend, $type_date) {
        $data1 = strtotime($dtainit);
        $data2 = strtotime($dtaend);

        if ($type_date == "d" || $type_date == "Y" || $type_date == "H" || $type_date == "i" || $type_date == "s") {
            switch ($type_date) {
            case "s":
                $unit = 1 / 3600;
                break; // SECONDI
            case "i":
                $unit = 1 / 60;
                break; // MINUTI
            case "H":
                $unit = 1;
                break; // ORE
            case "d":
                $unit = 24;
                break; // GIORNI
            case "Y":
                $unit = 8760;
                break; // ANNI
            }
            $differenza = floor((($data2 - $data1) / 3600) / $unit);
        } elseif ($type_date == "m") {
            $data1 = date('Y-m-d H:i', strtotime($dtainit));
            $data2 = date('Y-m-d H:i', strtotime($dtaend));
            $unit = (365 / 12);
            $anno_init = substr($data1, 0, 4);
            $mese_init = substr($data1, 5, 2);
            $giorno_init = substr($data1, 8, 2);

            $anno_end = substr($data2, 0, 4);
            $mese_end = substr($data2, 5, 2);
            $giorno_end = substr($data2, 8, 2);

            $date_diff = mktime(12, 0, 0, $mese_end, $giorno_end, $anno_end) - mktime(12, 0, 0, $mese_init, $giorno_init, $anno_init);
            $differenza = floor(($date_diff / 60 / 60 / 24) / $unit);
        } else {
            $differenza = 0;
        }
        return $differenza;
    }

    /**
     * Ritorna true se data finale maggiore di data iniziale
     * @param string $dtainit data iniziale
     * @param string $dtaend data finale
     * @return boolean true se data finale maggiore di data iniziale
     */
    public static function endMax($dtainit, $dtaend) {
        $data1 = strtotime($dtainit);
        $data2 = strtotime($dtaend);
        if ($data2 > $data1) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * Ritorna true se una data è compresa tra una data iniziale e una data finale
     * @param string $dta data da confrontare
     * @param string $dtainit data iniziale
     * @param string $dtaend data finale
     * @return boolean true se una data è compresa tra una data iniziale "dtainit" e una data finale "dtaend"
     */
    public static function beetwenDates($dta, $dtainit, $dtaend) {
        $data1 = strtotime($dtainit);
        $data2 = strtotime($dtaend);
        $dta1 = strtotime($dta);
        if ($dta1 >= $data1 && $dta1 <= $data2) {
            return true;
        } else {
            return false;
        }

    }
}
