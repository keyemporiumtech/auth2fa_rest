<?php

/**
 * Utility che gestisce le stringhe
 *
 * @author Giuseppe Sassone
 */
class StringUtility {

    //------------------------OPERATIONS
    /**
     * Rimuove da una stringa i caratteri unicode (ex. \u0000)
     * @param string $string stringa da pulire
     * @return string stringa ripulita dei caratteri unicode
     */
    public static function cleanUnicode($string) {
        return preg_replace('/[^\PC\s]/u', '', $string);
    }

    /**
     * Aggiunge un numero di caratteri "0" prima della stringa ne ritorna il valore
     * @param string $string stringa da riempire con "0"
     * @param string $max numeri di "0" da aggiungere
     * @return string stringa riempita con numero "max" di "0" prima del suo valore
     */
    public static function getFillZeroByString($string, $max) {
        return StringUtility::getFillByString($string, $max, "0");
    }

    /**
     * Aggiunge un numero di caratteri "fillerChar" prima della stringa ne ritorna il valore
     * @param string $string stringa da riempire con "fillerChar"
     * @param string $max numeri di "fillerChar" da aggiungere
     * @param string $fillerChar carattere di riempimento
     * @return string stringa riempita con numero "max" di "fillerChar" prima del suo valore
     */
    public static function getFillByString($string, $max, $fillerChar) {
        $filler = "";
        for ($i = 0; $i < $max; $i++) {
            $filler .= $fillerChar;
        }
        return $filler . $string;
    }

    /**
     * Ritorna una sottostringa concatenata con "separator"
     * @param string $string stringa da modificare
     * @param int $lenght numeri di caratteri da includere
     * @param string $separator separatore della sottostringa (di default i tre puntini sospensivi "...")
     * @return string sottostringa di "string" concatenata con "separator"
     */
    public static function shortString($string, $lenght, $separator = "...") {
        if (strlen(strip_tags($string)) > $lenght) {
            return substr(strip_tags($string), 0, $lenght) . $separator;
        }
        return strip_tags($string);
    }

    /**
     * Rimuove dalla stringa "string" la parte finale a partire dalla parola "word" cercata
     * @param string $stringa stringa da pulire
     * @param string $word parola da cercare
     * @param boolean $includeWord se true include la parola "word" nella stringa ripulita (di default false)
     * @return string stringa ripulita della parte parte finale a partire dalla parola "word" cercata
     */
    public static function cleanFromCharToEnd($stringa, $word, $includeWord = false) {
        /*
         * $cleaned= $stringa;
         * $pos= strpos($stringa, $word);
         * if ($pos && ! empty($pos)) {
         * $cleaned= substr($stringa, 0, $includeWord ? $pos + strlen($word) : $pos);
         * }
         * return $cleaned;
         */
        $result = $stringa;
        $pos = strrpos($stringa, $word); //ultima posizione del carattere trovato
        if ($pos != FALSE) {
            $todelete = substr($stringa, $includeWord ? $pos + strlen($word) : $pos);
            $result = str_replace($todelete, "", $stringa);
        }
        return $result;
    }

    /**
     * Rimuove dalla stringa "string" la parte iniziale fino alla parola "word" cercata
     * @param string $stringa stringa da pulire
     * @param string $word parola da cercare
     * @param boolean $includeWord se true include la parola "word" nella stringa ripulita (di default false)
     * @return string stringa ripulita della parte iniziale
     */
    public static function cleanFromInitToWord($stringa, $word, $includeWord = false) {
        $result = $stringa;
        $pos = strpos($stringa, $word);
        if ($pos && !empty($pos)) {
            $todelete = substr($stringa, 0, $includeWord ? $pos + strlen($word) : $pos);
            $result = str_replace($todelete, "", $stringa);
        }
        return $result;
    }

    /**
     * Pulisce una stringa dagli spazi
     * @param string $string stringa da pulire
     * @return string stringa ripulita dagli spazi
     */
    public static function trim($string) {
        return preg_replace('/\s+/', '', $string);
    }

    /**
     * Ritorna una lettera randomica
     * @return string lettera randomina
     */
    public static function getRandLetter() {
        $int = rand(0, 51);
        $a_z = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $rand_letter = $a_z[$int];
        return $rand_letter;
    }

    /**
     * Ritorna un carattere numerico randomico
     * @return string carattere numerico randomico
     */
    public static function getRandNumber() {
        $int = rand(0, 9);
        $a_z = "0123456789";
        $rand_letter = $a_z[$int];
        return $rand_letter;
    }

    //------------------------CONTAINS
    /**
     * Ritorna true se una stringa contiene una sottostringa "value"
     * @param string $string stringa da valutare
     * @param string $value sottostringa da cercare
     * @return boolean true se contiene una sottostringa "value"
     */
    public static function contains($string, $value) {
        if (!empty($string) && !empty($value) && strpos($string, $value) !== false) {
            return true;
        }
        return false;
    }

    /**
     * Ritorna true se una stringa contiene almeno uno dei valori di un array
     * @param string $string stringa da controllare
     * @param array $values array di sottostringhe da verificare
     * @return boolean true se una stringa contiene almeno uno dei valori di un array
     */
    public static function containsAll($string, $values) {
        if (!empty($string)) {
            foreach ($values as $value) {
                if (!empty($value) && strpos($string, $value) !== false) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Data una stringa di valori separati da "split", ritorna true se è contenuto il valore "value"
     * @param string $string stringa di valori
     * @param string $value valore da cercare
     * @param string $split separatore di valori (di default ",")
     * @param string $delimiter il delimiter viene usato per quei valori che potrebbero trarre in inganno nella ricerca, esempio ,2 e ,20 .. se si cerca ,2 si beccano entrambi.
     * Il delimiter fa si che si cerchi ,*2* che è diverso da ,*20*
     * @return boolean true se è contenuto il valore "value"
     */
    public static function containsArrayByString($string, $value, $split = ",", $delimiter = null) {
        $stringConv = $string;
        if (!empty($delimiter)) {
            $stringConv = str_replace($delimiter, "", $string);
        }
        $array = explode($split, $stringConv);
        if (in_array($value, $array)) {
            return true;
        }
        return false;
    }

    /**
     * Ritona true se una stringa è contenuta in un array di stringhe.
     * @param string $string stringa da cercare
     * @param array $array array di stringhe
     * @return boolean true se "string" è contenuta in "array".
     */
    public static function stringInArray($string, $array) {
        foreach ($array as $word) {
            if (StringUtility::contains($string, $word)) {
                return true;
            }
        }
        return false;
    }

    //------------------------ARRAY
    /**
     * Ritorna un'array contenente pezzi di una stringa divisa in base alla lunghezza "lenght" richiesta per ogni pezzo di stringa
     * @param string $stringa stringa da splittare
     * @param int $lenght numero di caratteri di ogni pezzo di stringa
     * @param string $separatore eventuale separatore che effettua una prima scrematura (di default nessuno) in più stringhe
     * @return array array contenente pezzi di una stringa divisa in base alla lunghezza "lenght" richiesta per ogni pezzo di stringa
     */
    public static function splitText($stringa, $lenght, $separatore = "") {
        $ret = array();
        //se la stringa non è vuota ed è più lunga di $lenght, la divido
        if ($stringa != "" and strlen($stringa) > $lenght) {

            $stringa2 = "";
            if (!empty($separatore)) {
                //la divido in base agli accapo (separatori) già presenti, poi prendo ogni frammento e, se più lungo di $lenght, li divido in pezzi contenenti $lenght caratteri
                $array = explode($separatore, $stringa);
            } else {
                $array = array();
                array_push($array, $stringa);
            }
            //prendo ogni pezzo e lo divido in base al totale di caratteri per riga
            foreach ($array as $value) {
                if (strlen($value) > $lenght) {
                    $inizio = 0;
                    while ($inizio < strlen($value)) {
                        //estraggo i primi tot caratteri dall'inizio
                        $parte1 = substr($value, $inizio, $lenght);

                        //controllo nel caso in cui si è all'ultima parte di stringa (altrimenti viene troncata)
                        if ($inizio > strlen($value) - $lenght) {
                            $stringa2 = $parte1 . $separatore;
                            array_push($ret, $stringa2);
                            break;
                        }

                        //torno al carattere prima dello spazio
                        $parte1 = substr($parte1, 0, strrpos($parte1, ' '));
                        //aggiorno il puntatore per ripartire dalla stringa (il +1 serve per eliminare lo spazio quando va a capo)
                        $inizio += strlen($parte1) + 1;
                        //salvo la parte tagliata
                        $stringa2 = $parte1 . $separatore;
                        array_push($ret, $stringa2);
                    }
                } else {
                    $stringa2 = $value . $separatore;
                    array_push($ret, $stringa2);
                }
            }
        } else {
            array_push($ret, $stringa);
        }
        return $ret;
    }

    public static function splitTextForced($stringa, $lenght, $separatore = "", $debug = false) {
        $ret = array();
        $array = array();
        if (!empty($separatore)) {
            //la divido in base agli accapo (separatori) già presenti, poi prendo ogni frammento e, se più lungo di $lenght, li divido in pezzi contenenti $lenght caratteri
            $array = explode($separatore, $stringa);
        } else {
            $array = array();
            array_push($array, $stringa);
        }
        if ($debug) {
            debug($array);
        }
        $i = 0;
        foreach ($array as &$part) {
            if ($debug) {
                debug("INIT $i=$part");
            }
            $entered = false;
            while (strlen($part) > $lenght) {
                $entered = true;
                if ($debug) {
                    debug(strlen($part) . " maggiore di $lenght");
                }
                $inizio = 0;
                $part1 = substr($part, $inizio, $lenght);
                if ($debug) {
                    debug("part1=$part1");
                }
                $ultimo_carattere = substr($part1, strlen($part1) - 1, 1);
                $penultimo_carattere = substr($part1, strlen($part1) - 2, 1);
                $successivo_carattere = substr($part, strlen($part1) + 1, 1);
                if ($debug) {
                    debug("successivo=$successivo_carattere");
                }
                if ($debug) {
                    debug($ultimo_carattere);
                }
                if ($ultimo_carattere != $separatore && $ultimo_carattere != " " && $penultimo_carattere != " " && $successivo_carattere != " ") {
                    $part1 .= "-";
                    array_push($ret, $part1);
                } elseif ($penultimo_carattere == " ") {
                    if ($debug) {
                        debug("part1=$part1");
                    }
                    $part1 = str_replace($ultimo_carattere, "", $part1);
                    if ($debug) {
                        debug("part1 penultimo carattere bianco=$part1");
                    }
                    array_push($ret, $part1);
                } else {
                    if ($debug) {
                        debug("part1=$part1");
                    }
                    array_push($ret, $part1);
                }
                $inizio += strlen($part1) - 1;
                $part = substr($part, $inizio, strlen($part));
                if ($debug) {
                    debug("part=$part");
                }
            }
            if (!empty($array[$i + 1]) && $entered) {
                $array[$i + 1] = $part . " " . $array[$i + 1];
            } else {
                array_push($ret, $part);
            }
            $i++;
        }
        return $ret;
    }

    /**
     * Restituisce un array contenente pezzi di stringa di lunghezza "lenght", eventualmente troncando stringhe a metà.
     * @param string $stringa stringa da valutare
     * @param int $lenght numero di caratteri di ogni pezzo di stringa
     * @param string $separatore eventuale separatore che effettua una prima scrematura (di default "*#*") in più stringhe
     * @param boolean $truncate se true tronca le stringhe a metà
     * @param boolean $debug se true stampa in debug l'elaborazione
     * @return array array contenente pezzi di stringa di lunghezza "lenght", eventualmente troncando stringhe a metà.
     */
    public static function splitTextWithWordWrap($stringa, $lenght, $separatore = "", $truncate = true, $debug = false) {
        if (empty($separatore)) {
            $separatore = "*#*";
        }
        $new_string = wordwrap($stringa, $lenght, $separatore, $truncate);
        return explode($separatore, $new_string);
    }

    /**
     * Restituisce un array troncando per un insieme di separatori
     * @param string $stringa stringa da valutare
     * @param array $delimiters array dei separatori
     * @return array array contenente pezzi di stringa separati dai separatori $delimiters.
     */
    public static function multiexplode($string, $delimiters = array()) {

        $ready = str_replace($delimiters, $delimiters[0], $string);
        $launch = explode($delimiters[0], $ready);
        return $launch;
    }

    //------------------------HTML
    /**
     * Data una stringa contenente il tag html href, ritorna l'url di href
     * @param string $string stringa contenente un tag href di link html
     * @return string url del tag href
     */
    public static function getHref($string) {
        $url = preg_match('/href=["\']?([^"\'>]+)["\']?/', $string, $match);
        if (!empty($match[1])) {
            return $match[1];
        }
        return "";
    }

    /**
     * Data una stringa contenente il tag html href, sostituisce l'url di href con il valore $replace
     * @param string $string stringa contenente un tag href di link html
     * @param string $replace valore da sostiture alla url del tag href
     * @return string stringa contenente il tag html href con la url rimpiazzata da "replace"
     */
    public static function replaceHref($string, $replace = "") {
        $pattern = "/(?<=href=(\"|'))[^\"']+(?=(\"|'))/";
        return preg_replace($pattern, $replace, $string);
    }

    //------------------------JSON
    /**
     * Ritorna un oggetto di tipo json da una stringa in formato json
     * @param string $string stringa in formato json
     * @param boolean $singleQuotesContext se true indica che la stringa json è formata di singlequote (')(di default true)
     * @param boolean $doubleQuotesContext se true indica che la stringa json è formata di doublequote (")(di default false)
     * @param boolean $addQuotes se true formatta il json con doublequote (")(di default false)
     * @return mixed oggetto json da una stringa formato json
     */
    public static function formatJavaScript($string, $singleQuotesContext = true, $doubleQuotesContext = false, $addQuotes = false) {

        // Encode as standard JSON, double quotes
        $string = json_encode($string);

        // Remove " from start and end"
        $string = mb_substr($string, 1, -1);

        // If using single quotes, reaplce " with ' and escape
        if ($doubleQuotesContext == true) {
            // Escape double quote
            $string = str_replace('"', '\"', $string);
        }
        if ($singleQuotesContext == true) {
            // Escape single quotes
            $string = str_replace("'", "\'", $string);
        }

        if ($addQuotes == true) {

            if ($doubleQuotesContext == true) {
                $string = '"' . $string . '"';
            } else {
                $string = "'" . $string . "'";
            }
        }

        return $string;
    }
}
