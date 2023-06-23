<?php
App::uses("AppController", "Controller");
App::uses("TestfkUI", "modules/cakeutils/delegate");

class TestfkController extends AppController {

    public function beforeFilter() {
        $this->json = true;
        $this->delegate = new TestfkUI();
        $this->delegate->json = $this->json;
        parent::beforeFilter();
    }

    public function get($id = null, $cod = null, $belongs = null, $virtualfields = null, $flags = null, $properties = null, $groups = null, $likegroups = null) {
        parent::evalParam($id, 'id');
        parent::evalParam($cod, 'cod');
        $checkBelongs = false;
        parent::evalParamBool($checkBelongs, 'checkBelongs');
        if ($checkBelongs) {
            $belongs = "[\"test_fk\"]";
        }
        parent::completeFkVf($this->delegate, $belongs, $virtualfields, $flags, $properties, $groups, $likegroups);
        $this->set("record", $this->delegate->get($id, $cod));
        $this->responseMessageStatus($this->delegate->status);
    }

    public function table($jsonFilters = null, $jsonOrders = null, $jsonPaginate = null, $belongs = null, $virtualfields = null, $flags = null, $properties = null, $groups = null, $likegroups = null) {
        parent::evalParam($jsonFilters, 'filters');
        parent::evalParam($jsonOrders, 'orders');
        parent::evalParam($jsonPaginate, 'paginate');
        parent::completeFkVf($this->delegate, $belongs, $virtualfields, $flags, $properties, $groups, $likegroups);
        $this->set('list', $this->delegate->table($jsonFilters, $jsonOrders, $jsonPaginate));
        $this->responseMessageStatus($this->delegate->status);
    }
}