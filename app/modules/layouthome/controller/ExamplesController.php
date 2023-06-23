<?php
App::uses("AppController", "Controller");
App::uses('ProductionVisibilityUtility', 'modules/layouthome/utility');
App::uses('TranslatorUtility', 'modules/cakeutils/utility');
App::uses("ApiurlUI", "Model");
App::uses("ApiurlUtility", "Model");

class ExamplesController extends AppController {

    public function beforeFilter() {
        parent::beforeFilter();
        ProductionVisibilityUtility::checkAuth($this);
    }

    public function home() {
    }

    public function apiList() {
        $ui = new ApiurlUI();
        $this->set("data", $ui->api);
    }

    public function apiProfiles() {
        $this->set("data", ApiurlUtility::buildApiProfilesManagement(true));
    }

}