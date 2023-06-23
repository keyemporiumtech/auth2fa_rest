<?php
App::uses('AppController', 'Controller');
App::uses("Defaults", "Config/system");
App::uses("RestBI", "modules/util_currency/plugin/bancaditalia");
App::uses("ManagerBI", "modules/util_currency/plugin/bancaditalia");
App::uses("RestOANDA", "modules/util_currency/plugin/oanda");
App::uses("ManagerOANDA", "modules/util_currency/plugin/oanda");

class UtilcurrencyController extends AppController {

    public function home() {
        $this->set("currency", CakeSession::read('Config.currency'));
    }

    public function change() {
        CakeSession::write('Config.currency', 'usd');
        $this->redirect(array(
            'action' => 'home',
        ));
    }

    public function reset() {
        CakeSession::write('Config.currency', Defaults::get('currency'));
        $this->redirect(array(
            'action' => 'home',
        ));
    }

    public function pluginBi() {
    }

    public function bilatest() {
        $this->set("result", RestBI::latest());
    }

    public function bimanager() {
        $this->set("latest", ManagerBI::read());
        $this->set("currency", ManagerBI::get("EUR"));
        $this->set("converted", ManagerBI::convert("CHF", "LKR"));
    }

    public function pluginOanda() {
        $this->set("converted", RestOANDA::convert("CHF", "LKR"));
    }
    public function oandarest() {
        $this->set("result", RestOANDA::callApi("EUR", "USD"));
    }
    public function oandamanager($rate = null, $from = null, $to = null) {
        parent::evalParam($rate, "rate", "1");
        parent::evalParam($from, "curr1", "EUR");
        parent::evalParam($to, "curr2", "CHF");
        $this->set("rate", $rate);
        $this->set("curr1", $from);
        $this->set("curr2", $to);
        $this->set("converted", ManagerOANDA::convert($from, $to, $rate));
    }
    public function oandalanguage($currency = null, $language = null) {
        parent::evalParam($currency, "currency", "USD");
        parent::evalParam($language, "language", CakeSession::read('Config.language'));
		$this->set("currency", $currency);
		$this->set("language", $language);
        $this->set("currencies", ManagerOANDA::currencies($language));
        $this->set("currencyObject", ManagerOANDA::getCurrency($currency, $language));
        $this->set("currencyName", ManagerOANDA::translate($currency, $language));
    }
}
