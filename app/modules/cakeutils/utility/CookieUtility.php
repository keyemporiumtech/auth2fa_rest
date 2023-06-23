<?php
App::uses("ArrayUtility", "modules/coreutils/utility");
App::uses("EnumCookieType", "modules/cakeutils/config");
App::uses("CookieDTO", "modules/cakeutils/classes");

class CookieUtility {

    static $keyArrayCreated = "COOKIE_WRITE_";
    static $keyArrayInstanced = "COOKIE_INSTANCE_";
    /**
     * Crea in sessione i flag e gli array per le varie tipologie di cookie
     *
     * @return void
     */
    static function create() {
        if (!CakeSession::check(EnumCookieType::NECESSARY)) {
            CakeSession::write(EnumCookieType::NECESSARY, true);
        }
        if (!CakeSession::check(EnumCookieType::PREFERENCE)) {
            CakeSession::write(EnumCookieType::PREFERENCE, false);
        }
        if (!CakeSession::check(EnumCookieType::STATISTIC)) {
            CakeSession::write(EnumCookieType::STATISTIC, false);
        }
        if (!CakeSession::check(EnumCookieType::MARKETING)) {
            CakeSession::write(EnumCookieType::MARKETING, false);
        }
        if (!CakeSession::check(EnumCookieType::NOT_CLASSIFIED)) {
            CakeSession::write(EnumCookieType::NOT_CLASSIFIED, false);
        }
        // ARRAY INSTANCED
        if (!CakeSession::check(CookieUtility::$keyArrayInstanced . EnumCookieType::NECESSARY)) {
            CakeSession::write(CookieUtility::$keyArrayInstanced . EnumCookieType::NECESSARY, []);
        }
        if (!CakeSession::check(CookieUtility::$keyArrayInstanced . EnumCookieType::PREFERENCE)) {
            CakeSession::write(CookieUtility::$keyArrayInstanced . EnumCookieType::PREFERENCE, []);
        }
        if (!CakeSession::check(CookieUtility::$keyArrayInstanced . EnumCookieType::STATISTIC)) {
            CakeSession::write(CookieUtility::$keyArrayInstanced . EnumCookieType::STATISTIC, []);
        }
        if (!CakeSession::check(CookieUtility::$keyArrayInstanced . EnumCookieType::MARKETING)) {
            CakeSession::write(CookieUtility::$keyArrayInstanced . EnumCookieType::MARKETING, []);
        }
        if (!CakeSession::check(CookieUtility::$keyArrayInstanced . EnumCookieType::NOT_CLASSIFIED)) {
            CakeSession::write(CookieUtility::$keyArrayInstanced . EnumCookieType::NOT_CLASSIFIED, []);
        }
        // ARRAY CREATED
        if (!CakeSession::check(CookieUtility::$keyArrayCreated . EnumCookieType::NECESSARY)) {
            CakeSession::write(CookieUtility::$keyArrayCreated . EnumCookieType::NECESSARY, []);
        }
        if (!CakeSession::check(CookieUtility::$keyArrayCreated . EnumCookieType::PREFERENCE)) {
            CakeSession::write(CookieUtility::$keyArrayCreated . EnumCookieType::PREFERENCE, []);
        }
        if (!CakeSession::check(CookieUtility::$keyArrayCreated . EnumCookieType::STATISTIC)) {
            CakeSession::write(CookieUtility::$keyArrayCreated . EnumCookieType::STATISTIC, []);
        }
        if (!CakeSession::check(CookieUtility::$keyArrayCreated . EnumCookieType::MARKETING)) {
            CakeSession::write(CookieUtility::$keyArrayCreated . EnumCookieType::MARKETING, []);
        }
        if (!CakeSession::check(CookieUtility::$keyArrayCreated . EnumCookieType::NOT_CLASSIFIED)) {
            CakeSession::write(CookieUtility::$keyArrayCreated . EnumCookieType::NOT_CLASSIFIED, []);
        }
    }

    /**
     * Aggiorna i flag delle tipologie di cookie e per le tipologie disabilitate rimuove i cookie istanziati
     *
     * @param  boolean $flgPreference valore per cookie di preferenza
     * @param  boolean $flgStatistic valore per cookie di statistica
     * @param  boolean $flgMarketing valore per cookie di marketing
     * @param  boolean $flgNotClassified valore per cookie non classificati
     * @param  boolean $flgNecessary valore per cookie necessari (di default true)
     * @return void
     */
    static function update($flgPreference, $flgStatistic, $flgMarketing, $flgNotClassified, $flgNecessary = true) {
        CookieUtility::updateType(EnumCookieType::NECESSARY, $flgNecessary);
        if (!$flgNecessary) {
            CookieUtility::disableType(EnumCookieType::NECESSARY);
        }
        CookieUtility::updateType(EnumCookieType::PREFERENCE, $flgPreference);
        if (!$flgPreference) {
            CookieUtility::disableType(EnumCookieType::PREFERENCE);
        }
        CookieUtility::updateType(EnumCookieType::STATISTIC, $flgStatistic);
        if (!$flgStatistic) {
            CookieUtility::disableType(EnumCookieType::STATISTIC);
        }
        CookieUtility::updateType(EnumCookieType::MARKETING, $flgMarketing);
        if (!$flgMarketing) {
            CookieUtility::disableType(EnumCookieType::MARKETING);
        }
        CookieUtility::updateType(EnumCookieType::NOT_CLASSIFIED, $flgNotClassified);
        if (!$flgNotClassified) {
            CookieUtility::disableType(EnumCookieType::NOT_CLASSIFIED);
        }
    }

    // -------------------------- TYPES
    /**
     * Ritorna il valore del flag per una tipologia di cookie
     *
     * @param  string $type tipologia di cookie
     * @return boolean true se è stato settato, false altrimenti
     */
    static function isActiveType($type) {
        return CakeSession::check($type) ? CakeSession::read($type) : false;
    }

    /**
     * Ritorna la lista dei cookie settati
     *
     * @param  string $type tipologia di cookie
     * @return array lista dei cookie
     */
    static function listType($type) {
        return CakeSession::check(CookieUtility::$keyArrayCreated . $type) ? CakeSession::read(CookieUtility::$keyArrayCreated . $type) : array();
    }
    /**
     * Aggiorna il flag per una tipologia di cookie
     *
     * @param  string $type tipologia di cookie
     * @param  boolean $flg valore da settare
     * @return void
     */
    static function updateType($type, $flg) {
        CakeSession::write($type, $flg);
        if (!$flg) {
            CookieUtility::disableType($type);
        }
    }
    /**
     * Disabilita una tipologia di cookie rimuovendone tutti i cookie istanziati per quella tipologia
     *
     * @param  string $type tipologia di cookie da rimuovere
     * @return void
     */
    static function disableType($type) {
        $arr = CakeSession::read(CookieUtility::$keyArrayCreated . $type);
        foreach ($arr as $key => $value) {
            CookieUtility::removeCookieType($key, $type);
        }
    }

    // -------------------------- INSTANCE
    /**
     * Istanzia un cookie aggiungendo, se ancora non esiste, all'array di riferimenti cookie per la tipologia richiesta
     *
     * @param  CookieDTO $dto informazioni del cookie da instanziare
     * @return void
     */
    static function instanceCookieType(CookieDTO $dto) {
        if ($dto->type) {
            $arr = CakeSession::read(CookieUtility::$keyArrayInstanced . $dto->type);
            $arr[$dto->name] = (array) $dto;
            CakeSession::write(CookieUtility::$keyArrayInstanced . $dto->type, $arr);
        }
    }

    /**
     * Ritorna la lista dei cookie istanziati per una tipologia.
     *
     * @param  string $type tipologia del cookie
     * @return array|null una lista di cookie dto
     */
    static function listInstancedType($type) {
        if ($type) {
            $arr = CakeSession::read(CookieUtility::$keyArrayInstanced . $type);
            $ret = array();
            foreach ($arr as $key => $value) {
                array_push($ret, $value);
            }
            return $ret;
        }
        return null;
    }

    /**
     * Ritorna un cookie specifico di una tipologia.
     *
     * @param  string $type tipologia del cookie
     * @param  string $key chiave del cookie
     * @return mixed|null una lista di cookie dto o un singolo elemento
     */
    static function readInstancedType($type, $key) {
        if ($type) {
            $arr = CakeSession::read(CookieUtility::$keyArrayInstanced . $type);
            return $arr[$key];
        }
        return null;
    }

    // -------------------------- WRITED
    /**
     * Aggiunge un cookie, se ancora non esiste, all'array di riferimenti cookie per la tipologia richiesta
     *
     * @param  CookieDTO $dto informazioni del cookie da instanziare
     * @return void
     */
    static function addCookieType(CookieDTO $dto) {
        if ($dto->type) {
            $arr = CakeSession::read(CookieUtility::$keyArrayCreated . $dto->type);
            $arr[$dto->name] = (array) $dto;
            CakeSession::write(CookieUtility::$keyArrayCreated . $dto->type, $arr);
        }
    }

    /**
     * Crea un cookie aggiungendo, se ancora non esiste, all'array di riferimenti cookie per la tipologia richiesta
     *
     * @param  CookieinnerComponent $cookie oggetto cookie del controller
     * @param  CookieDTO $dto informazioni del cookie da instanziare
     * @return void
     */
    static function createCookieType(CookieinnerComponent $cookie, CookieDTO $dto) {
        $cookie->write($dto->name, $dto->value, $dto->hash, $dto->duration);
        if ($dto->type) {
            $arr = CakeSession::read(CookieUtility::$keyArrayCreated . $dto->type);
            if (!array_key_exists($dto->name, $arr) || (array_key_exists($dto->name, $arr) && empty($arr[$dto->name]))) {
                $arr[$dto->name] = (array) $dto;
                CakeSession::write(CookieUtility::$keyArrayCreated . $dto->type, $arr);
            }
        }
    }

    /**
     * Ritorna il valore di un cookie
     *
     * @param  string $key chiave del cookie
     * @param  string $type tipologia del cookie
     * @return mixed|null Valore del cookie
     */
    static function readCookieType($key, $type) {
        $arr = CakeSession::read(CookieUtility::$keyArrayCreated . $type);
        return array_key_exists($key, $arr) ? $arr[$key] : null;
    }

    /**
     * Ritorna true se un cookie esiste
     *
     * @param  string $key chiave del cookie
     * @param  string $type tipologia del cookie
     * @return bool True se il cookie esiste
     */
    static function checkCookieType($key, $type) {
        $arr = CakeSession::read(CookieUtility::$keyArrayCreated . $type);
        return !ArrayUtility::isEmpty() && array_key_exists($key, $arr) && !empty($arr[$key]) && !empty($arr[$key]["name"]);
    }

    /**
     * Rimuove un cookie
     *
     * @param  string $key chiave del cookie
     * @param  string $type tipologia del cookie
     * @return void
     */
    static function removeCookieType($key, $type) {
        $arr = CakeSession::read(CookieUtility::$keyArrayCreated . $type);
        unset($arr[$key]);
        CakeSession::write(CookieUtility::$keyArrayCreated . $type, $arr);
    }

    // -------------------------- CONTROLLER
    /**
     * Istanzia un cookie per il controller
     *
     * @param  CookieinnerComponent $cookie oggetto cookie del controller
     * @param  string $key chiave del cookie
     * @param  mixed $value valore del cookie
     * @param  int $duration valore in secondi della durata del cookie
     * @param  string $type tipologia del cookie da istanziare
     * @param  boolean $hash se true il valore viene codificato
     * @return void
     */
    static function instanceCookie(CookieinnerComponent $cookie, $key, $value, $duration, $hash = true) {
        $cookie->write($key, $value, $hash, $duration);
    }
    /**
     * Ritorna il valore di un cookie per il controller
     *
     * @param  CookieinnerComponent $cookie oggetto cookie del controller
     * @param  string $key chiave del cookie
     * @return string|null Value for specified key
     */
    static function readCookie(CookieinnerComponent $cookie, $key) {
        return $cookie->read($key);
    }

    /**
     * Ritorna true se un cookie esiste nel controller
     *
     * @param  CookieinnerComponent $cookie oggetto cookie del controller
     * @param  string $key chiave del cookie
     * @return bool True if variable is there
     */
    static function checkCookie(CookieinnerComponent $cookie, $key) {
        return $cookie->check($key);
    }

    /**
     * Rimuove un cookie dal controller
     *
     * @param  CookieinnerComponent $cookie oggetto cookie del controller
     * @param  string $key chiave del cookie
     * @return void
     */
    static function removeCookie(CookieinnerComponent $cookie, $key) {
        $cookie->delete($key);
    }

}
