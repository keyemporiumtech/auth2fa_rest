<?php
App::uses("CoreutilsConfig", "modules/coreutils/config");
App::uses('FileUtility', 'modules/coreutils/utility');
App::uses('StringUtility', 'modules/coreutils/utility');
App::uses('ArrayUtility', 'modules/coreutils/utility');
App::uses('CryptingUtility', 'modules/crypting/utility');

/**
 * Utility per la gestione delle informazioni View
 *
 * @author Giuseppe Sassone
 */
class PageUtility {

    //------------------------ENCODING
    /**
     * converte un array di parametri in una stringa separata da un carattere separatore "separator"
     * @param array $params array di parametri stringa
     * @param string $separator carattere separatore di stringa (di default ",")
     * @return string stringa separata da un carattere separatore
     */
    public static function encodeParametersPath($params = array(), $separator = ",") {
        if (empty($params) || ArrayUtility::isEmpty($params)) {
            return "";
        } elseif (count($params) == 1) {
            return $params[0];
        }
        $string = "";
        $cnt = 1;
        foreach ($params as $param) {
            if ($cnt < count($params)) {
                $string .= $param . $separator;
            } else {
                $string .= $param;
            }
            $cnt++;
        }
        return $string;
    }

    /**
     * converte un array di parametri in una stringa chiave-valore separata da un carattere separatore "separator"
     * @param array $params array di parametri stringa
     * @param string $separator carattere separatore di stringa (di default ",")
     * @return string stringa chiave-valore separata da un carattere separatore
     */
    public static function encodeParametersPathWithKeyValue($params = array(), $separator = ",") {
        if (empty($params) || ArrayUtility::isEmpty($params)) {
            return "";
        } elseif (count($params) == 1) {
            foreach ($params as $key => $param) {
                return $key . "=" . $param;
            }
        }
        $string = "";
        $cnt = 1;
        foreach ($params as $key => $param) {
            if ($cnt < count($params)) {
                $string .= $key . "=" . $param . $separator;
            } else {
                $string .= $key . "=" . $param;
            }
            $cnt++;
        }
        return $string;
    }

    /**
     * converte una stringa di parametri separati da un carattere separatore "separator" in un array di parametri
     * @param string $string stringa di parametri separati da "separator"
     * @param string $separator carattere separatore di stringa (di default ",")
     * @return array array di parametri da stringa
     */
    public static function decodeParametersPath($string, $separator = ",") {
        if (empty($string)) {
            $back_parameter = array();
        } elseif (StringUtility::contains($string, $separator)) {
            $back_parameter = explode($separator, $string);
        } else {
            $back_parameter = array(
                $string,
            );
        }
        return $back_parameter;
    }

    /**
     * converte una stringa di parametri chiave-valore separati da un carattere separatore "separator" in un array di parametri
     * @param string $string stringa di parametri chiave-valore separati da "separator"
     * @param string $separator carattere separatore di stringa (di default ",")
     * @return array array di parametri da stringa
     */
    public static function decodeParametersPathWithKeyValue($string, $separator = ",") {
        if (empty($string)) {
            $back_parameters = array();
        } elseif (StringUtility::contains($string, $separator)) {
            $back_parameters = explode($separator, $string);
        } else {
            $back_parameters = array(
                $string,
            );
        }
        $result = array();
        if (!ArrayUtility::isEmpty($back_parameters)) {
            foreach ($back_parameters as $keyValue) {
                $keyValueExplode = explode("=", $keyValue);
                if (count($keyValueExplode) == 2) {
                    $result[$keyValueExplode[0]] = $keyValueExplode[1];
                }
            }
        }
        return $result;
    }

    /**
     * converte una stringa url sostituendo slash e back slash con il carattere separatore "separator"
     * @param string $url stringa url da convertire
     * @param string $separator separatore al posto dello slash o back slash (di default "-")
     * @return string url con "separator" al posto di slash e back slash
     */
    public static function encodeUrlPath($url, $separator = "-") {
        if (!empty($url)) {
            $first = str_replace(CoreutilsConfig::$SLASH, $separator, $url);
            return str_replace(CoreutilsConfig::$BACK_SLASH, $separator, $first);
        }
        return "";
    }

    /**
     * converte una stringa sostituendo il carattere separatore "separator" con slash o back slash
     * @param string $url url separata da "separator" al posto di slash o back slash
     * @param string $separator separatore al posto dello slash o back slash (di default "-")
     * @param boolean $backSlash se true indica che la url va separata con back slash, altrimenti slash (di default "false")
     * @return string url da stringa separata con slash o back slash al posto dei "separator"
     */
    public static function decodeUrlPath($url, $separator = "-", $backSlash = false) {
        if (!empty($url)) {
            return str_replace($separator, $backSlash ? CoreutilsConfig::$BACK_SLASH : CoreutilsConfig::$SLASH, $url);
        }
        return "";
    }

    //------------------------PATH
    /**
     * Costruisce una url a partire da un array di valori
     * @param array $routerParts array di url scomposta in parti (ex. ['user','1','?cod=xxx'])
     * @param string $init parte iniziale della url ( ex http://any) (di default null)
     * @param boolean $backSlash se true indica che la url va separata con back slash, altrimenti slash (di default "false")
     * @return string un link a partire da un array di valori
     */
    public static function getUrlByArray($routerParts = array(), $init = null, $backSlash = false) {
        $pageURL = (!empty($init)) ? $init : "";
        $separator = $backSlash ? CoreutilsConfig::$BACK_SLASH : CoreutilsConfig::$SLASH;
        if (!ArrayUtility::isEmpty($routerParts)) {
            $i = 0;
            foreach ($routerParts as $routerPart) {
                if ($i > 0) {
                    $pageURL .= (StringUtility::contains($routerPart, "?") ? "" : $separator) . $routerPart;
                } else {
                    $pageURL .= $routerPart;
                }
                $i++;
            }
        }
        return $pageURL;
    }

    /**
     * Converte un array di parametri chiave-valore in una stringa di parametri chiave-valore separati da "separator1" o solo valori se "separator2" è null
     * @param array $parameters array di parametri chiave-valore
     * @param string $separator1 separatore chiave-valore (di default "&")
     * @param string|NULL $separator2 separatore di assegnazione valore a chiave (di default "="). Se null ritorna solo i valori senza chiavi
     * @return string una stringa di parametri chiave-valore separati da "separator1"
     */
    public static function getStringParametersByArray($parameters = array(), $separator1 = "&", $separator2 = "=") {
        $pageURL = "";
        if (!ArrayUtility::isEmpty($parameters)) {
            $i = 0;
            foreach ($parameters as $key => $value) {
                $pageURL .= !empty($separator2) ? $key . $separator2 . $value : $value;
                if ($i < count($parameters) - 1) {
                    $pageURL .= $separator1;
                }
                $i++;
            }
        }
        return $pageURL;
    }

    /**
     * Converte una stringa di parametri chiave-valore separati da "separator1" in un array chiave-valore o in un array di valori se "separator2" è null
     * @param string $string stringa di parametri chiave-valore separati da "separator1" ( ex. param1=xxx&param2=yyy)
     * @param string $separator1 separatore chiave-valore (di default "&")
     * @param string|NULL $separator2 separatore di assegnazione valore a chiave (di default "="), se null assegna solo il valore
     * @return array array chiave-valore
     */
    public static function getArrayByStringParameters($string, $separator1 = "&", $separator2 = "=") {
        $arrayFinal = array();
        if (empty($string) || !StringUtility::contains($string, $separator1) || (!empty($separator2) && !StringUtility::contains($string, $separator2))) {
            return null;
        }

        $arrayCouple = explode($separator1, $string);
        foreach ($arrayCouple as $part) {
            if (!empty($separator2)) {
                $arrayKeyValue = explode($separator2, $part);
                if (!ArrayUtility::isEmpty($arrayKeyValue) && count($arrayKeyValue) == 2) {
                    $arrayFinal[$arrayKeyValue[0]] = $arrayKeyValue[1];
                }
            } else {
                array_push($arrayFinal, $part);
            }
        }

        return $arrayFinal;
    }

    /**
     * Ritorna il path dell'applicazione
     * @return string il path dell'applicazione
     */
    public static function getPathApp() {
        return str_replace("" . DS . "webroot" . DS . "", "", WWW_ROOT);
    }

    /**
     * Ritorna la url corrente
     * @param type $view url relativa di cake
     * @return string la url corrente di pagina
     */
    public static function getCurrentUrl($view = null) {
        if (empty($view)) {
            return Router::url(null, true);
        }
        return Router::url($view->here, true);
    }

    /**
     * Ritorna l'url assoluta dell'applicazione
     * @param string $host se true include anche il protocollo web (di default true)
     * @return string url assoluta dell'applicazione
     */
    public static function getCurrentUrlComplete($host = true) {
        $link = "";
        if ($host) {
            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
                $link .= "https";
            } else {
                $link .= "http";
            }

            // Here append the common URL characters.
            $link .= "://";

            // Append the host(domain name, ip) to the URL.
            $link .= $_SERVER['HTTP_HOST'];
        }

        // Append the requested resource location to the URL
        $link .= $_SERVER['REQUEST_URI'];

        // Print the link
        return $link;
    }

    //------------------------CAKE
    /**
     * Ritorna il link cake in path mode (ex. /controller/action/1/2/N), eventualmente cryptando i parametri
     * @param string $controller controller
     * @param string $action action
     * @param array $parameters parametri da convertire in path mode (ex. 1/2/N) (di default array())
     * @param boolean $flgEncryptParameters se true indica che bisogna cryptare i parametri (di default false)
     * @return string il link cake in path mode (ex. /controller/action/1/2/N), eventualmente cryptando i parametri
     */
    public static function getLinkCake($controller, $action, $parameters = array(), $flgEncryptParameters = false) {
        if ($flgEncryptParameters) {
            $parameters = CryptingUtility::encryptArrayByType($parameters);
        }
        $link = Router::url(array(
            'controller' => $controller,
            'action' => $action,
        ));
        if ($action == "index") {
            if (!StringUtility::contains($link, "index") && !ArrayUtility::isEmpty($parameters)) {
                $link .= "/index/" . ArrayUtility::getStringByList($parameters, false, "/");
            } elseif (!ArrayUtility::isEmpty($parameters)) {
                $link .= "/" . ArrayUtility::getStringByList($parameters, false, "/");
            }
        } elseif (!ArrayUtility::isEmpty($parameters)) {

            $link .= "/" . ArrayUtility::getStringByList($parameters, false, "/");
        }
        return $link;
    }

    /**
     * Costruisce un array con i parametri necessari per effettuare una redirect da cakePhp in path mode.<br/>
     * Effettua una conversione del tris di parametri ($action, $controller e $parameters), al fine di ottenere il corretto parametro per la funzione $this->redirect()
     * @param string $action action
     * @param string $controller controller (di default null)
     * @param array $parameters parametri (di default array())
     * @param boolean $flgEncryptParameters se true indica che bisogna cryptare i parametri (di default false)
     * @return array array con i parametri necessari per effettuare una redirect da cakePhp in path mode
     */
    public static function getLinkCakeForRedirect($action, $controller = null, $parameters = array(), $flgEncryptParameters = false) {
        if (!empty($controller)) {
            $array = array(
                'controller' => $controller,
                'action' => $action,
            );
        } else {
            $array = array(
                'action' => $action,
            );
        }
        if (!ArrayUtility::isEmpty($parameters)) {
            if ($flgEncryptParameters) {
                $parameters = CryptingUtility::encryptArrayByType($parameters);
            }
            foreach ($parameters as $key => $value) {
                $array[$key] = $value;
            }
        }
        return $array;
    }

    /**
     * Ritorna il link cake in query mode (ex. /controller/action?key1=1&key2=2&key3=N), eventualmente cryptando i parametri
     * @param string $controller controller
     * @param string $action action
     * @param array $parameters parametri da convertire in query mode (ex. key1=1&key2=2&key3=N) (di default array())
     * @param boolean $flgEncryptParameters se true indica che bisogna cryptare i parametri (di default false)
     * @return string il link cake in path mode (ex. /controller/action/1/2/N), eventualmente cryptando i parametri
     */
    public static function getLinkCakeQueryMode($controller, $action, $parameters = array(), $flgEncryptParameters = false) {
        if (ArrayUtility::isEmpty($parameters)) {
            return PageUtility::getLinkCake($controller, $action, $parameters, $flgEncryptParameters);
        }
        if ($flgEncryptParameters) {
            $parameters = CryptingUtility::encryptArrayByType($parameters);
        }
        $link = Router::url(array(
            'controller' => $controller,
            'action' => $action,
        ));
        if ($action == "index") {
            if (!StringUtility::contains($link, "index")) {
                $link .= "/index?" . PageUtility::getStringParametersByArray($parameters);
            } else {
                $link .= "?" . PageUtility::getStringParametersByArray($parameters);
            }
        } else {

            $link .= "?" . PageUtility::getStringParametersByArray($parameters);
        }
        return $link;
    }

    /**
     * Costruisce un array con i parametri necessari per effettuare una redirect da cakePhp in query mode.<br/>
     * Effettua una conversione del tris di parametri ($action, $controller e $parameters), al fine di ottenere il corretto parametro per la funzione $this->redirect()
     * @param string $action action
     * @param string $controller controller (di default null)
     * @param array $parameters parametri (di default array())
     * @param boolean $flgEncryptParameters se true indica che bisogna cryptare i parametri (di default false)
     * @return array array con i parametri necessari per effettuare una redirect da cakePhp in query mode
     */
    public static function getLinkCakeForRedirectQueryMode($action, $controller = null, $parameters = array(), $flgEncryptParameters = false) {
        if (!empty($controller)) {
            $array = array(
                'controller' => $controller,
                'action' => $action,
            );
        } else {
            $array = array(
                'action' => $action,
            );
        }
        if (!ArrayUtility::isEmpty($parameters)) {
            if ($flgEncryptParameters) {
                $parameters = CryptingUtility::encryptArrayByType($parameters);
            }
            $array["?"] = $parameters;
        }
        return $array;
    }

    /**
     * Ritorna il nome della cartella che contiene l'applicazione
     * @return string nome della cartella applicativa
     */
    public static function getAppNameFolder() {
        $pathWR = str_replace(CoreutilsConfig::$BACK_SLASH, CoreutilsConfig::$SLASH, WWW_ROOT);
        $split = explode(CoreutilsConfig::$SLASH, $pathWR);
        $key = array_search("app", $split);
        return $split[$key - 1];
    }

    //------------------------FORM
    /**
     * Ritorna la request corretta (POST o GET)
     * @param unknown $request
     */
    public static function getRequest($request) {
        if ($request->is('post')) {
            return $request->data;
        } elseif ($request->is('get')) {
            if (!empty($request->params['named'])) {
                $request->query = $request->params['named'];
            }
            if (!empty($request->query)) {
                return $request->query;
            }
        }
        return null;
    }

    /**
     * Ritorna il valore di una variabile proveniente da una form, indipendentemente dal tipo di form (get o post)
     * @param string $field
     * @return string|NULL
     */
    public static function getFieldByPhpForm($field) {
        if (array_key_exists($field, $_POST)) {
            return $_POST[$field];
        } elseif (array_key_exists($field, $_REQUEST)) {
            return $_REQUEST[$field];
        } elseif (array_key_exists($field, $_GET)) {
            return $_GET[$field];
        } else {
            return null;
        }
    }

    /**
     * Richiama il giusto metodo per avere il valore di una variabile proveniente da una form html.
     * Se viene valorizzato il campo $obj(Nome della classe cakePhp) allora viene invocato il metodo getObjectFieldRequest, altrimenti getStringFieldRequest
     * @param string $field
     * @param type $request
     * @param type $obj
     * @param boolean $bool se true indica che il parametro è di tipo boolean (di default è false)
     * @return type|string
     */
    public static function getFieldRequest($field, $request, $obj = null, $bool = false) {
        if (empty($obj)) {
            return PageUtility::getStringFieldRequest($field, $request, $bool);
        } else {
            return PageUtility::getObjectFieldRequest($obj, $field, $request);
        }
    }

    /**
     * Ritorna il valore di un campo proveniente da una form html leggendolo dalla request cake.<br/>
     * Non vengono elaborati campi stringa vuoti ("") o stringa nulla ("null")
     * @param string $field id del campo html
     * @param type $request oggetto request cake
     * @param boolean $bool se true indica che il parametro è di tipo boolean (di default è false)
     * @return string il valore di un campo proveniente da una form html
     */
    public static function getStringFieldRequest($field, $request, $bool = false) {
        $return = "";
        if ($request->is('post')) {
            if (array_key_exists($field, $request->data) && $request->data[$field] != null && $request->data[$field] != "" && $request->data[$field] != "null") {
                $return = $request->data[$field];
                $request->query[$field] = $request->data[$field];
            }
        } elseif (!empty($request->query)) {
            if (array_key_exists($field, $request->query) && $request->query[$field] != null && $request->query[$field] != "" && $request->query[$field] != "null") {
                $return = $request->query[$field];
            }

        }
        if ($bool) {
            switch ($return) {
            case "1":
            case "true":
                $return = true;
                break;
            case "0":
            case "false":
                $return = false;
                break;
            default:
                $return = false;
                break;
            }
        }

        return $return;
    }

    /**
     * Ritorna il valore di un campo bidimensionale (formato classe cakePhp) proveniente da una form html leggendolo dalla request cake.<br/>
     * Non vengono elaborati campi stringa vuoti ("") o stringa nulla ("null")
     * @param string $obj nome dell'oggetto cakePhp
     * @param string $field id del campo html
     * @param type $request oggetto request
     * @param boolean $bool se true indica che il parametro è di tipo boolean (di default è false)
     * @return string il valore di un campo bidimensionale (formato classe cakePhp) proveniente da una form html
     */
    public static function getObjectFieldRequest($obj, $field, $request, $bool = false) {
        $return = "";
        if ($request->is('post')) {
            if (array_key_exists($field, $request->data[$obj]) && $request->data[$obj][$field] != null && $request->data[$obj][$field] != "" && $request->data[$obj][$field] != "null") {
                $return = $request->data[$obj][$field];
                $request->query[$obj][$field] = $request->data[$obj][$field];
            }
        } elseif (!empty($request->query)) {
            if (array_key_exists($field, $request->query[$obj]) && $request->query[$obj][$field] != null && $request->query[$obj][$field] != "" && $request->query[$obj][$field] != "null") {
                $return = $request->query[$obj][$field];
            }

        }

        if ($bool) {
            switch ($return) {
            case "1":
            case "true":
                $return = true;
                break;
            case "0":
            case "false":
                $return = false;
                break;
            default:
                break;
            }
        }

        return $return;
    }

    /**
     * Verifica se esiste una variabile proveniente da una form html.
     * Se viene valorizzato il campo $obj(Nome della classe cakePhp) allora viene invocato il metodo existObjectFieldRequest, altrimenti existStringFieldRequest
     * @param string $field
     * @param type $request
     * @param type $obj
     * @param boolean $bool se true indica che il parametro è di tipo boolean (di default è false)
     * @return type|string
     */
    public static function existFieldRequest($field, $request, $obj = null, $bool = false) {
        if (empty($obj)) {
            return PageUtility::existStringFieldRequest($field, $request, $bool);
        } else {
            return PageUtility::existObjectFieldRequest($obj, $field, $request);
        }
    }

    /**
     * Ritorna true se esiste un campo proveniente da una form html leggendolo dalla request cake.<br/>
     * @param string $field id del campo html
     * @param type $request oggetto request cake
     * @return string il valore di un campo proveniente da una form html
     */
    public static function existStringFieldRequest($field, $request) {
        if ($request->is('post')) {
            if (array_key_exists($field, $request->data)) {
                return true;
            }
        } elseif (!empty($request->query)) {
            if (array_key_exists($field, $request->query)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Ritorna true se esiste un campo bidimensionale (formato classe cakePhp) proveniente da una form html leggendolo dalla request cake.<br/>
     * @param string $obj nome dell'oggetto cakePhp
     * @param string $field id del campo html
     * @param type $request oggetto request
     * @param boolean $bool se true indica che il parametro è di tipo boolean (di default è false)
     * @return string il valore di un campo bidimensionale (formato classe cakePhp) proveniente da una form html
     */
    public static function existObjectFieldRequest($obj, $field, $request, $bool = false) {
        if ($request->is('post')) {
            if (array_key_exists($field, $request->data[$obj])) {
                return true;
            }
        } elseif (!empty($request->query)) {
            if (array_key_exists($field, $request->query[$obj])) {
                return true;
            }
        }
        return false;
    }
}
