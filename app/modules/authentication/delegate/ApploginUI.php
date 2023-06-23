<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("UserprofileBS", "modules/authentication/business");
App::uses("UserBS", "modules/authentication/business");
App::uses("ProfileBS", "modules/authentication/business");

class ApploginUI extends AppGenericUI {
    public $json = false;

    function __construct() {
        parent::__construct("ApploginUI");
        $this->localefile = "applogin";
    }

    function setTokenError($exception = null, $expired = false) {
        $this->LOG_FUNCTION = "setTokenError";
        DelegateUtility::eccezione($exception, $this, $expired ? "ERROR_SESSION_EXPIRED" : "ERROR_LOGIN_TOKEN");
        return "";
    }

    function setTokenValid() {
        $this->LOG_FUNCTION = "setTokenValid";
        $this->ok("Il token è valido");
        return "";
    }

    function memoProfileDefault($username) {
        $this->LOG_FUNCTION = "memoProfileDefault";
        try {
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
            $userprofileBS->addCondition("flgdefault", 1);
            $userprofile = $userprofileBS->unique();

            if (empty($userprofile)) {
                throw new Exception("userprofile default for username {$username} not found");
            }

            $profileBS = new ProfileBS();
            $profileBS->acceptNull = true;
            $profile = $profileBS->unique($userprofile['Userprofile']['profile']);

            if (empty($profile)) {
                throw new Exception("Profile for username {$username} not found");
            }

            CakeSession::write($username . "_PROFILE", $profile['Profile']['cod']);

            $this->ok();
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }
}