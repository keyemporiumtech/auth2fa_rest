<?php

App::uses("AppController", "Controller");
App::uses("Codes", "Config/system");
App::uses("Enables", "Config/system");
App::uses("LogUtility", "modules/coreutils/utility");
App::uses("ArrayUtility", "modules/coreutils/utility");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("TranslatorUtility", "modules/cakeutils/utility");
App::uses("ApploginUtility", "modules/authentication/utility");
App::uses("AppactivityUtility", "modules/authentication/utility");
App::uses("ActivityUtility", "modules/authentication/utility");
App::uses("UserUtility", "modules/authentication/utility");
// business
App::uses("ProfileBS", "modules/authentication/business");
App::uses("UserBS", "modules/authentication/business");
App::uses("UserprofileBS", "modules/authentication/business");
App::uses("ProfilepermissionBS", "modules/authentication/business");
App::uses("ActivityBS", "modules/authentication/business");
App::uses("ActivityprofileBS", "modules/authentication/business");
App::uses("UserrelationBS", "modules/authentication/business");
App::uses("UserrelationpermissionBS", "modules/authentication/business");

class PermissionUtility {

    /* ----------------- user */
    static function checkpermissionsUI(CakeRequest $request, AppGenericUI $ui, $permissions = array(), $username = null, $cod_profile = null) {
        try {
            if (empty($username)) {
                $username = ApploginUtility::getUsernameLogged($request);
                PermissionUtility::logMessage("username di sistema {$username}");
            }
            if (empty($cod_profile)) {
                $cod_profile = ApploginUtility::getProfileLogged($username);
                PermissionUtility::logMessage("profilo di sistema {$cod_profile}");
            }
            if (empty($username) || empty($cod_profile)) {
                PermissionUtility::logMessage("utente non loggato");
                return false;
            }
            $flag = PermissionUtility::checkpermissions($username, $cod_profile, $permissions);
            if (!$flag) {
                DelegateUtility::errorInternal(
                    $ui,
                    "USER_NOT_PERMISSIONS",
                    "ERROR_USER_NOT_PERMISSION",
                    null,
                    "ERROR_USER_NOT_PERMISSION_DETAIL",
                    array($username, ArrayUtility::toPrintString($permissions, false)),
                    "user",
                    "user");
            }
            return $flag;
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $ui, "ERROR_USER_NOT_PERMISSION", "user");
            return false;
        }
    }

    /**
     * Controlla se un profilo utente ha determinati permessi
     * @param string $username nome utente
     * @param string $cod_profile profilo utente
     * @param string[] $permissions lista di permessi da controllare
     * @return boolean true se almeno un permesso di quelli richiesti in $permissions è stato trovato per il profilo utente
     */
    static function checkpermissions($username, $cod_profile, $permissions = array()) {
        try {
            $userBS = new UserBS();
            $userBS->acceptNull = true;
            $userBS->addCondition("username", $username);
            $user = $userBS->unique();

            if (empty($user)) {
                PermissionUtility::logMessage("[ERROR] => L'utente {$username} non esiste");
                throw new Exception(TranslatorUtility::__translate("ERROR_USER_NOT_FOUND", "user"), Codes::get("PERMISSION_USER_NOT_FOUND"));
            }

            $profileBS = new ProfileBS();
            $profileBS->acceptNull = true;
            $profileBS->addCondition("cod", $cod_profile);
            $profile = $profileBS->unique();

            if (empty($profile)) {
                PermissionUtility::logMessage("[ERROR] => Il profilo {$cod_profile} non esiste");
                throw new Exception(TranslatorUtility::__translate("ERROR_PROFILE_NOT_FOUND", "profile"), Codes::get("PERMISSION_PROFILE_NOT_FOUND"));
            }

            $userprofileBS = new UserprofileBS();
            $userprofileBS->acceptNull = true;
            $userprofileBS->addCondition("user", $user['User']['id']);
            $userprofileBS->addCondition("profile", $profile['Profile']['id']);
            $userprofile = $userprofileBS->unique();

            if (empty($userprofile)) {
                PermissionUtility::logMessage("[ERROR] => L'utente {$username} non è associato al profilo {$cod_profile} nella tabella userprofiles");
                throw new Exception(TranslatorUtility::__translate("ERROR_USERPROFILE_NOT_FOUND", "userprofile"), Codes::get("PERMISSION_USERPROFILE_NOT_FOUND"));
            }

            if (ArrayUtility::contains($permissions, $profile['Profile']['cod'])) {
                PermissionUtility::logMessage("Ruolo {$profile['Profile']['cod']} abilitato");
                return true;
            }

            $profilepermissionBS = new ProfilepermissionBS();
            $profilepermissionBS->acceptNull = true;
            $profilepermissionBS->addBelongsTo("permission_fk");
            $profilepermissionBS->addCondition("permission_fk.cod", $permissions);
            $profilepermissionBS->addCondition("profile", $profile['Profile']['id']);
            $num_permissions = $profilepermissionBS->count();

            PermissionUtility::logMessage("permessi trovati in profilepermissions: {$num_permissions}");

            return $num_permissions > 0;

        } catch (Exception $e) {
            throw ($e);
        }
    }

    static function checkGrantForUser(CakeRequest $request, $usernameMaster = null, $usernameRelated = null, $permissions = array()) {
        if (empty($usernameMaster)) {
            $usernameMaster = ApploginUtility::getUsernameLogged($request);
            PermissionUtility::logMessage("username di sistema {$usernameMaster}");
        }
        if (empty($usernameMaster)) {
            PermissionUtility::logMessage("utente non loggato");
            return false;
        }
        if (empty($usernameRelated)) {
            PermissionUtility::logMessage("username di relazione non passato come parametro");
            return false;
        }
        // informazioni personali
        if ($usernameMaster == $usernameRelated) {
            PermissionUtility::logMessage("sto cercando le mie informazioni");
            return true;
        }
        // controlla la tabella delle relazioni
        $id_userrelation = null;
        $direction = 0;
        $userrelationBS = new UserrelationBS();
        $userrelationBS->acceptNull = true;
        $userrelationBS->addBelongsTo("user1_fk");
        $userrelationBS->addBelongsTo("user2_fk");
        $userrelationBS->addCondition("user1_fk.username", $usernameMaster);
        $userrelationBS->addCondition("user2_fk.username", $usernameRelated);
        $userrelation = $userrelationBS->unique();
        if (!empty($userrelation)) {
            $id_userrelation = $userrelation['Userrelation']['id'];
            $direction = 1;
        }

        if (empty($id_userrelation)) {
            $userrelationBS = new UserrelationBS();
            $userrelationBS->acceptNull = true;
            $userrelationBS->addBelongsTo("user1_fk");
            $userrelationBS->addBelongsTo("user2_fk");
            $userrelationBS->addCondition("user1_fk.username", $usernameRelated);
            $userrelationBS->addCondition("user2_fk.username", $usernameMaster);
            $userrelation = $userrelationBS->unique();
            if (!empty($userrelation)) {
                $id_userrelation = $userrelation['Userrelation']['id'];
                $direction = 2;
            }
        }

        if (empty($id_userrelation)) {
            PermissionUtility::logMessage("nessuna relazione presente nella tabella userrelations tra {$usernameMaster} e {$usernameRelated}");
            return false;
        }

        $userrelationpermissionBS = new UserrelationpermissionBS();
        $userrelationpermissionBS->acceptNull = true;
        $userrelationpermissionBS->addBelongsTo("permission_fk");
        $userrelationpermissionBS->addCondition("userrelation", $id_userrelation);
        $userrelationpermissionBS->addCondition("direction", $direction);
        $userrelationpermissions = $userrelationpermissionBS->all();

        if (ArrayUtility::isEmpty($userrelationpermissions)) {
            PermissionUtility::logMessage("nessuna permesso presente nella tabella userrelationpermissions tra {$usernameMaster} e {$usernameRelated}");
            return false;
        }

        PermissionUtility::logMessage("trovati " . count($userrelationpermissions) . " permessi tra {$usernameMaster} e {$usernameRelated}");

        foreach ($userrelationpermissions as $userrelationpermission) {
            $permission = $userrelationpermission['Userrelationpermission']['permission_fk'];
            if (ArrayUtility::contains($permissions, $permission['cod'])) {
                return true;
            }
        }

        return false;
    }

    /* ----------------- activity */
    static function checkpermissionsActivityUI(CakeRequest $request, AppGenericUI $ui, $permissions = array(), $username = null, $cod_profile = null, $id_activity = null, $piva = null) {
        try {
            if (empty($username)) {
                $username = ApploginUtility::getUsernameLogged($request);
                PermissionUtility::logMessage("username di sistema {$username}");
            }
            if (empty($cod_profile)) {
                $cod_profile = ApploginUtility::getProfileLogged($username);
                PermissionUtility::logMessage("profilo di sistema {$cod_profile}");
            }
            if (empty($piva)) {
                $piva = AppactivityUtility::getActivityLogged($username);
                PermissionUtility::logMessage("activity di sistema {$piva}");
            }

            if (empty($username) || empty($cod_profile)) {
                PermissionUtility::logMessage("utente non loggato");
                return false;
            }
            if (empty($piva)) {
                PermissionUtility::logMessage("utente non loggato con profilo aziendale");
                return false;
            }

            $flag = PermissionUtility::checkpermissionsActivity($username, $cod_profile, $permissions, $id_activity, $piva);
            if (!$flag) {
                DelegateUtility::errorInternal(
                    $ui,
                    "USER_NOT_PERMISSIONS",
                    "ERROR_USER_NOT_PERMISSION",
                    null,
                    "ERROR_USER_NOT_PERMISSION_DETAIL",
                    array($username, ArrayUtility::toPrintString($permissions, false)),
                    "user",
                    "user");
            }
            return $flag;
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $ui, "ERROR_USER_NOT_PERMISSION", "user");
            return false;
        }
    }
    /**
     * Controlla se un profilo aziendale ha determinati permessi
     * @param string $username nome utente
     * @param string $cod_profile profilo utente
     * @param string[] $permissions lista di permessi da controllare
     * @param string $id_activity id azienda
     * @param string $piva partita iva azienda
     * @return boolean true se almeno un permesso di quelli richiesti in $permissions è stato trovato per il profilo aziendale
     */
    static function checkpermissionsActivity($username, $cod_profile, $permissions = array(), $id_activity = null, $piva = null) {
        try {
            $userBS = new UserBS();
            $userBS->acceptNull = true;
            $userBS->addCondition("username", $username);
            $user = $userBS->unique();

            if (empty($user)) {
                PermissionUtility::logMessage("[ERROR] => L'utente {$username} non esiste");
                throw new Exception(TranslatorUtility::__translate("ERROR_USER_NOT_FOUND", "user"), Codes::get("PERMISSION_USER_NOT_FOUND"));
            }

            $profileBS = new ProfileBS();
            $profileBS->acceptNull = true;
            $profileBS->addCondition("cod", $cod_profile);
            $profile = $profileBS->unique();

            if (empty($profile)) {
                PermissionUtility::logMessage("[ERROR] => Il profilo {$cod_profile} non esiste");
                throw new Exception(TranslatorUtility::__translate("ERROR_PROFILE_NOT_FOUND", "profile"), Codes::get("PERMISSION_PROFILE_NOT_FOUND"));
            }

            $userprofileBS = new UserprofileBS();
            $userprofileBS->acceptNull = true;
            $userprofileBS->addCondition("user", $user['User']['id']);
            $userprofileBS->addCondition("profile", $profile['Profile']['id']);
            $userprofile = $userprofileBS->unique();

            if (empty($userprofile)) {
                PermissionUtility::logMessage("[ERROR] => L'utente {$username} non è associato al profilo {$cod_profile} nella tabella userprofiles");
                throw new Exception(TranslatorUtility::__translate("ERROR_USERPROFILE_NOT_FOUND", "userprofile"), Codes::get("PERMISSION_USERPROFILE_NOT_FOUND"));
            }

            if (ArrayUtility::contains($permissions, $profile['Profile']['cod'])) {
                PermissionUtility::logMessage("Ruolo {$profile['Profile']['cod']} abilitato");
                return true;
            }

            $activityBS = new ActivityBS();
            $activityBS->acceptNull = true;
            if (!empty($piva)) {
                $activityBS->addCondition("piva", $piva);
            }
            $activity = $activityBS->unique($id_activity);

            if (empty($activity)) {
                PermissionUtility::logMessage("[ERROR] => L'attività {$activity['Activity']['piva']} non esiste");
                throw new Exception(TranslatorUtility::__translate("ERROR_ACTIVITY_NOT_FOUND", "activity"), Codes::get("PERMISSION_ACTIVITY_NOT_FOUND"));
            }

            $activityprofileBS = new ActivityprofileBS();
            $activityprofileBS->acceptNull = true;
            $activityprofileBS->addCondition("activity", $activity['Activity']['id']);
            $activityprofileBS->addCondition("profile", $profile['Profile']['id']);
            $activityprofile = $activityprofileBS->unique();

            if (empty($activityprofile)) {
                PermissionUtility::logMessage("[ERROR] => L'attività {$activity['Activity']['piva']} non è associato al profilo {$cod_profile} nella tabella activityprofiles");
                throw new Exception(TranslatorUtility::__translate("ERROR_ACTIVITYPROFILE_NOT_FOUND", "activityprofile"), Codes::get("PERMISSION_ACTIVITYPROFILE_NOT_FOUND"));
            }

            $userprofileBS = new UserprofileBS();
            $userprofileBS->acceptNull = true;
            $userprofileBS->addCondition("user", $user['User']['id']);
            $userprofileBS->addCondition("activity", $activity['Activity']['id']);
            $userprofileBS->addCondition("profile", $profile['Profile']['id']);
            $userprofile = $userprofileBS->unique();

            if (empty($userprofile)) {
                PermissionUtility::logMessage("[ERROR] => L'attività {$activity['Activity']['piva']} non è associato al profilo {$cod_profile} dell'utente {$username} nella tabella userprofiles");
                throw new Exception(TranslatorUtility::__translate("ERROR_USERPROFILE_NOT_FOUND", "userprofile"), Codes::get("PERMISSION_USERPROFILE_NOT_FOUND"));
            }

            $profilepermissionBS = new ProfilepermissionBS();
            $profilepermissionBS->acceptNull = true;
            $profilepermissionBS->addBelongsTo("permission_fk");
            $profilepermissionBS->addCondition("permission_fk.cod", $permissions);
            $profilepermissionBS->addCondition("profile", $profile['Profile']['id']);
            $num_permissions = $profilepermissionBS->count();

            PermissionUtility::logMessage("permessi trovati in profilepermissions: {$num_permissions}");

            return $num_permissions > 0;

        } catch (Exception $e) {
            throw ($e);
        }
    }

    static function checkGrantForUserActivity(CakeRequest $request, $usernameMaster = null, $usernameRelated = null, $pivaRelated = null) {
        if (empty($usernameMaster)) {
            $usernameMaster = ApploginUtility::getUsernameLogged($request);
            PermissionUtility::logMessage("username di sistema {$usernameMaster}");
        }
        if (empty($usernameMaster)) {
            PermissionUtility::logMessage("utente non loggato");
            return false;
        }
        if (empty($usernameRelated)) {
            PermissionUtility::logMessage("username di relazione non passato come parametro");
            return false;
        }
        // informazioni personali
        if ($usernameMaster == $usernameRelated) {
            PermissionUtility::logMessage("sto cercando le mie informazioni");
            return true;
        }

        if (empty($pivaRelated)) {
            $pivaRelated = AppactivityUtility::getActivityLogged($usernameMaster);
            PermissionUtility::logMessage("azienda di sistema {$pivaRelated}");
        }
        if (empty($pivaRelated)) {
            PermissionUtility::logMessage("azienda non loggata o non fornita");
            return false;
        }

        // controlla l'utente abbia un profilo sull'azienda
        $userprofileBS = new UserprofileBS();
        $userprofileBS->acceptNull = true;
        $userprofileBS->addBelongsTo("user_fk");
        $userprofileBS->addBelongsTo("activity_fk");
        $userprofileBS->addCondition("user_fk.username", $usernameRelated);
        $userprofileBS->addCondition("activity_fk.piva", $pivaRelated);
        $num = $userprofileBS->count();

        return $num > 0;
    }

    static function checkGrantForActivity(CakeRequest $request, $usernameMaster = null, $pivaRelated = null, $permissions = array()) {
        if (empty($usernameMaster)) {
            $usernameMaster = ApploginUtility::getUsernameLogged($request);
            PermissionUtility::logMessage("username di sistema {$usernameMaster}");
        }
        if (empty($usernameMaster)) {
            PermissionUtility::logMessage("utente non loggato");
            return false;
        }
        if (empty($pivaRelated)) {
            PermissionUtility::logMessage("partita iva di relazione non passata come parametro");
            return false;
        }

        $pivaLogged = AppactivityUtility::getActivityLogged($usernameMaster);
        if ($pivaRelated == $pivaLogged) {
            PermissionUtility::logMessage("sto cercando le informazioni della mia azienda");
            return true;
        }

        // controlla la tabella delle relazioni
        $id_activityrelation = null;
        $activityrelationBS = new UserrelationBS();
        $activityrelationBS->acceptNull = true;
        $activityrelationBS->addBelongsTo("user_fk");
        $activityrelationBS->addBelongsTo("activity_fk");
        $activityrelationBS->addCondition("user_fk.username", $usernameMaster);
        $activityrelationBS->addCondition("activity_fk.piva", $pivaRelated);
        $activityrelation = $activityrelationBS->unique();
        if (!empty($activityrelation)) {
            $id_activityrelation = $activityrelation['Activityrelation']['id'];
        }

        if (empty($id_activityrelation)) {
            PermissionUtility::logMessage("nessuna relazione presente nella tabella activityrelations tra {$usernameMaster} e {$pivaRelated}");
            return false;
        }

        $activityrelationpermissionBS = new UserrelationpermissionBS();
        $activityrelationpermissionBS->acceptNull = true;
        $activityrelationpermissionBS->addBelongsTo("permission_fk");
        $activityrelationpermissionBS->addCondition("activityrelation", $id_activityrelation);
        $activityrelationpermissions = $activityrelationpermissionBS->all();

        if (ArrayUtility::isEmpty($activityrelationpermissions)) {
            PermissionUtility::logMessage("nessuna permesso presente nella tabella activityrelationpermissions tra {$usernameMaster} e {$pivaRelated}");
            return false;
        }

        PermissionUtility::logMessage("trovati " . count($activityrelationpermissions) . " permessi tra {$usernameMaster} e {$pivaRelated}");

        foreach ($activityrelationpermissions as $activityrelationpermission) {
            $permission = $activityrelationpermission['Activityrelationpermission']['permission_fk'];
            if (ArrayUtility::contains($permissions, $permission['cod'])) {
                return true;
            }
        }

        return false;
    }

    // --------------- LOG

    static function logMessage($message, $cod = null) {
        if (Enables::get("log_permissions")) {
            empty($cod) ? LogUtility::simpleWrite("permissions", $message, false, "") : LogUtility::write("permissions", $cod, $message, false, "");
        }
    }

}
