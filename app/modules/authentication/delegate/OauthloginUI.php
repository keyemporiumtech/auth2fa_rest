<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("FileUtility", "modules/coreutils/utility");
App::uses("TranslatorUtility", "modules/cakeutils/utility");
App::uses("ManagerOauthLogin", "modules/authentication/plugin/OauthSocial");
App::uses("ConfirmoperationRequest", "modules/authentication/classes");
App::uses("ConfirmoperationUI", "modules/authentication/delegate");
App::uses("UserBS", "modules/authentication/business");
App::uses("UserUI", "modules/authentication/delegate");
App::uses("UserreportBS", "modules/authentication/business");
App::uses("ApploginUtility", "modules/authentication/utility");
App::uses("UserUtility", "modules/authentication/utility");
App::uses("UseroauthsocialBS", "modules/authentication/business");

class OauthloginUI extends AppGenericUI {

    function __construct() {
        parent::__construct("OauthloginUI");
        $this->localefile = "oauthlogin";
        $this->obj = null;
    }

    function check($socialUserIn = null, $tpsocialreference = null, $id_user = null) {
        $this->LOG_FUNCTION = "check";
        try {
            if (empty($socialUserIn) || empty($tpsocialreference)) {
                DelegateUtility::paramsNull($this, "ERROR_OAUTH_CHECK");
                return "";
            }
            parent::startTransaction();

            $user = ManagerOauthLogin::check($this->json, $socialUserIn, $tpsocialreference, $id_user);

            parent::commitTransaction();

            $this->ok();
            return $user;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_OAUTH_CHECK");
            return "";
        }
    }

    function login($username = null, $oauthid = null, $tpsocialreference = null, $rememberme = false, $clientid = null, $confirmoperationRequest = null) {
        $this->LOG_FUNCTION = "login";
        try {
            if (empty($username)) {
                DelegateUtility::paramsNull($this, "ERROR_USER_NOT_FOUND");
                return "";
            }

            parent::startTransaction();

            // chiudo tutte le operazioni di login ancora aperte
            $confirmoperationUI = new ConfirmoperationUI();
            $confirmoperationUI->transactional = true;
            $flgClose = $confirmoperationUI->closeAll("CODELOGIN");
            if (!$flgClose) {
                parent::rollbackTransaction();
                parent::mappingDelegate($confirmoperationUI);
                return "";
            }

            // effettuo login
            $userBS = new UserBS();
            $userBS->acceptNull = true;
            $userBS->addVirtualField("completename");
            $userBS->addCondition("username", $username);
            $user = $userBS->unique();

            if (empty($user)) {
                DelegateUtility::paramsNull($this, "ERROR_USER_NOT_FOUND");
                return "";
            }

            $useroauthsocialBS = new UseroauthsocialBS();
            $useroauthsocialBS->addCondition("id_user", $user['User']['id']);
            if (!empty($tpsocialreference)) {
                $useroauthsocialBS->addCondition("tpsocialreference", $tpsocialreference);
            }
            $useroauthsocialBS->addCondition("oauthid", $oauthid);
            $useroauthsocial = $useroauthsocialBS->unique();

            if (empty($useroauthsocial)) {
                DelegateUtility::paramsNull($this, "ERROR_USER_NOT_FOUND");
                return "";
            }

            // verifico che già esista un token valido
            $authtoken = CakeSession::read($username);
            if (!empty($authtoken) && ApploginUtility::verifySession($authtoken)) {

                if (!ApploginUtility::verifyClient($authtoken)) {
                    parent::rollbackTransaction();
                    $ui = new UserUI();
                    $ui->logout($username);
                    DelegateUtility::errorInternal($this, "OBJECT_NULL", "ERROR_SESSION_USERNAME", null, "ERROR_SESSION_LOGIN_NOT_VALID", array(
                        $username,
                    ));
                    return "";
                }

                $this->ok();
                return $authtoken;
            }

            // genero token
            $payload = array(
                "username" => $user['User']['username'],
                "name" => $user['User']['name'],
                "surname" => $user['User']['surname'],
                "sex" => $user['User']['sex'],
                "born" => $user['User']['born'],
                "session" => CakeSession::id(),
            );
            $authtoken = ApploginUtility::buildToken($clientid, json_encode($payload), $rememberme);
            $MESSAGE_OK = null;
            if (empty($confirmoperationRequest)) {
                ApploginUtility::memoAuthtoken($username, $authtoken);

                // registro l'operazione
                $userreportBS = new UserreportBS();
                $id_userreport = $userreportBS->storeOperation($user['User']['id'], "LOGIN", TranslatorUtility::__translate_args("INFO_USER_LOGIN", array(
                    $username,
                    $authtoken,
                ), $this->localefile));

                DelegateUtility::logMessage($this, MessageUtility::messageGeneric("LOGIN", "INFO_USER_LOGIN", $this->localefile, array(
                    $username,
                    $authtoken,
                )));
            } else {
                // INVIO IL CODICE DI VERIFICA
                $codEmail = FileUtility::uuid_number();
                $codSms = FileUtility::uuid_number();
                $i18n = array(
                    "mail_subject" => TranslatorUtility::__translate("INFO_CONFIRMOPERATION_CODELOGIN_SUBJECT_MAIL", "confirmoperation"),
                    "mail_message" => TranslatorUtility::__translate_args("INFO_CONFIRMOPERATION_CODELOGIN_MESSAGE_MAIL", array(
                        $codEmail,
                    ), "confirmoperation"),
                    "mail_code" => $codEmail,
                    "phone_message" => TranslatorUtility::__translate_args("INFO_CONFIRMOPERATION_CODELOGIN_MESSAGE_PHONE", array(
                        $codSms,
                    ), "confirmoperation"),
                    "phone_code" => $codSms,
                );
                UserUtility::sendConfirmationcodeToUser($this->json, $user, "CODELOGIN", $authtoken, $i18n, $confirmoperationRequest);
                $MESSAGE_OK = TranslatorUtility::__translate("INFO_SEND_CODE_LOGIN", $this->localefile);
            }

            parent::commitTransaction();

            $this->ok($MESSAGE_OK);
            return $authtoken;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_USER_NOT_FOUND");
            return "";
        }
    }

}
