<?php
App::uses("AppController", "Controller");
App::uses("Codes", "Config/system");
App::uses("Enables", "Config/system");
App::uses("Defaults", "Config/system");
App::uses("EnumTypeCrypt", "modules/crypting/config");
App::uses("CryptingUtility", "modules/crypting/utility");
App::uses("PageUtility", "modules/coreutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("MessageStatus", "modules/cakeutils/classes");
App::uses("EnumResponseCode", "modules/cakeutils/config");
App::uses("SystemUtility", "modules/coreutils/utility");

class TokenUtility {

    static function checkInnerToken(AppController $controller) {
        $request = $controller->request;
        $tokenInHeader = array_key_exists("innertoken", SystemUtility::getallheaders());
        $tokenInRequest = PageUtility::existFieldRequest("innertoken", $request, null, true);
        if ($tokenInHeader || $tokenInRequest) {
            $token = $tokenInHeader ? $request->header("innertoken") : null;
            $exception = null;
            if (empty($token)) {
                $token = PageUtility::getFieldRequest("innertoken", $request);
            }

            if (empty($token)) {
                // token non fornito
                $exception = new Exception("TOKEN INNER IS REQUIRED", Codes::get("CHECK_INNER_NULL"));
            } else if ($token != TokenUtility::getInnerToken()) {
                // token non valido
                $exception = new Exception("TOKEN INNER {$token} IS NOT VALID", Codes::get("CHECK_INNER_NOT_VALID"));
            }
        } else if (!$tokenInHeader && !$tokenInRequest) {
            $exception = new Exception("TOKEN INNER IS REQUIRED", Codes::get("CHECK_INNER_NULL"));
        }
        if (!empty($exception) && !Enables::isDebug()) {
            $objInfo = MessageUtility::messageInfo("ERROR_ACCESS", "errors", Codes::get("EXCEPTION_GENERIC"));
            $objException = MessageUtility::messageExceptionByException($exception);
            $objInternal = MessageUtility::messageInternal("ERROR_THROW_EXCEPTION", "errors", Codes::get("EXCEPTION_GENERIC"));
            $status = new MessageStatus(EnumMessageStatus::ERROR, $objInfo, $objException, $objInternal, EnumResponseCode::INTERNAL_SERVER_ERROR);

            $controller->forceResponse($status, "");
        }
    }

    static function getInnerToken() {
        return CryptingUtility::encryptByType(Defaults::get('inner_name') . "." . Defaults::get('inner_secret'), EnumTypeCrypt::SHA256);
    }

    static function previewInnerToken($secret = null, $name = null) {
        if (empty($name)) {
            $name = Defaults::get('inner_name');
        }
        if (empty($secret)) {
            $secret = Defaults::get('inner_secret');
        }
        $phrase = Defaults::get('PHRASE_SHA256');
        $iv = Defaults::get('IV_SHA256');

        return Crypting::encrypt_sha256($name . "." . $secret, $phrase, $iv);
    }
}