<?php
App::uses('AppController', 'Controller');
App::uses("Defaults", "Config/system");
App::uses("AppclientUtility", "modules/authentication/utility");
App::uses("AppclientUI", "modules/authentication/delegate");
App::uses("ApploginUI", "modules/authentication/delegate");

class AuthenticationController extends AppController {

    public function home() {
        $this->set("clientNameToken", Defaults::get("param_name_token_client"));
        $this->set("loginNameToken", Defaults::get("param_name_token_login"));
    }

    public function clientTokenEncode($clientId = null) {
        parent::evalParam($clientId, 'client_id');
        $this->set("token", AppclientUtility::buildToken($clientId));
    }

    public function clientTokenDecode($token = null) {
        parent::evalParam($token, 'token');
        $this->set("verify", AppclientUtility::verifyToken($token));
        $this->set("clientId", AppclientUtility::decodeTokenClient($token));
    }

    public function tokenNull() {
        AppclientUtility::checkTokenClient($this);
    }

    public function tokenInvalid() {
        AppclientUtility::checkTokenClient($this);
    }

    public function tokenValid() {
        AppclientUtility::checkTokenClient($this);
        $ui = new AppclientUI();
        $ui->setTokenValid();
        $this->responseMessageStatus($ui->status);
    }

    // LOGIN
    public function loginTokenEncode($clientId = null, $payload = null) {
        parent::evalParam($clientId, 'client_id');
        parent::evalParam($payload, 'payload');
        $this->set("token", ApploginUtility::buildToken($clientId, $payload));
    }

    public function loginTokenDecode($token = null) {
        parent::evalParam($token, 'token');
        $this->set("verify", ApploginUtility::verifyToken($token));
        $this->set("loginObj", ApploginUtility::decodeTokenLogin($token));
    }

    public function loginNull() {
        ApploginUtility::checkTokenLogin($this);
    }

    public function loginInvalid() {
        ApploginUtility::checkTokenLogin($this);
    }

    public function loginValid() {
        ApploginUtility::checkTokenLogin($this);
        $ui = new AppclientUI();
        $ui->setTokenValid();
        $this->responseMessageStatus($ui->status);
    }
    // BUILD
    public function buildApi($ctrl = null, $act = null, $clientT = null, $sessionT = null, $paramsS = null) {
        parent::evalParam($ctrl, 'ctrl');
        parent::evalParam($act, 'act');
        parent::evalParam($clientT, 'clientT');
        parent::evalParam($sessionT, 'sessionT');
        parent::evalParam($paramsS, 'paramsS');
        $url = Router::url('/', true) . $ctrl . "/" . $act . "?";
        $and = "&";
        if (!empty($paramsS)) {
            $url .= urlencode($paramsS);
        } else {
            $and = "";
        }
        if (!empty($clientT)) {
            $url .= "{$and}" . Defaults::get("param_name_token_client") . "=" . $clientT;
            $and = "&";
        }
        if (!empty($sessionT)) {
            $url .= "{$and}" . Defaults::get("param_name_token_login") . "=" . $sessionT;
        }
        $this->set("url", $url);
    }
}