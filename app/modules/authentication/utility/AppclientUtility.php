<?php
App::uses("AppController", "Controller");
App::uses("Enables", "Config/system");
App::uses("Defaults", "Config/system");
App::uses("Codes", "Config/system");
App::uses("EnumTypeCrypt", "modules/crypting/config");
App::uses("CryptingUtility", "modules/crypting/utility");
App::uses("PageUtility", "modules/coreutils/utility");
App::uses("SystemUtility", "modules/coreutils/utility");
App::uses("AppclientUI", "modules/authentication/delegate");
App::uses("ClienttokenBS", "modules/authentication/business");

class AppclientUtility {

    static function getToken(CakeRequest $request) {
        $tokenInHeader = array_key_exists(Defaults::get("param_name_token_client"), SystemUtility::getallheaders());
        $tokenInRequest = PageUtility::existFieldRequest(Defaults::get("param_name_token_client"), $request, null, true);
        if ($tokenInHeader) {
            return $request->header(Defaults::get("param_name_token_client"));
        } elseif ($tokenInRequest) {
            return PageUtility::getFieldRequest(Defaults::get("param_name_token_client"), $request);
        }
        return null;
    }

    static function buildToken($clientid) {
        $encrypt_input = CryptingUtility::encryptByType($clientid, EnumTypeCrypt::AES);
        $encrypt_secret = CryptingUtility::encryptByType(Defaults::get("secret_key"), EnumTypeCrypt::SHA256);
        return base64_encode($encrypt_input) . "." . sha1(base64_encode($encrypt_input . "." . $encrypt_secret));
    }

    static function verifyToken($token, $clientid = null) {
        if (empty($token)) {
            return false;
        }
        if (empty($clientid)) {
            $clientid = AppclientUtility::decodeTokenClient($token);
        }
        if (empty($clientid)) {
            return false;
        }
        return $token == AppclientUtility::buildToken($clientid);
    }

    static function decodeTokenClient($token) {
        $arr = explode(".", $token);
        if (count($arr) == 2) {
            return CryptingUtility::decryptByType(base64_decode($arr[0]), EnumTypeCrypt::AES);
        }
        return null;
    }

    /**
     * Verifica la presenza del token nella request del cliente e ne gestisce la validità.
     * Se il token non è presente o non è valido, forza il controller ad emettere una response con un message status di client non autorizzato.
     * @param AppController $controller controller su cui effettuare il check
     */
    static function checkTokenClient(AppController $controller) {
        $request = $controller->request;
        $tokenInHeader = array_key_exists(Defaults::get("param_name_token_client"), SystemUtility::getallheaders());
        $tokenInRequest = PageUtility::existFieldRequest(Defaults::get("param_name_token_client"), $request, null, true);
        if (Enables::get("full_client_verification") || $tokenInHeader || $tokenInRequest) {
            $token = $tokenInHeader ? $request->header(Defaults::get("param_name_token_client")) : null;
            $exception = null;
            if (empty($token)) {
                $token = PageUtility::getFieldRequest(Defaults::get("param_name_token_client"), $request);
            }
            if (empty($token)) {
                // token non fornito
                $exception = new Exception("TOKEN IS REQUIRED", Codes::get("TOKEN_CLIENT_NULL"));
            } else if (!AppclientUtility::verifyToken($token)) {
                // token non valido
                $exception = new Exception("TOKEN {$token} IS NOT VALID", Codes::get("TOKEN_CLIENT_NOT_VALID"));
            }
        } else if (Enables::get("full_client_verification") && !$tokenInHeader && !$tokenInRequest) {
            $exception = new Exception("TOKEN IS REQUIRED", Codes::get("TOKEN_CLIENT_NULL"));
        }
        if ((Enables::get("full_client_verification") || $tokenInHeader || $tokenInRequest) && !AppclientUtility::checkTokenClientDb(AppclientUtility::getToken($request))) {
            $exception = new Exception("TOKEN MUST BE INTO DB", Codes::get("TOKEN_CLIENT_NOT_IN_DB"));
        }
        if (!empty($exception) && !Enables::isDebug()) {
            $ui = new AppclientUI();
            $res = $ui->setTokenError($exception);
            $controller->forceResponse($ui->status, $res);
        }
    }

    static function checkTokenClientDb($token) {
        if (empty($token)) {
            return false;
        }
        try {
            $clienttokenBS = new ClienttokenBS();
            $clienttokenBS->addCondition("token", CryptingUtility::encryptByType($token, EnumTypeCrypt::SHA256));
            $clienttoken = $clienttokenBS->unique();
            return !empty($clienttoken) ? true : false;
        } catch (Exception $e) {
            return false;
        }
    }

    static function previewTokenClient($clientid) {
        $phrase = Defaults::get('PHRASE_AES');
        $iv = Defaults::get('IV_AES');
        $encrypt_input = Crypting::encrypt_aes($clientid, $phrase, $iv);
        $encrypt_secret = Crypting::encrypt_aes(Defaults::get("secret_key"), $phrase, $iv);
        return base64_encode($encrypt_input) . "." . sha1(base64_encode($encrypt_input . "." . $encrypt_secret));
    }
}