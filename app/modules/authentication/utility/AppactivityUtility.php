<?php
App::uses("AppactivityUI", "modules/authentication/delegate");

class AppactivityUtility {

    static function memoAuthtoken($username, $authtoken) {
        CakeSession::write($username, $authtoken);
    }

    static function memoActivity($username, $piva) {
        CakeSession::write($username . "_ACTIVITY", $piva);
    }

    static function memoActivityDefault($username) {
        if (empty(AppactivityUtility::getActivityLogged($username))) {
            $ui = new AppactivityUI();
            $ui->memoActivityDefault($username);
        }
    }

    static function getActivityLogged($username) {
        return CakeSession::read($username . "_ACTIVITY");
    }

    static function memoActivityByProfile($username, $cod_profile) {
        $ui = new AppactivityUI();
        $ui->memoActivityByProfile($username, $cod_profile);
    }
}