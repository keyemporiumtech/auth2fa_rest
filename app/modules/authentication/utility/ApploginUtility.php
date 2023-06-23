<?php
App::uses("AppController", "Controller");
App::uses("Enables", "Config/system");
App::uses("Defaults", "Config/system");
App::uses("Codes", "Config/system");
App::uses("AuthenticationConfig", "modules/authentication/config");
App::uses("EnumTypeCrypt", "modules/crypting/config");
App::uses("CryptingUtility", "modules/crypting/utility");
App::uses("PageUtility", "modules/coreutils/utility");
App::uses("SystemUtility", "modules/coreutils/utility");
App::uses("ApploginUI", "modules/authentication/delegate");
App::uses("AppactivityUtility", "modules/authentication/utility");
App::uses("PermissionUtility", "modules/authentication/utility");

class ApploginUtility {

    static function getToken(CakeRequest $request) {
        $tokenInHeader = array_key_exists(Defaults::get("param_name_token_login"), SystemUtility::getallheaders());
        $tokenInRequest = PageUtility::existFieldRequest(Defaults::get("param_name_token_login"), $request, null, true);
        if ($tokenInHeader) {
            return $request->header(Defaults::get("param_name_token_login"));
        } elseif ($tokenInRequest) {
            return PageUtility::getFieldRequest(Defaults::get("param_name_token_login"), $request);
        }
        return null;
    }

    static function buildToken($clientid, $payloadUser, $rememberme = false) {
        $time = time();
        $header = array(
            "alg" => AuthenticationConfig::$ALGORITHM_TYPE,
            "type" => Defaults::get("algorithm_name"),
        );
        $paylod = array(
            "iss" => Defaults::get("server_name"), // server name
            "aud" => $clientid, // destinatario del token
            "iat" => $time, // creato il
            "nbf" => ($time + AuthenticationConfig::$TOKEN_START_TIME), // inizio validità
            "exp" => $rememberme ? -1 : ($time + Defaults::get("token_end_time")), // fine validità
            "data" => $payloadUser,
        );
        $headerKEY = base64_encode(json_encode($header));
        $paylodKEY = base64_encode(json_encode($paylod));
        return $headerKEY . "." . $paylodKEY . "." . sha1(base64_encode($headerKEY . "." . $paylodKEY . "." . Defaults::get("secret_key")));
    }

    static function decodeTokenLogin($token) {
        if (empty($token)) {
            return array(
                "auth" => false,
                "message" => 'TOKEN_EMPTY',
            );
        }
        $tokenSplit = explode(".", $token);
        if (empty($tokenSplit) || count($tokenSplit) != 3) {
            return array(
                "auth" => false,
                "message" => 'TOKEN_NOT_SPLITTED',
            );
        }
        $headerReceived = $tokenSplit[0];
        $paylodReceived = $tokenSplit[1];
        $tokenReceived = $tokenSplit[2];
        $verify = sha1(base64_encode($headerReceived . "." . $paylodReceived . "." . Defaults::get("secret_key"))) == $tokenReceived;
        return array(
            "auth" => $verify,
            "message" => $verify ? 'TOKEN_VALID' : 'TOKEN_NOT_VALID',
            "header" => json_decode(base64_decode($headerReceived), true),
            "payload" => json_decode(base64_decode($paylodReceived), true),
            "token" => $tokenReceived,
        );
    }

    static function verifyToken($token) {
        if (empty($token)) {
            return false;
        }
        $tokenSplit = explode(".", $token);
        if (empty($tokenSplit) || count($tokenSplit) != 3) {
            return false;
        }
        $headerReceived = $tokenSplit[0];
        $paylodReceived = $tokenSplit[1];
        $tokenReceived = $tokenSplit[2];
        return sha1(base64_encode($headerReceived . "." . $paylodReceived . "." . Defaults::get("secret_key"))) == $tokenReceived;
    }

    static function verifySession($token) {
        if (empty($token)) {
            return false;
        }
        $tokenSplit = explode(".", $token);
        if (empty($tokenSplit) || count($tokenSplit) != 3) {
            return false;
        }
        $payload = json_decode(base64_decode($tokenSplit[1]), true);
        if ($payload['exp'] != -1 && time() > $payload['exp']) {
            return false;
        }
        return true;
    }

    static function verifyClient($token) {
        $clientIdInHeader = array_key_exists("clientId", SystemUtility::getallheaders());
        if (empty($clientIdInHeader)) {
            return false;
        }
        if (empty($token)) {
            return false;
        }
        $tokenSplit = explode(".", $token);
        if (empty($tokenSplit) || count($tokenSplit) != 3) {
            return false;
        }
        $payload = json_decode(base64_decode($tokenSplit[1]), true);
        if ($payload['aud'] != $clientIdInHeader) {
            return false;
        }
        return true;
    }

    /**
     * Verifica la presenza del token di sessione nella request del cliente e ne gestisce la validità.
     * Se il token non è presente o non è valido oppure è scaduto, forza il controller ad emettere una response con un message status di session non autorizzato.
     * @param AppController $controller controller su cui effettuare il check
     */
    static function checkTokenLogin(AppController $controller) {
        $request = $controller->request;
        $tokenInHeader = array_key_exists(Defaults::get("param_name_token_login"), SystemUtility::getallheaders());
        $tokenInRequest = PageUtility::existFieldRequest(Defaults::get("param_name_token_login"), $request);
        $exception = null;
        $expired = false;
        if ($tokenInHeader || !empty($tokenInRequest)) {
            $token = $tokenInHeader ? $request->header(Defaults::get("param_name_token_login")) : null;
            if (empty($token)) {
                $token = PageUtility::getFieldRequest(Defaults::get("param_name_token_login"), $request);
            }

            if (empty($token)) {
                // token non fornito
                PermissionUtility::logMessage("Token NON PASSATO nel parametro " . Defaults::get("param_name_token_login"));
                $exception = new Exception("TOKEN LOGIN IS REQUIRED", Codes::get("TOKEN_LOGIN_NULL"));
            } else if (!ApploginUtility::verifyToken($token)) {
                // token non valido
                PermissionUtility::logMessage("Token NON VALIDO passato nel parametro " . Defaults::get("param_name_token_login") . " => {$token}");
                $exception = new Exception("TOKEN LOGIN {$token} IS NOT VALID", Codes::get("TOKEN_LOGIN_NOT_VALID"));
            } else if (!ApploginUtility::verifySession($token)) {
                // sessione scaduta
                $expirationTime = ApploginUtility::getExpirationToken($token);
                $expirationDate = "";
                if (!empty($expirationTime)) {
                    $expirationDate = date('Y-m-d H:i:s', $expirationTime);
                }
                $exception = new Exception("TOKEN LOGIN EXPIRED IN {$expirationDate}", Codes::get("TOKEN_LOGIN_EXPIRED"));
                $expired = true;
                PermissionUtility::logMessage("Token SCADUTO passato nel parametro " . Defaults::get("param_name_token_login") . " con valore {$token} => scaduto il {$expirationDate}");
            }
        } else {
            // token non fornito
            PermissionUtility::logMessage("Token NON PASSATO nel parametro " . Defaults::get("param_name_token_login"));
            $exception = new Exception("TOKEN LOGIN IS REQUIRED", Codes::get("TOKEN_LOGIN_NULL"));
        }

        if (!empty($exception) && !Enables::isDebug()) {
            $ui = new ApploginUI();
            $res = $ui->setTokenError($exception, $expired);
            $controller->forceResponse($ui->status, $res);
        }
    }

    static function getExpirationToken($token) {
        if (empty($token)) {
            return null;
        }
        $tokenSplit = explode(".", $token);
        if (empty($tokenSplit) || count($tokenSplit) != 3) {
            return null;
        }
        $payload = json_decode(base64_decode($tokenSplit[1]), true);
        return $payload['exp'];
    }

    static function memoAuthtoken($username, $authtoken) {
        CakeSession::write($username, $authtoken);
    }

    static function memoProfile($username, $profile) {
        CakeSession::write($username . "_PROFILE", $profile);
        AppactivityUtility::memoActivityByProfile($username, $profile);
    }

    static function memoProfileDefault($username) {
        if (empty(ApploginUtility::getProfileLogged($username))) {
            $ui = new ApploginUI();
            $ui->memoProfileDefault($username);
            AppactivityUtility::memoActivityByProfile($username, ApploginUtility::getProfileLogged($username));
        }
    }

    /**
     * Invia tramite header response il token di sessione di un utente al client
     * @param string $username Nome utente loggato
     * @param AppController $controller Controller che manderà in response il valore del token
     */
    static function sendAuthtoken($username, AppController $controller) {
        $authtoken = CakeSession::read($username);
        $controller->response->header(Defaults::get("param_name_token_login"), $authtoken);
    }

    /**
     * Ritorna la username dell'utente loggato in sessione
     *
     * @param  CakeRequest $request oggetto request
     * @param  string $authtoken se passato verifica l'utente loggato con quel token
     * @return string username dell'utente loggato in sessione
     */
    static function getUsernameLogged(CakeRequest $request, $authtoken = null) {
        if (empty($authtoken)) {
            $authtoken = ApploginUtility::getToken($request);
        }
        $login = ApploginUtility::decodeTokenLogin($authtoken);
        if (!$login['auth']) {
            throw new Exception('TOKEN NOT VALID');
        }

        $dataUser = json_decode($login['payload']['data'], true);

        return $dataUser['username'];
    }

    /**
     * Ritorna la username da un token di autenticazione
     *
     * @param  string $authtoken token di autenticazione
     * @return string username dell'utente ricavato dal token di autenticazione
     */
    static function getUsernameLoggedByToken($authtoken = null) {
        if (empty($authtoken)) {
            throw new Exception('TOKEN EMPTY');
        }
        $login = ApploginUtility::decodeTokenLogin($authtoken);
        if (!$login['auth']) {
            throw new Exception('TOKEN NOT VALID');
        }

        $dataUser = json_decode($login['payload']['data'], true);

        return $dataUser['username'];
    }

    static function getProfileLogged($username) {
        return CakeSession::read($username . "_PROFILE");
    }
}