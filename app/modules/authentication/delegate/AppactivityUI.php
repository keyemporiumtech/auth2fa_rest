<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("ArrayUtility", "modules/coreutils/utility");
App::uses("UserprofileBS", "modules/authentication/business");
App::uses("ActivityuserBS", "modules/authentication/business");
App::uses("UserBS", "modules/authentication/business");
App::uses("ActivityBS", "modules/authentication/business");
App::uses("ProfileBS", "modules/authentication/business");
App::uses("AppactivityUtility", "modules/authentication/utility");

class AppactivityUI extends AppGenericUI {
    public $json = false;

    function __construct() {
        parent::__construct("AppactivityUI");
        $this->localefile = "";
    }

    function memoActivityDefault($username) {
        $this->LOG_FUNCTION = "memoActivityDefault";
        try {
            $id_activity = null;

            if (empty($username)) {
                throw new Exception("username empty");
            }

            $userBS = new UserBS();
            $userBS->acceptNull = true;
            $userBS->addCondition("username", $username);
            $user = $userBS->unique();

            if (empty($user)) {
                throw new Exception("user with username {$username} not found");
            }

            $userprofileBS = new UserprofileBS();
            $userprofileBS->acceptNull = true;
            $userprofileBS->addCondition("user", $user['User']['id']);
            $userprofileBS->addSimpleNOT("activity", 0);
            $userprofiles = $userprofileBS->all();

            if (!ArrayUtility::isEmpty($userprofiles) && count($userprofiles) == 1) {
                $userprofile = $userprofiles[0];
                $id_activity = $userprofile['Userprofile']['activity'];
            }

            if (empty($id_activity)) {
                $activityuserBS = new ActivityuserBS();
                $activityuserBS->acceptNull = true;
                $activityuserBS->addCondition("user", $user['User']['id']);
                $activityusers = $activityuserBS->all();

                if (!ArrayUtility::isEmpty($activityusers) && count($activityusers) == 1) {
                    $activityuser = $activityusers[0];
                    $id_activity = $activityuser['Activityuser']['activity'];
                }
            }
            if (!empty($id_activity)) {
                $activityBS = new ActivityBS();
                $activity = $activityBS->unique($id_activity);
                CakeSession::write($username . "_ACTIVITY", $activity['Activity']['piva']);
            }

            $this->ok();
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    function memoActivityByProfile($username, $cod_profile) {
        $this->LOG_FUNCTION = "memoActivityByProfile";
        try {
            $id_activity = null;

            if (empty($username)) {
                throw new Exception("username empty");
            }

            if (empty($cod_profile)) {
                throw new Exception("cod profile empty");
            }

            $userBS = new UserBS();
            $userBS->acceptNull = true;
            $userBS->addCondition("username", $username);
            $user = $userBS->unique();

            if (empty($user)) {
                throw new Exception("user with username {$username} not found");
            }

            $userprofileBS = new UserprofileBS();
            $userprofileBS->acceptNull = true;
            $userprofileBS->addBelongsTo("profile_fk");
            $userprofileBS->addCondition("user", $user['User']['id']);
            $userprofileBS->addCondition("profile_fk.cod", $cod_profile);
            $userprofile = $userprofileBS->unique();

            if (!empty($userprofile) && !empty($userprofile['Userprofile']['activity'])) {
                $id_activity = $userprofile['Userprofile']['activity'];
                $activityBS = new ActivityBS();
                $activity = $activityBS->unique($id_activity);
                CakeSession::write($username . "_ACTIVITY", $activity['Activity']['piva']);
            }

            $this->ok();
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }
}