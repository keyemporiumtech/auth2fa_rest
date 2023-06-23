<?php
App::uses("Codes", "Config/system");
App::uses("AppController", "Controller");
App::uses("ApiurlUI", "Model");
App::uses("ApiurlUtility", "Model");
App::uses("DelegateUtility", "modules/cakeutils/utility");

class ApiurlController extends AppController {

    public function beforeFilter() {
        $this->json = true;
        $this->delegate = new ApiurlUI();
        $this->delegate->json = $this->json;
        parent::beforeFilter();
    }

    public function api($url = null, $params = null) {
        parent::evalParam($url, 'url');
        parent::evalParam($params, 'params', '');

        $objUrl = empty($url) ? null : $this->delegate->api[$url];

        if (empty($objUrl) || empty($url)) {
            $this->delegate->sendErrorApiNotFound($this, $url);
        } else {
            $arrParams = json_decode($params, true);
            $arrParams['evalApiManager'] = '0';
            $this->goToPageQueryMode($objUrl['action'], $objUrl['controller'], $arrParams);
        }
    }

    public function apiList() {
        $this->set("data", json_encode($this->delegate->api, true));
    }

    public function apiProfiles() {
        $this->set("data", json_encode(ApiurlUtility::buildApiProfilesManagement(true), true));
    }

    public function apiSystem() {
        $json = Cores::readJson(ROOT . "" . DS . "app" . DS . "Config" . DS . "json" . DS . "utility_manager.json");
        if (!Cores::isEmpty($json)) {
            $classname = $json['classname'];
            $path = $json['path'];
            App::uses($classname, $path);
            $util = new $classname();
            $this->set("data", json_encode($util->api, true));
        } else {
            $this->set("data", null);
        }
    }
}
