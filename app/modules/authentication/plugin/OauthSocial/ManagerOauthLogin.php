<?php
App::uses("Codes", "Config/system");
App::uses("ArrayUtility", "modules/coreutils/utility");
App::uses("TranslatorUtility", "modules/cakeutils/utility");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("FileUtility", "modules/coreutils/utility");
// business delegates
App::uses("AttachmentUtility", "modules/resources/utility");
App::uses("EnumAttachmentType", "modules/resources/config");
App::uses("EnumContactreferenceType", "modules/authentication/config");
App::uses("UseroauthsocialBS", "modules/authentication/business");
App::uses("UserBS", "modules/authentication/business");
App::uses("AddressBS", "modules/localesystem/business");
App::uses("ContactreferenceBS", "modules/authentication/business");
App::uses("UserreferenceBS", "modules/authentication/business");
App::uses("UserUI", "modules/authentication/delegate");
App::uses("UseraddressUI", "modules/authentication/delegate");
App::uses("UserreferenceUI", "modules/authentication/delegate");
App::uses("UserattachmentUI", "modules/authentication/delegate");

class ManagerOauthLogin {

    static function check($json, $socialuser, $tpsocialreference, $id_user = null) {
        try {
            $objSocialUser = DelegateUtility::getObj($json, $socialuser); // SocialUser
            $userUI = new UserUI();

            if (!property_exists($objSocialUser, "user") || empty($objSocialUser->user)) {
                throw new Exception(ManagerOauthLogin::translate("ERROR_USER_EMPTY"), Codes::get("OAUTH_SOCIAL_CHECK_ERROR"));
            }
            $objUser = $objSocialUser->user;

            // useroauthsocial
            if (!property_exists($objSocialUser, "id") || empty($objSocialUser->id)) {
                throw new Exception(ManagerOauthLogin::translate("ERROR_OAUTH_ID_EMPTY"), Codes::get("OAUTH_SOCIAL_CHECK_ERROR"));
            }
            $useroauthsocialBS = new UseroauthsocialBS();
            $useroauthsocialBS->acceptNull = true;
            if (!empty($id_user)) {
                $useroauthsocialBS->addCondition("user", $id_user);
            }
            if (!empty($tpsocialreference)) {
                $useroauthsocialBS->addCondition("tpsocialreference", $tpsocialreference);
            }
            $useroauthsocialBS->addCondition("oauthid", $objSocialUser->id);
            $useroauthsocial = $useroauthsocialBS->unique();
            if (!empty($useroauthsocial)) {
                $properties = array("flgDecrypt" => true);
                $userUI->json = $json;
                $userUI->properties = $json ? json_encode($properties, true) : $properties;
                return $userUI->get($useroauthsocial['Useroauthsocial']['user']);
            }

            // user by email
            if (!property_exists($objUser, "username") || empty($objUser->username)) {
                throw new Exception(ManagerOauthLogin::translate("ERROR_USERNAME_EMPTY"), Codes::get("OAUTH_SOCIAL_CHECK_ERROR"));
            }
            $userBS = new UserBS();
            $userBS->acceptNull = true;
            $userBS->addPropertyDao("flgDecrypt", true);
            $userBS->addCondition("username", $objUser->username);
            $user = $userBS->unique();
            if (!empty($user)) {
                $id_useroauthlogin = ManagerOauthLogin::saveOauth($user['User']['id'], $objSocialUser->id, $tpsocialreference);
                if (empty($id_useroauthlogin)) {
                    throw new Exception(ManagerOauthLogin::translate("ERROR_SAVE_OAUTH"), Codes::get("OAUTH_SOCIAL_CHECK_ERROR"));
                }
                return $json ? json_encode($user['User']) : $user;
            }

            $userreferenceBS = new UserreferenceBS();
            $userreferenceBS->acceptNull = true;
            $userreferenceBS->addBelongsTo("contactreference_fk");
            $userreferenceBS->addCondition("contactreference_fk.val", $objUser->username);
            $userreferenceBS->addCondition("contactreference_fk.tpcontactreference", EnumContactreferenceType::EMAIL);
            $userreferences = $userreferenceBS->all();

            if (!ArrayUtility::isEmpty($userreferences)) {
                $userreference = $userreferences[0];
                $id_useroauthlogin = ManagerOauthLogin::saveOauth($userreference['Userreference']['user'], $objSocialUser->id, $tpsocialreference);
                if (empty($id_useroauthlogin)) {
                    throw new Exception(ManagerOauthLogin::translate("ERROR_SAVE_OAUTH"), Codes::get("OAUTH_SOCIAL_CHECK_ERROR"));
                }
                $properties = array("flgDecrypt" => true);
                $userUI->json = $json;
                $userUI->properties = $json ? json_encode($properties, true) : $properties;
                return $userUI->get($userreference['Userreference']['user']);
            }

            // user by completename and born
            if (!property_exists($objUser, "name") || empty($objUser->name)) {
                throw new Exception(ManagerOauthLogin::translate("ERROR_NAME_EMPTY"), Codes::get("OAUTH_SOCIAL_CHECK_ERROR"));
            }
            if (!property_exists($objUser, "surname") || empty($objUser->surname)) {
                throw new Exception(ManagerOauthLogin::translate("ERROR_SURNAME_EMPTY"), Codes::get("OAUTH_SOCIAL_CHECK_ERROR"));
            }
            if (!property_exists($objUser, "born") || empty($objUser->born)) {
                throw new Exception(ManagerOauthLogin::translate("ERROR_BORN_EMPTY"), Codes::get("OAUTH_SOCIAL_CHECK_ERROR"));
            }
            $userBS = new UserBS();
            $userBS->acceptNull = true;
            $userBS->addPropertyDao("flgDecrypt", true);
            $userBS->addCondition("name", $objUser->name);
            $userBS->addCondition("surname", $objUser->surname);
            $userBS->addCondition("born", $objUser->born);
            $user = $userBS->unique();
            if (!empty($user)) {
                // l'email che è stata passata non è stata trovata, quindi la salvo
                $id_userreference = ManagerOauthLogin::saveEmail($objUser->username, $user['User']['id']);
                if (empty($id_userreference)) {
                    throw new Exception(ManagerOauthLogin::translate("ERROR_SAVE_EMAIL"), Codes::get("OAUTH_SOCIAL_CHECK_ERROR"));
                }
                $id_useroauthlogin = ManagerOauthLogin::saveOauth($user['User']['id'], $objSocialUser->id, $tpsocialreference);
                if (empty($id_useroauthlogin)) {
                    throw new Exception(ManagerOauthLogin::translate("ERROR_SAVE_OAUTH"), Codes::get("OAUTH_SOCIAL_CHECK_ERROR"));
                }
                return $json ? json_encode($user['User']) : $user;
            }

            // register
            return ManagerOauthLogin::register($json, $socialuser, $tpsocialreference);

        } catch (Exception $e) {
            throw ($e);
        }
    }

    static function register($json, $socialuser, $tpsocialreference) {
        try {
            $objSocialUser = DelegateUtility::getObj($json, $socialuser); // SocialUser
            if (!property_exists($objSocialUser, "user") || empty($objSocialUser->user)) {
                throw new Exception(ManagerOauthLogin::translate("ERROR_USER_EMPTY"), Codes::get("OAUTH_SOCIAL_REGISTER_ERROR"));
            }
            $objUser = $objSocialUser->user;

            $userBS = new UserBS();
            $userSave = $json ? DelegateUtility::mapEntityByJson(new UserBS(), json_encode($objUser), array(
                new ObjPropertyEntity("username", null, ""),
                new ObjPropertyEntity("password", null, FileUtility::password()),
                new ObjPropertyEntity("cf", null, ""),
                new ObjPropertyEntity("name", null, ""),
                new ObjPropertyEntity("surname", null, ""),
                new ObjPropertyEntity("sex", null, ""),
                new ObjPropertyEntity("born", null, ""),
                new ObjPropertyEntity("flgtest", null, 0),
            ), null, null) : $objUser;
            $id_user = $userBS->save($userSave);

            if (empty($id_user)) {
                throw new Exception("", Codes::get("OAUTH_SOCIAL_REGISTER_ERROR"));
            }

            if (property_exists($objSocialUser, "addresses") && !ArrayUtility::isEmpty($objSocialUser->addresses)) {
                foreach ($objSocialUser->addresses as $address) {
                    $useraddressUI = new UseraddressUI();
                    $useraddressUI->transactional = true;
                    $addressSave = $json ? DelegateUtility::mapEntityByJson(new AddressBS(), json_encode($address), array(
                        new ObjPropertyEntity("cod", null, FileUtility::uuid()),
                        new ObjPropertyEntity("street", null, ""),
                        new ObjPropertyEntity("number", null, ""),
                        new ObjPropertyEntity("zip", null, ""),
                        new ObjPropertyEntity("city", null, ""),
                        new ObjPropertyEntity("province", null, ""),
                        new ObjPropertyEntity("region", null, ""),
                        new ObjPropertyEntity("geo1", null, ""),
                        new ObjPropertyEntity("geo2", null, ""),
                        new ObjPropertyEntity("nation", null, 0),
                        new ObjPropertyEntity("cityid", null, 0),
                        new ObjPropertyEntity("tpaddress", null, 0),
                    ), null, null) : $address;
                    $id_useraddress = $useraddressUI->saveRelation($id_user, $addressSave, $addressSave['Address']['tpaddress']);
                    if (empty($id_useraddress)) {
                        throw new Exception(ManagerOauthLogin::translate("ERROR_SAVE_ADDRESS"), Codes::get("OAUTH_SOCIAL_CHECK_ERROR"));
                    }
                }
            }
            if (property_exists($objSocialUser, "phones") && !ArrayUtility::isEmpty($objSocialUser->phones)) {
                foreach ($objSocialUser->phones as $phone) {
                    $userreferenceUI = new UserreferenceUI();
                    $userreferenceUI->transactional = true;
                    $phoneSave = $json ? DelegateUtility::mapEntityByJson(new ContactreferenceBS(), json_encode($phone), array(
                        new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
                        new ObjPropertyEntity("val", null, ""),
                        new ObjPropertyEntity("description", null, ""),
                        new ObjPropertyEntity("tpcontactreference", null, 0),
                        new ObjPropertyEntity("tpsocialreference", null, 0),
                        new ObjPropertyEntity("prefix", null, ""),
                        new ObjPropertyEntity("flgused", null, 0),
                    ), null, null) : $phone;
                    $id_userreference = $userreferenceUI->saveRelation($id_user, $phoneSave, $phoneSave['Contactreference']['tpcontactreference']);
                    if (empty($id_userreference)) {
                        throw new Exception(ManagerOauthLogin::translate("ERROR_SAVE_PHONE"), Codes::get("OAUTH_SOCIAL_CHECK_ERROR"));
                    }
                }
            }
            if (property_exists($objSocialUser, "photoUrl") && !empty($objSocialUser->photoUrl)) {
                $attachment = AttachmentUtility::getObjByUrl($objSocialUser->photoUrl);
                $attachment['Attachment']['ext'] = "jpg";
                $attachment['Attachment']['name'] = "profile_" . $objSocialUser->id;
                $attachment['Attachment']['tpattachment'] = EnumAttachmentType::IMAGE;
                $userattachmentUI = new UserattachmentUI();
                $userattachmentUI->transactional = true;
                $id_userattachment = $userattachmentUI->saveRelation($id_user, $attachment, EnumAttachmentType::IMAGE, true);
                if (empty($id_userattachment)) {
                    throw new Exception(ManagerOauthLogin::translate("ERROR_SAVE_IMAGE"), Codes::get("OAUTH_SOCIAL_CHECK_ERROR"));
                }
            }

            $id_useroauthlogin = ManagerOauthLogin::saveOauth($id_user, $objSocialUser->id, $tpsocialreference);
            if (empty($id_useroauthlogin)) {
                throw new Exception(ManagerOauthLogin::translate("ERROR_SAVE_OAUTH"), Codes::get("OAUTH_SOCIAL_CHECK_ERROR"));
            }

            $userBS = new UserBS();
            $userBS->json = $json;
            $userBS->addPropertyDao("flgDecrypt", true);
            return $userBS->unique($id_user);

        } catch (Exception $e) {
            throw ($e);
        }
    }

    static function saveOauth($id_user, $oauthid, $tpsocialreference) {
        $useroauthsocialBS = new UseroauthsocialBS();
        $useroauthsocial = $useroauthsocialBS->instance();
        $useroauthsocial['Useroauthsocial']['cod'] = FileUtility::uuid_medium_unique();
        $useroauthsocial['Useroauthsocial']['user'] = $id_user;
        $useroauthsocial['Useroauthsocial']['oauthid'] = $oauthid;
        $useroauthsocial['Useroauthsocial']['tpsocialreference'] = $tpsocialreference;
        return $useroauthsocialBS->save($useroauthsocial);
    }

    static function saveEmail($email, $id_user) {
        $userreferenceUI = new UserreferenceUI();
        $userreferenceUI->transactional = true;

        $contactreferenceBS = new ContactreferenceBS();
        $contactreference = $contactreferenceBS->instance();
        $contactreference['Contactreference']['cod'] = FileUtility::uuid_medium_unique();
        $contactreference['Contactreference']['val'] = $email;
        $contactreference['Contactreference']['tpcontactreference'] = EnumContactreferenceType::EMAIL;
        return $userreferenceUI->saveRelation($id_user, $contactreference, EnumContactreferenceType::EMAIL);
    }

    // utils
    static function translate($key, $args = null) {
        if (!empty($args)) {
            return TranslatorUtility::__translate_args($key, $args, "oauthlogin");
        }
        return TranslatorUtility::__translate($key, "oauthlogin");
    }
}