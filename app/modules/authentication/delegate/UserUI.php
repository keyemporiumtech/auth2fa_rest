<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("ConnectionManager", "Model");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("CryptingUtility", "modules/crypting/utility");
App::uses("EnumTypeCrypt", "modules/crypting/config");
App::uses("UserBS", "modules/authentication/business");
App::uses("UserreportBS", "modules/authentication/business");
App::uses("FileUtility", "modules/coreutils/utility");
App::uses("TranslatorUtility", "modules/cakeutils/utility");
App::uses("ApploginUtility", "modules/authentication/utility");
App::uses("UserUtility", "modules/authentication/utility");
App::uses("ConfirmoperationRequest", "modules/authentication/classes");
App::uses("ConfirmoperationUI", "modules/authentication/delegate");
App::uses("AttachmentUI", "modules/resources/delegate");
App::uses("UserattachmentUI", "modules/authentication/delegate");

class UserUI extends AppGenericUI {

    function __construct() {
        parent::__construct("UserUI");
        $this->localefile = "user";
        $this->obj = array(
            new ObjPropertyEntity("username", null, ""),
            new ObjPropertyEntity("password", null, FileUtility::password()),
            new ObjPropertyEntity("cf", null, ""),
            new ObjPropertyEntity("name", null, ""),
            new ObjPropertyEntity("surname", null, ""),
            new ObjPropertyEntity("sex", null, ""),
            new ObjPropertyEntity("born", null, ""),
            new ObjPropertyEntity("flgtest", null, 0),
        );
    }

    function get($id = null, $username = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($username)) {
                DelegateUtility::paramsNull($this, "ERROR_USER_NOT_FOUND");
                return "";
            }
            $userBS = new UserBS();
            $userBS->json = $this->json;
            parent::completeByJsonFkVf($userBS);
            if (!empty($username)) {
                $userBS->addCondition("username", $username);
            }
            $this->ok();
            return $userBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_USER_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $userBS = !empty($bs) ? $bs : new UserBS();
            $userBS->json = $this->json;
            parent::completeByJsonFkVf($userBS);
            parent::evalConditions($userBS, $conditions);
            parent::evalOrders($userBS, $orders);
            $users = $userBS->table($conditions, $orders, $paginate);
            parent::evalPagination($userBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($users);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($userIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $user = DelegateUtility::getEntityToSave(new UserBS(), $userIn, $this->obj);

            if (!empty($user)) {

                $userBS = new UserBS();
                $id_user = $userBS->save($user);
                parent::saveInGroup($userBS, $id_user);

                parent::commitTransaction();
                if (!empty($id_user)) {
                    $this->ok(TranslatorUtility::__translate("INFO_USER_SAVE", $this->localefile));
                    return $id_user;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_USER_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_USER_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_USER_SAVE");
            return 0;
        }
    }

    function edit($id, $userIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $user = DelegateUtility::getEntityToEdit(new UserBS(), $userIn, $this->obj, $id);

            if (!empty($user)) {
                $userBS = new UserBS();
                $userBS->addPropertyDao("avoidSavePassword", true);
                $id_user = $userBS->save($user);
                parent::saveInGroup($userBS, $id_user);
                parent::delInGroup($userBS, $id_user);

                parent::commitTransaction();
                if (!empty($id_user)) {
                    $this->ok(TranslatorUtility::__translate("INFO_USER_EDIT", $this->localefile));
                    return $id_user;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_USER_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_USER_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_USER_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $userBS = new UserBS();
                $userBS->delete($id);
                parent::delInGroup($userBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_USER_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_USER_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_USER_DELETE");
            return false;
        }
    }

    // --------------------- register
    function register($userIn, $imageIn = null, $phoneIn = null, $addressIn = null) {
        $this->LOG_FUNCTION = "register";
        try {
            parent::startTransaction();

            $id_user = $this->save($userIn);

            if (!empty($id_user)) {

            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_USER_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_USER_SAVE");
            return 0;
        }
    }

    // --------------------- SESSION
    function login($username = null, $password = null, $rememberme = false, $clientid = null, $confirmoperationRequest = null) {
        $this->LOG_FUNCTION = "login";
        try {
            if (empty($username) && empty($password)) {
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
            $user = $userBS->login($username, $password);

            if (empty($user)) {
                DelegateUtility::errorInternal($this, "OBJECT_NULL", "ERROR_USER_NOT_FOUND", null, "ERROR_USER_LOGIN_NOT_VALID", array(
                    $username,
                    CryptingUtility::encryptByType($password, EnumTypeCrypt::AES),
                ));
                DelegateUtility::logMessage($this, MessageUtility::messageGeneric(Codes::get("OBJECT_NULL"), "ERROR_USER_LOGIN_NOT_VALID", $this->localefile, array(
                    $username,
                    CryptingUtility::encryptByType($password, EnumTypeCrypt::AES),
                )));
                return "";
            }

            // verifico che già esista un token valido
            $authtoken = CakeSession::read($username);
            if (!empty($authtoken) && ApploginUtility::verifySession($authtoken)) {

                if (!ApploginUtility::verifyClient($authtoken)) {
                    parent::rollbackTransaction();
                    $this->logout($username);
                    DelegateUtility::errorInternal($this, "OBJECT_NULL", "ERROR_SESSION_USERNAME", null, "ERROR_SESSION_LOGIN_NOT_VALID", array(
                        $username,
                        CryptingUtility::encryptByType($password, EnumTypeCrypt::AES),
                    ));
                    return "";
                }

                // memorizzo il profilo di default se nessun profilo è già memorizzato
                ApploginUtility::memoProfileDefault($username);

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

            // memorizzo il profilo di default se nessun profilo è già memorizzato
            ApploginUtility::memoProfileDefault($username);

            parent::commitTransaction();

            $this->ok($MESSAGE_OK);
            return $authtoken;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_USER_NOT_FOUND");
            return "";
        }
    }

    function confirmLogin($username = null, $pin = null) {
        $this->LOG_FUNCTION = "confirmLogin";
        try {
            if (empty($username) && empty($pin)) {
                DelegateUtility::paramsNull($this, "ERROR_USER_NOT_FOUND");
                return "";
            }

            // verifico che già esista un token valido
            $authtoken = CakeSession::read($username);
            if (!empty($authtoken) && ApploginUtility::verifySession($authtoken)) {

                if (!ApploginUtility::verifyClient($authtoken)) {
                    parent::rollbackTransaction();
                    $this->logout($username);
                    DelegateUtility::errorInternal($this, "OBJECT_NULL", "ERROR_SESSION_USERNAME", null, "ERROR_SESSION_LOGIN_NOT_VALID", array(
                        $username,
                    ));
                    return "";
                }

                // memorizzo il profilo di default se nessun profilo è già memorizzato
                ApploginUtility::memoProfileDefault($username);

                $this->ok();
                return $authtoken;
            }

            parent::startTransaction();

            $confirmoperationUI = new ConfirmoperationUI();
            $confirmoperationUI->transactional = true;
            $authtoken = $confirmoperationUI->getOperationToken("CODELOGIN");
            if (empty($authtoken)) {
                parent::rollbackTransaction();
                parent::mappingDelegate($confirmoperationUI);
                return "";
            } elseif (!ApploginUtility::verifySession($authtoken)) {
                // chiudo tutte le operazioni di login ancora aperte
                $confirmoperationUI = new ConfirmoperationUI();
                $confirmoperationUI->transactional = true;
                $flgClose = $confirmoperationUI->closeAll("CODELOGIN");
                if (!$flgClose) {
                    parent::rollbackTransaction();
                    parent::mappingDelegate($confirmoperationUI);
                    return "";
                }
                parent::commitTransaction();
                DelegateUtility::errorInternal($this, "CONTROL_CODE_INVALID", "ERROR_CONFIRMLOGIN_TOKEN_EXPIRED", null, "ERROR_CONTROL_CODE_EXPIRED", array(
                    "CODELOGIN",
                ));
                return "";
            }

            $flgManage = $confirmoperationUI->checkAndManageOperationByCod("CODELOGIN", $pin);
            if (!$flgManage) {
                parent::rollbackTransaction();
                parent::mappingDelegate($confirmoperationUI);
                return "";
            }

            ApploginUtility::memoAuthtoken($username, $authtoken);

            // registro l'operazione
            $userBS = new UserBS();
            $userBS->addCondition("username", $username);
            $user = $userBS->unique();

            $userreportBS = new UserreportBS();
            $id_userreport = $userreportBS->storeOperation($user['User']['id'], "LOGIN", TranslatorUtility::__translate_args("INFO_USER_LOGIN", array(
                $username,
                $authtoken,
            ), $this->localefile));

            DelegateUtility::logMessage($this, MessageUtility::messageGeneric("LOGIN", "INFO_USER_LOGIN", $this->localefile, array(
                $username,
                $authtoken,
            )));

            // memorizzo il profilo di default se nessun profilo è già memorizzato
            ApploginUtility::memoProfileDefault($username);

            parent::commitTransaction();

            $this->ok();
            return $authtoken;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_USER_NOT_FOUND");
            return "";
        }
    }

    function checkSession($username = null, $token = null) {
        $this->LOG_FUNCTION = "checkSession";
        try {
            if (empty($username) && empty($token)) {
                DelegateUtility::paramsNull($this, "ERROR_USER_NOT_FOUND");
                return false;
            }

            $authtoken = CakeSession::read($username);
            if (empty($authtoken) || $authtoken != $token) {
                parent::startTransaction();

                // ricavo l'utente
                $userBS = new UserBS();
                $userBS->addCondition("username", $username);
                $user = $userBS->unique();

                // registro l'operazione
                $userreportBS = new UserreportBS();
                $id_userreport = $userreportBS->storeOperation($user['User']['id'], "SESSION_EXPIRED", TranslatorUtility::__translate_args("INFO_SESSION_EXPIRED", array(
                    $username,
                    empty($authtoken) ? "NULL" : $authtoken,
                ), $this->localefile));

                DelegateUtility::logMessage($this, MessageUtility::messageGeneric("SESSION_EXPIRED", "INFO_SESSION_EXPIRED", $this->localefile, array(
                    $username,
                    empty($authtoken) ? "NULL" : $authtoken,
                )));

                parent::commitTransaction();

                $expirationTime = ApploginUtility::getExpirationToken($token);
                $expirationDate = "";
                if (!empty($expirationTime)) {
                    $expirationDate = date('Y-m-d H:i:s', $expirationTime);
                }
                DelegateUtility::eccezione(new Exception("TOKEN LOGIN EXPIRED IN {$expirationDate}", Codes::get("TOKEN_LOGIN_EXPIRED")), $this, "ERROR_SESSION_EXPIRED", "applogin");
                return false;
            }

            $this->ok();
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_SESSION_EXPIRED", "applogin");
            return false;
        }
    }

    function logout($username = null) {
        $this->LOG_FUNCTION = "logout";
        try {
            $authtoken = CakeSession::read($username);

            if (empty($username) || empty($authtoken)) {
                DelegateUtility::paramsNull($this, "ERROR_USER_NOT_FOUND");
                return false;
            }

            parent::startTransaction();

            // ricavo l'utente
            $userBS = new UserBS();
            $userBS->addCondition("username", $username);
            $user = $userBS->unique();

            // registro l'operazione
            $userreportBS = new UserreportBS();
            $id_userreport = $userreportBS->storeOperation($user['User']['id'], "LOGOUT", TranslatorUtility::__translate_args("INFO_USER_LOGOUT", array(
                $username,
                empty($authtoken) ? "NULL" : $authtoken,
            ), $this->localefile));

            DelegateUtility::logMessage($this, MessageUtility::messageGeneric("LOGOUT", "INFO_USER_LOGOUT", $this->localefile, array(
                $username,
                empty($authtoken) ? "NULL" : $authtoken,
            )));

            // effettuo il logout
            CakeSession::delete($username);
            CakeSession::delete($username . "_PROFILE");

            parent::commitTransaction();

            $this->ok();
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_SESSION_EXPIRED", "applogin");
            return false;
        }
    }

    // --------------------------- MANAGE PASSWORD
    function restorePassword($id = null, $username = null) {
        $this->LOG_FUNCTION = "restorePassword";
        try {
            if (empty($id) && empty($username)) {
                DelegateUtility::paramsNull($this, "ERROR_RESTORE_PASSWORD");
                return false;
            }
            $userBS = new UserBS();
            $userBS->addPropertyDao("flgDecrypt", true);
            if (!empty($username)) {
                $userBS->addCondition("username", $username);
            }
            $user = $userBS->unique($id);

            parent::startTransaction();

            $oldPassword = $user['User']['passclean'];
            $newPassword = FileUtility::password();
            $user['User']['password'] = $newPassword;
            $userBS->save($user);

            // registro l'operazione
            $userreportBS = new UserreportBS();
            $id_userreport = $userreportBS->storeOperation($user['User']['id'], "RESTORE_PASSWORD", TranslatorUtility::__translate_args("INFO_USER_PASSWORD_RESTORED", array(
                $username,
                CryptingUtility::encryptByType($oldPassword, EnumTypeCrypt::AES),
                CryptingUtility::encryptByType($newPassword, EnumTypeCrypt::AES),
            ), $this->localefile));

            DelegateUtility::logMessage($this, MessageUtility::messageGeneric("RESTORE_PASSWORD", "INFO_USER_PASSWORD_RESTORED", $this->localefile, array(
                $username,
                CryptingUtility::encryptByType($oldPassword, EnumTypeCrypt::AES),
                CryptingUtility::encryptByType($newPassword, EnumTypeCrypt::AES),
            )));

            parent::commitTransaction();

            $this->ok(TranslatorUtility::__translate("INFO_RESTORE_PASSWORD", $this->localefile));
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_RESTORE_PASSWORD");
            return false;
        }
    }

    function remindPassword($id = null, $username = null) {
        $this->LOG_FUNCTION = "remindPassword";
        try {
            if (empty($id) && empty($username)) {
                DelegateUtility::paramsNull($this, "ERROR_REMIND_PASSWORD");
                return "";
            }
            $userBS = new UserBS();
            $userBS->addPropertyDao("flgDecrypt", true);
            if (!empty($username)) {
                $userBS->addCondition("username", $username);
            }
            $user = $userBS->unique($id);

            $this->ok();
            return $user['User']['passclean'];
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_REMIND_PASSWORD");
            return "";
        }
    }

    function changePassword($oldPassword, $newPassword, $id = null, $username = null) {
        $this->LOG_FUNCTION = "changePassword";
        try {
            if ((empty($id) && empty($username)) || empty($oldPassword) || empty($newPassword)) {
                DelegateUtility::paramsNull($this, "ERROR_CHANGE_PASSWORD");
                return false;
            }
            $userBS = new UserBS();
            $userBS->addPropertyDao("flgDecrypt", true);
            if (!empty($username)) {
                $userBS->addCondition("username", $username);
            }
            $user = $userBS->unique($id);

            if ($oldPassword != $user['User']['passclean']) {
                DelegateUtility::errorInternal($this, "OLD_PASSWORD_NOT_EQUAL", "ERROR_CHANGE_PASSWORD_OLD_PASSWORD", null, "ERROR_OLD_PASSWORD_NOT_EQUAL", array(
                    CryptingUtility::encryptByType($oldPassword, EnumTypeCrypt::AES),
                    CryptingUtility::encryptByType($user['User']['passclean'], EnumTypeCrypt::AES),
                ));
            }

            parent::startTransaction();

            $user['User']['password'] = $newPassword;
            $userBS->save($user);

            // registro l'operazione
            $userreportBS = new UserreportBS();
            $id_userreport = $userreportBS->storeOperation($user['User']['id'], "CHANGE_PASSWORD", TranslatorUtility::__translate_args("INFO_USER_PASSWORD_CHANGED", array(
                $username,
                CryptingUtility::encryptByType($oldPassword, EnumTypeCrypt::AES),
                CryptingUtility::encryptByType($newPassword, EnumTypeCrypt::AES),
            ), $this->localefile));

            DelegateUtility::logMessage($this, MessageUtility::messageGeneric("CHANGE_PASSWORD", "INFO_USER_PASSWORD_CHANGED", $this->localefile, array(
                $username,
                CryptingUtility::encryptByType($oldPassword, EnumTypeCrypt::AES),
                CryptingUtility::encryptByType($newPassword, EnumTypeCrypt::AES),
            )));

            parent::commitTransaction();

            $this->ok(TranslatorUtility::__translate("INFO_CHANGE_PASSWORD", $this->localefile));
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_CHANGE_PASSWORD");
            return false;
        }
    }

    // --------------------------- MANAGE PROFILE
    function getProfileLogged(CakeRequest $request) {
        $this->LOG_FUNCTION = "getProfileLogged";
        try {

            $username = ApploginUtility::getUsernameLogged($request);
            $this->ok();
            return ApploginUtility::getProfileLogged($username);
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PROFILE_NOT_FOUND");
            return false;
        }
    }
    function changeProfile($id = null, $username = null, $profile = null) {
        $this->LOG_FUNCTION = "changeProfile";
        try {
            if ((empty($id) && empty($username)) || empty($profile)) {
                DelegateUtility::paramsNull($this, "ERROR_CHANGE_PROFILE");
                return false;
            }

            $userBS = new UserBS();
            if (!empty($username)) {
                $userBS->addCondition("username", $username);
            }
            $user = $userBS->unique($id);

            if (!CakeSession::check($user['User']['username'])) {
                DelegateUtility::errorInternal($this, "USER_NOT_IN_SESSION", "ERROR_CHANGE_PROFILE", null, "ERROR_CHANGE_PROFILE_USER_NOT_SESSION", array(
                    $profile,
                    $user['User']['username'],
                ));
                return false;
            }

            ApploginUtility::memoProfile($user['User']['username'], $profile);

            $this->ok(TranslatorUtility::__translate_args("INFO_CHANGE_PROFILE", array($profile), $this->localefile));
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_CHANGE_PROFILE");
            return false;
        }
    }
}