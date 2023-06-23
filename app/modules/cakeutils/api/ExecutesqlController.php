<?php
App::uses("AppController", "Controller");
App::uses("ExecutesqlUI", "modules/cakeutils/delegate");

class ExecutesqlController extends AppController {

    public function beforeFilter() {
        $this->json = true;
        $this->delegate = new ExecutesqlUI();
        $this->delegate->json = $this->json;
        parent::beforeFilter();
    }

    public function executeSql() {
        $this->set('value', $this->delegate->executeSql());
        $this->responseMessageStatus($this->delegate->status);
    }

    public function executeSqlPath($path = null) {
        parent::evalParam($path, 'path');
        $this->set('value', $this->delegate->executeSqlByPath($path));
        $this->responseMessageStatus($this->delegate->status);
    }

}