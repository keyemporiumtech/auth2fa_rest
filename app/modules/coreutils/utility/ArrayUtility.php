<?php
App::uses("StringUtility", "modules/coreutils/utility");

/**
 * Utility che gestisce gli array
 *
 * @author Giuseppe Sassone
 */
class ArrayUtility {

    //------------------------OPERATIONS
    /**
     * Rimuove da un array bidimensionale [class][field] la chiave "field" che ha uno specifico valore "value"
     * @param array $list array da valutare
     * @param string $class prima chiave dell'array
     * @param unknown $value valore da ricercare nella seconda chiave "field"
     * @param string $field seconda chiave dell'array (di default "id")
     */
    public static function removeObjectByField(&$list, $class, $value, $field = "id") {
        foreach ($list as $key => $el) {
            if ($el[$class][$field] == $value) {
                unset($list[$key]);
            }
        }
    }

    /**
     * Dato un array bidimensionale [class][field] costruisce un array contenente i valori di una specifica chiave dell'array bidimensionale
     * @param array $list array bidimensionale
     * @param string $class prima chiave dell'array
     * @param string $field seconda chiave dell'array (di default "id")
     * @return array array contenente i valori di una specifica chiave dell'array bidimensionale
     */
    public static function getArrayIdByObject($list, $class, $field = "id") {
        $result = array();
        foreach ($list as $el) {
            array_push($result, $el[$class][$field]);
        }
        return $result;
    }

    /**
     * Dato un array chiave-valore ne ritorna un altro con valore-chiave
     * @param array $enum array chiave-valore
     * @return array array valore-chiave
     */
    static function invertKeyValue($enum = array()) {
        $result = array();
        foreach ($enum as $key => $val) {
            $result[$val] = $key;
        }
        return $result;
    }

    /**
     * Ritorna la chiave di un array per uno specifico valore cercato
     * @param array $array array da analizzare
     * @param unknown $value valore da cercare
     * @return unknown chiave dell'array che ha per valore "value"
     */
    static function getKeyByValue($array = array(), $value) {
        foreach ($array as $key => $val) {
            if ($val == $value) {
                return $key;
            }
        }
        return null;
    }

    /**
     * Controlla se un array è vuoto o nullo
     * @param array $array array da controllare
     * @return boolean true se l'array ha almeno un elemento, false altrimenti
     */
    static function isEmpty($array = array()) {
        if (!empty($array) && count($array) > 0) {
            return false;
        }
        return true;
    }

    /**
     * Restituisce true se ogni elemento di un array è anch'esso un array
     * @param array $a array da controllare
     * @return boolean true se ogni elemento di un array è anch'esso un array
     */
    static function isMulti($a) {
        foreach ($a as $v) {
            if (!is_array($v)) {
                return false;
            }

        }
        return true;
    }

    /**
     * Sostituisce la chiave di un array con una nuova chiave
     * @param array $array array
     * @param string $old_key vecchia chiave
     * @param string $new_key nuova chiave
     */
    static function replaceKey(&$array, $old_key, $new_key) {
        $array[$new_key] = $array[$old_key];
        unset($array[$old_key]);
        return;
    }

    //------------------------SORTING
    /**
     * Ordina un array per uno specifico campo e ritorna l'array ordinato
     * @param array $array array da ordinare
     * @param string $key campo da ordinare
     * @param boolean $reverse se è true ordina in maniera discendente
     * @return array l'array ordinato
     */
    static function sortArrayByKey($array, $key, $reverse = false) {
        $sortArray = array();
        foreach ($array as $el) {
            foreach ($el as $key => $value) {
                if (!isset($sortArray[$key])) {
                    $sortArray[$key] = array();
                }
                $sortArray[$key][] = $value;
            }
        }
        array_multisort($sortArray[$key], $reverse ? SORT_DESC : SORT_ASC, $array);
        return $array;
    }

    /**
     * Ordina un array di oggetti per uno specifico campo e ritorna l'array ordinato
     * @param array $array array da ordinare
     * @param string $propName campo da ordinare
     * @param boolean $reverse se è true ordina in maniera discendente
     * @return array l'array ordinato
     */
    static function sortObjectsByField($array, $propName, $reverse = false) {
        $sorted = [];
        foreach ($array as $item) {
            $sorted[$item->$propName][] = $item;
        }
        if ($reverse) {
            krsort($sorted);
        } else {
            ksort($sorted);
        }

        $result = [];
        foreach ($sorted as $subArray) {
            foreach ($subArray as $item) {
                $result[] = $item;
            }
        }

        return $result;
    }

    /**
     * Ordina un array di oggetti dello stesso tipo ("class") utilizzando funzioni di ordinamento
     * ascendente e discendente dichiarate nella definizione di classe
     * @param array $array array da ordinare
     * @param string $class nome della classe per identificare il tipo di oggetti dell'array
     * @param string $methodAsc metodo ascendente per la comparazione degli oggetti di una classe
     * @param string $methodDesc metodo discendente per la comparazione degli oggetti di una classe
     * @param boolean $asc se è false ordina in maniera discendente
     * @return array array ordinato di oggetti
     */
    static function sortObjects($array, $class, $methodAsc, $methodDesc, $asc = true) {
        if ($asc) {
            if (!empty($methodAsc)) {
                usort($array, array(
                    $class,
                    $methodAsc,
                ));
            }

        } else {
            if (!empty($methodDesc)) {
                usort($array, array(
                    $class,
                    $methodDesc,
                ));
            }

        }
        return $array;
    }

    public static function partition($list, $p) {
        $listlen = count($list);
        $partlen = floor($listlen / $p);
        $partrem = $listlen % $p;
        $partition = array();
        $mark = 0;
        for ($px = 0; $px < $p; $px++) {
            $incr = ($px < $partrem) ? $partlen + 1 : $partlen;
            $partition[$px] = array_slice($list, $mark, $incr);
            $mark += $incr;
        }
        return $partition;
    }

    public static function partitionOrder($list, $p) {
        $listlen = count($list);
        $partition = array();
        $i = 0;
        while ($i < $p) {
            $partition[$i] = array();
            $i++;
        }
        $i = 0;
        $j = 0;
        foreach ($list as $el):
            if ($i < $p) {
                $partition[$i][$j] = $el;
                $i++;
            } else {
                $i = 0;
                $j++;
                $partition[$i][$j] = $el;
            }
        endforeach
        ;
        return $partition;
    }

    //------------------------COMPARE
    /**
     * Confronta due array semplici e ritorna:
     * -1 se il primo array ha meno elementi del secondo o ha elementi con valori minori del secondo
     * 1 se il primo array ha più elementi del secondo o ha elementi con valori maggiori del secondo
     * null se i due array non sono confrontabili, ovvero di stessa dimensione ma con chiavi diverse
     * 0 se i due array sono uguali sia in dimensione che in valore
     * @param array $op1 primo array
     * @param array $op2 secondo array
     * @return number|NULL intero(-1, 0, 1) o null in caso di array inconfrontabili
     */
    static function compareArray($op1, $op2) {
        if (count($op1) < count($op2)) {
            return -1; // $op1 < $op2
        } elseif (count($op1) > count($op2)) {
            return 1; // $op1 > $op2
        }
        foreach ($op1 as $key => $val) {
            if (!array_key_exists($key, $op2)) {
                return null; // uncomparable
            } elseif ($val < $op2[$key]) {
                return -1;
            } elseif ($val > $op2[$key]) {
                return 1;
            }
        }
        return 0; // $op1 == $op2
    }

    /**
     * Confronta due array bidimensionali o semplici e ritorna:
     * -1 se il primo array ha meno elementi del secondo
     * 1 se il primo array ha più elementi del secondo
     * null se i due array non sono confrontabili, ovvero uno dei due non contiene una chiave "field"
     * 0 se i due array sono uguali in dimensione
     * @param array $op1 primo array
     * @param array $op2 secondo array
     * @param string $class prima chiave degli array bidimensionale (di default null)
     * @param string $field seconda chiave degli array (di default "id")
     * @return number|NULL intero(-1, 0, 1) o null in caso di array inconfrontabili
     */
    static function compareArrayKey($op1, $op2, $class = null, $field = "id") {
        if (!ArrayUtility::containsArrayKey($op1, $class, $field) || !ArrayUtility::containsArrayKey($op2, $class, $field)) {
            return null;
        }

        if (count($op1) < count($op2)) {
            return -1; // $op1 < $op2
        } elseif (count($op1) > count($op2)) {
            return 1; // $op1 > $op2
        }
        return 0; // $op1 == $op2
    }

    /**
     * Confronta un array di valori "op1" con un array bidimensionale e ritorna:
     * -1 se il primo array ha meno elementi del secondo
     * 1 se il primo array ha più elementi del secondo
     * null se i due array non sono confrontabili, ovvero "op2" non contiene una chiave "field"
     * 0 se i due array sono uguali in dimensione
     * @param array $op1 primo array di valori
     * @param array $op2 secondo array bidimensionale
     * @param string $class prima chiave dell'array bidimensionale (di default null)
     * @param string $field seconda chiave dell'array bidimensionale (di default "id")
     * @return number|NULL intero(-1, 0, 1) o null in caso di array inconfrontabili
     */
    static function compareArrayKeyByListValue($op1, $op2, $class = null, $field = "id") {
        if (!ArrayUtility::containsArrayKey($op2, $class, $field)) {
            return null;
        }
        if (count($op1) < count($op2)) {
            return -1; // $op1 < $op2
        } elseif (count($op1) > count($op2)) {
            return 1; // $op1 > $op2
        }
        return 0; // $op1 == $op2
    }

    //------------------------CONTAINS
    /**
     * Controlla che una array bidimensionale [class][field] o un array semplice [field] contenga la chiave "field" in tutti i suoi elementi
     * @param array $list array da controllare
     * @param string $class prima chiave dell'array
     * @param string $field seconda chiave dell'array (di default "id")
     */
    public static function containsArrayKey($list, $class = null, $field = "id") {
        if (!empty($list) && count($list) > 0) {
            if (!empty($class)) {
                foreach ($list as $el) {
                    if (is_array($el) && array_key_exists($class, $el) && is_array($el[$class]) && array_key_exists($field, $el[$class])) {
                        continue;
                    } else {
                        return false;
                    }
                }
            } else {
                foreach ($list as $el) {
                    if (is_array($el) && array_key_exists($field, $el) && !empty($el[$field])) {
                        continue;
                    } else {
                        return false;
                    }
                }
            }
        }
        return true;
    }

    /**
     * Controlla che una array bidimensionale [class][field] o semplice [field] contenga il valore "value" di uno specifica chiave "field"
     * @param array $list array da controllare
     * @param unknown $value valore da ricercare nella seconda chiave "field"
     * @param string $class prima chiave dell'array bidimensionale (di default null)
     * @param string $field seconda chiave dell'array (di default "id")
     */
    public static function containsArrayKeyByValue($list, $value, $class = null, $field = "id") {
        if (!empty($list) && count($list) > 0) {
            if (!empty($class)) {
                foreach ($list as $el) {
                    if (is_array($el) && array_key_exists($class, $el) && is_array($el[$class]) && array_key_exists($field, $el[$class]) && $el[$class][$field] == $value) {
                        return true;
                    }
                }
            } else {
                foreach ($list as $el) {
                    if (is_array($el) && array_key_exists($field, $el) && !empty($el[$field]) && $el[$field] == $value) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    /**
     * Controlla che un array di oggetti contenga uno specifico campo "property"
     * @param array $list array da controllare
     * @param unknown $value valore da ricercare
     * @param string $property campo dell'array (di default "id")
     */
    public static function containsObjectField($list, $property = "id") {
        if (!empty($list) && count($list) > 0) {
            foreach ($list as $el) {
                if (is_object($el) && property_exists($el, $property) && !empty($el->$property)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Controlla che un array di oggetti contenga il valore "value" di uno specifico campo "property"
     * @param array $list array da controllare
     * @param unknown $value valore da ricercare
     * @param string $property campo dell'array (di default "id")
     */
    public static function containsObjectFieldByValue($list, $value, $property = "id") {
        if (!empty($list) && count($list) > 0) {
            foreach ($list as $el) {
                if (is_object($el) && property_exists($el, $property) && !empty($el->$property) && $el->$property == $value) {
                    return true;
                }
            }
        }
        return false;
    }
    public static function getObjectByFieldAndValue($list, $value, $property = "id") {
        if (!empty($list) && count($list) > 0) {
            foreach ($list as $el) {
                if (is_object($el) && property_exists($el, $property) && !empty($el->$property) && $el->$property == $value) {
                    return $el;
                }
            }
        }
        return null;
    }
    public static function getObjectIndexByFieldAndValue($list, $value, $property = "id") {
        if (!empty($list) && count($list) > 0) {
            $i = 0;
            foreach ($list as $el) {
                if (is_object($el) && property_exists($el, $property) && !empty($el->$property) && $el->$property == $value) {
                    return $i;
                }
                $i++;
            }
        }
        return null;
    }

    /**
     * Controlla se un array ($key=>$value) contiene il valore "value" passato
     * @param array $list array da controllare
     * @param unknown $value valore da ricercare
     * @return boolean true se il valore è stato trovato, false altrimenti
     */
    public static function contains($list, $value) {
        if (!empty($list) && count($list) > 0) {
            foreach ($list as $key => $val) {
                if ($val == $value) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Controlla che la lista $list($key=>$value) contenga un valore con sottostringa "value".
     * In tal caso lo ritorna
     * @param array $list array di suffissi
     * @param unknown $value valore da analizzare
     * @return unknow il suffisso contenuto in "value"
     */
    public static function getContainsSuffix($list, $value) {
        if (!empty($list) && count($list) > 0) {
            foreach ($list as $key => $val) {
                if (StringUtility::contains($val, $value)) {
                    return $val;
                }
            }
        }
        return null;
    }

    /**
     * Controlla che la lista $list($key=>$value) contenga una chiave con sottostringa "value".
     * In tal caso ne ritorna il valore della chiave trovata
     * @param array $list array di suffissi in chiave
     * @param unknown $value valore da analizzare
     * @return unknow il valore della chiave suffisso trovata nell'array
     */
    public static function getContainsSuffixKey($list, $value) {
        if (!empty($list) && count($list) > 0) {
            foreach ($list as $key => $val) {
                if (StringUtility::contains($key, $value)) {
                    return $val;
                }
            }
        }
        return null;
    }

    //------------------------STRING
    /**
     * Restituisce una stringa contente gli elementi della lista separati da "split", ed eventualmente aggiunge un delimitatore tra gli elementi (es *1*,*2* ...)
     * @param array $list array di valori
     * @param boolean $startZero se true la stringa deve cominciare con 0
     * @param string $split separatore di valori (di default ",")
     * @param string $delimiter il delimiter viene usato per quei valori che potrebbero trarre in inganno nella ricerca, esempio ,2 e ,20 .. se si cerca ,2 si beccano entrambi.
     * Il delimiter fa si che si cerchi ,*2* che è diverso da ,*20*
     * @return string una stringa dei valori dell'array separati da virgola
     */
    public static function getStringByList($list, $startZero = false, $split = ",", $delimiter = null) {
        $result = $startZero ? "0" . $split : "";
        $i = 0;
        if (!empty($list) && count($list) > 0) {
            foreach ($list as $el):
                if ($i > 0) {
                    $result .= $split;
                }
                if (!empty($delimiter)) {
                    $result .= $delimiter . $el . $delimiter;
                } else {
                    $result .= $el;
                }
                $i++;
            endforeach
            ;
        }
        return $result;
    }

    /**
     * Restituisce una stringa con primo elemento 0 e con i valori di un array bidimensionale [class][field] o semplice separati da "split"
     * @param array $list array bidimensionale
     * @param string $class prima chiave dell'array bidimensionale (di default null)
     * @param string $field seconda chiave dell'array (di default "id")
     * @param boolean $startZero se true la stringa deve cominciare con 0
     * @param string $split separatore di valori (di default ",")
     * @param string $delimiter il delimiter viene usato per quei valori che potrebbero trarre in inganno nella ricerca, esempio ,2 e ,20 .. se si cerca ,2 si beccano entrambi.
     * Il delimiter fa si che si cerchi ,*2* che è diverso da ,*20*
     * @return string una stringa con primo elemento 0 e con i valori di un array bidimensionale [class][field] separati da virgola
     */
    public static function getStringIdByArray($list, $class = null, $field = null, $startZero = false, $split = ",", $delimiter = null) {
        $result = $startZero ? "0" . $split : "";
        $i = 0;
        foreach ($list as $el) {
            if ($i > 0) {
                $result .= $split;
            }
            if (!empty($delimiter)) {
                $result .= $delimiter;
            }
            if (empty($field)) {
                $result .= !empty($class) ? $el[$class]["id"] : $el["id"];
            } else {
                $result .= !empty($class) ? $el[$class][$field] : $el[$field];
            }
            if (!empty($delimiter)) {
                $result .= $delimiter;
            }
            $i++;
        }
        return $result;
    }

    /**
     * Trasforma una stringa con separatore "split" in array e se è presente un "delimiter" tra i campi lo elimina
     * @param string $string stringa contenente valori separati da virgola
     * @param string $split separatore di valori (di default ",")
     * @param string $delimiter eventuale delemiter di valori da pulire
     * @return array array dei valori esplosi dalla stringa
     */
    public static function splitIdString($string, $split = ",", $delimiter = null) {
        $array = explode($split, $string);
        if (!empty($delimiter)) {
            $new_array = array();
            $i = 0;
            foreach ($array as $value) {
                $new_array[$i] = str_replace($delimiter, "", $value);
                $i++;
            }
            return $new_array;
        }
        return $array;
    }

    /**
     * Ritorna un'unica stringa contenente tutti i valori di un array racchiusi in parentesi quadre "[]"
     * @param array $list array da analizzare
     * @param boolean $flgkey se true stampa anche la chiave
     * @return string stringa contenente tutti i valori di un array racchiusi in parentesi quadre "[]"
     */
    static function toPrintString($list, $flgkey = true) {
        $result = "";
        foreach ($list as $key => $val) {
            if (is_array($val)) {
                $val = ArrayUtility::toPrintString($val);
            }
            if ($flgkey) {
                $result .= "[" . $key . " = " . $val . "] ";
            } else {
                $result .= "[" . $val . "] ";
            }
        }
        return $result;
    }

    /**
     * Ritorna una stringa con tutti i valori di un array racchiusi in parentesi quadre "[]" e disposti uno sotto all'altro (utilizza \n new line)
     * @param array $list array da analizzare
     * @param boolean $flgkey se true stampa anche la chiave
     * @return string stringa con tutti i valori di un array racchiusi in parentesi quadre "[]" e disposti uno sotto all'altro (utilizza \n new line)
     */
    static function toPrintStringNewLine($list, $flgkey = true) {
        $result = "";
        foreach ($list as $key => $val) {
            if (is_array($val)) {
                $val = ArrayUtility::toPrintStringNewLine($val, $flgkey);
            }
            if ($flgkey) {
                $result .= "[" . $key . " = " . $val . "]\n ";
            } else {
                $result .= "[" . $val . "]\n ";
            }
        }
        return $result;
    }

    /**
     * Ritorna una stringa con tutti i valori di un array racchiusi in parentesi quadre "[]" e disposti uno sotto all'altro (utilizza br new line)
     * @param array $list array da analizzare
     * @param boolean $flgkey se true stampa anche la chiave
     * @return string stringa con tutti i valori di un array racchiusi in parentesi quadre "[]" e disposti uno sotto all'altro (utilizza br new line)
     */
    static function toPrintStringNewLineHtml($list, $flgkey = true) {
        $result = "";
        foreach ($list as $key => $val) {
            if (is_array($val)) {
                $val = ArrayUtility::toPrintStringNewLineHtml($val, $flgkey);
            }
            if ($flgkey) {
                $result .= "[" . $key . " = " . $val . "]<br/> ";
            } else {
                $result .= "[" . $val . "]<br/> ";
            }
        }
        return $result;
    }
}
