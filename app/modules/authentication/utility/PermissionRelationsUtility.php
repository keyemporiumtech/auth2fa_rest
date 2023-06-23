<?php
App::uses("AppController", "Controller");
App::uses("Codes", "Config/system");
App::uses("ArrayUtility", "modules/coreutils/utility");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("TranslatorUtility", "modules/cakeutils/utility");
App::uses("ApploginUtility", "modules/authentication/utility");
// business
App::uses("ProfileBS", "modules/authentication/business");
App::uses("UserBS", "modules/authentication/business");
App::uses("UserprofileBS", "modules/authentication/business");
App::uses("ProfilepermissionBS", "modules/authentication/business");
App::uses("PermissionUtility", "modules/authentication/utility");

class PermissionRelationsUtility {

    static function onlyMeUser(CakeRequest $request, AppGenericUI $ui, $id_user = null, $username = null) {
        try {
            if (empty($username) && empty($id_user)) {
                PermissionUtility::logMessage("Parametri id_user e/o username non passati");
                PermissionRelationsUtility::setError($ui, "ERROR_USER_NOT_FOUND", null, "user");
                return false;
            }
            $userBS = new UserBS();
            $userBS->acceptNull = true;
            if (!empty($username)) {
                $userBS->addCondition("username", $username);
            }
            $user = $userBS->unique($id_user);

            if (empty($user)) {
                PermissionUtility::logMessage("Utente non trovato, parametri cercati : id_user={$id_user} - username={$username}");
                PermissionRelationsUtility::setError($ui, "ERROR_USER_NOT_FOUND", null, "user");
                return false;
            }

            $usernameLogged = ApploginUtility::getUsernameLogged($request);
            $check = $user['User']['username'] == $usernameLogged;
            if (!$check) {
                PermissionUtility::logMessage("Utente {$user['User']['username']} diverso dall'utente loggato {$usernameLogged}");
                PermissionRelationsUtility::setCheckError($ui, "WARNING_USER_ME", "permissioncheck");
            }
            return $check;

        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $ui, "ERROR_USER_NOT_PERMISSION", "user");
            return false;
        }
    }

    static function onlyMeUserprofile(CakeRequest $request, AppGenericUI $ui, $id_userprofile = null, $cod = null) {
        try {
            if (empty($cod) && empty($id_userprofile)) {
                PermissionUtility::logMessage("Parametri id_userprofile e/o cod non passati");
                PermissionRelationsUtility::setError($ui, "ERROR_USERPROFILE_NOT_FOUND", null, "userprofile");
                return false;
            }

            $userprofileBS = new UserprofileBS();
            $userprofileBS->acceptNull = true;
            if (!empty($cod)) {
                $userprofileBS->addCondition("cod", $cod);
            }
            $userprofile = $userprofileBS->unique($id_userprofile);

            if (empty($userprofile)) {
                PermissionUtility::logMessage("Profilo non trovato, parametri cercati : id_userprofile={$id_userprofile} - cod={$cod}");
                PermissionRelationsUtility::setError($ui, "ERROR_USERPROFILE_NOT_FOUND", null, "userprofile");
                return false;
            }

            $usernameLogged = ApploginUtility::getUsernameLogged($request);
            $user = $userprofile['Userprofile']['user_fk'];
            $check = $user['username'] == $usernameLogged;
            if (!$check) {
                PermissionUtility::logMessage("Utente {$user['username']} diverso dall'utente loggato {$usernameLogged}");
                PermissionRelationsUtility::setCheckError($ui, "WARNING_USERPROFILE_ME", "permissioncheck");
            }
            return $check;

        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $ui, "ERROR_USER_NOT_PERMISSION", "user");
            return false;
        }
    }

    // UTILS
    static function setError(AppGenericUI $ui, $cod, $file, $args = null) {
        DelegateUtility::errorInternal(
            $ui,
            "USER_NOT_PERMISSIONS",
            "ERROR_USER_NOT_PERMISSION",
            null,
            $cod,
            $args,
            "user",
            $file);
    }

    static function setCheckError(AppGenericUI $ui, $cod, $file, $args = null) {
        DelegateUtility::errorInternal(
            $ui,
            "USER_NOT_PERMISSIONS",
            $cod,
            $args,
            null,
            null,
            $file,
            null);
    }
}
