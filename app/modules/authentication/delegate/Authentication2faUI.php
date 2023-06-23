<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("ConnectionManager", "Model");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("Defaults", "Config/system");
App::uses("FileUtility", "modules/coreutils/utility");
App::uses("DateUtility", "modules/coreutils/utility");

class Authentication2faUI extends AppGenericUI {

    function __construct() {
        parent::__construct("Authenticationf2aUI");
        $this->localefile = "authenticationf2a";
        $this->obj = array();
    }

    function generate($key = null) {
        $this->LOG_FUNCTION = "generate";
        try {
            if (empty($key)) {
                $key = FileUtility::uuid_short();
            }
            $TIME_WAIT = Defaults::get("time2fa_s");
            $LAST_COD = null;
            $LAST_TIME = null;
            $TODAY = DateUtility::getCurrentTime();

            if (CakeSession::check("{$key}_LAST_F2A_COD") && CakeSession::check("{$key}_LAST_F2A_TIME")) {
                $LAST_COD = CakeSession::read("{$key}_LAST_F2A_COD");
                $LAST_TIME = CakeSession::read("{$key}_LAST_F2A_TIME");
                if (DateUtility::diffDate($LAST_TIME, $TODAY, 's') > $TIME_WAIT) {
                    $LAST_COD = FileUtility::uuid_number();
                    $LAST_TIME = $TODAY;
                }
            } else {
                $LAST_COD = FileUtility::uuid_number();
                $LAST_TIME = $TODAY;
            }

            $obj = array(
                "lastCod" => $LAST_COD,
                "lastTime" => $LAST_TIME,
                "key" => $key,
                "timeWait" => $TIME_WAIT
            );

            CakeSession::write("{$key}_LAST_F2A_COD", $LAST_COD);
            CakeSession::write("{$key}_LAST_F2A_TIME", $LAST_TIME);

            $this->ok();
            return $this->json ? json_encode($obj) : $obj;
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_AF2A_GENERATE");
            return "";
        }
    }

    function check($key = null, $cod = null) {
        $this->LOG_FUNCTION = "check";
        try {
            if (empty($cod) || empty($key)) {
                DelegateUtility::paramsNull($this, "ERROR_AF2A_CHECK");
                return false;
            }
            $TIME_WAIT = Defaults::get("time2fa_s");
            $LAST_COD = null;
            $LAST_TIME = null;
            $TODAY = DateUtility::getCurrentTime();
            $valid = false;

            if (CakeSession::check("{$key}_LAST_F2A_COD") && CakeSession::check("{$key}_LAST_F2A_TIME")) {
                $LAST_COD = CakeSession::read("{$key}_LAST_F2A_COD");
                $LAST_TIME = CakeSession::read("{$key}_LAST_F2A_TIME");
                if (DateUtility::diffDate($LAST_TIME, $TODAY, 's') <= $TIME_WAIT && $LAST_COD == $cod) {
                    $valid = true;
                }
            }

            $this->ok();
            return $valid;
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_AF2A_CHECK");
            return false;
        }
    }

}
