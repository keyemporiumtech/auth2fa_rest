<?php
App::uses("AppController", "Controller");
App::uses("TokenUtility", "modules/cakeutils/utility");
App::uses("AppclientUtility", "modules/authentication/utility");
App::uses("AppactivityUtility", "modules/authentication/utility");
App::uses("FileUtility", "modules/coreutils/utility");
App::uses("ArrayUtility", "modules/coreutils/utility");

class ApputilController extends AppController {

    public function beforeFilter() {
        $this->json = true;
        parent::beforeFilter();
    }

    public function innertoken() {
        $this->set('data', TokenUtility::getInnerToken());
    }

    public function clienttoken($clientid = null) {
        parent::evalParam($clientid, 'id');
        $this->set('data', AppclientUtility::buildToken($clientid));
    }

    public function encodeattachment($content = null, $mimetype = null) {
        parent::evalParam($content, 'content');
        parent::evalParam($mimetype, 'mimetype');
        $this->set('data', FileUtility::getEmbedByContent($content, $mimetype));
    }

    public function userlogged($username = null, $authtoken = null) {
        parent::evalParam($username, 'username');
        parent::evalParam($authtoken, 'authtoken');
        $profile = null;
        $activity = null;
        $objToken = null;

        if (!empty($username)) {
            $authtoken = CakeSession::read($username);
        }
        if (empty($username) && !empty($authtoken)) {
            $username = ApploginUtility::getUsernameLoggedByToken($authtoken);
        }
        if (!empty($username)) {
            $profile = ApploginUtility::getProfileLogged($username);
            $activity = AppactivityUtility::getActivityLogged($username);
        }
        if (!empty($authtoken)) {
            $objToken = ApploginUtility::decodeTokenLogin($authtoken);
        }

        $this->set('username', $username);
        $this->set('authtoken', $authtoken);
        $this->set('profile', $profile);
        $this->set('activity', $activity);
        $this->set('objToken', !ArrayUtility::isEmpty($objToken) ? ArrayUtility::toPrintStringNewLine($objToken) : "");
    }
}