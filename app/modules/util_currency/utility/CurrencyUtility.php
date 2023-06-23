<?php
App::uses("Enables", "Config/system");
App::uses("Codes", "Config/system");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ManagerBI", "modules/util_currency/plugin/bancaditalia");
App::uses("ManagerSVH", "modules/util_currency/plugin/sole24ore");
App::uses("Currency", "Model");
App::uses("CurrencyBS", "modules/util_currency/business");

class CurrencyUtility {

    static function convert($from, $to, $rate = 1) {
        $from = strtoupper($from);
        $to = strtoupper($to);
        if ($from == $to) {
            return $rate;
        }
        if (Enables::get("oanda")) {
            if (Enables::get("log_plugin")) {
                MessageUtility::logMessage(Codes::get("INFO_GENERIC"), "Conversione da {$from} a {$to} con plugin oanda", "log_currency", "currency", MessageUtility::logSource("CurrencyUtility", "convert"));
            }
            return ManagerSVH::convert($from, $to, $rate);
        } elseif (Enables::get("sole24ore")) {
            if (Enables::get("log_plugin")) {
                MessageUtility::logMessage(Codes::get("INFO_GENERIC"), "Conversione da {$from} a {$to} con plugin sole24ore", "log_currency", "currency", MessageUtility::logSource("CurrencyUtility", "convert"));
            }
            return ManagerSVH::convert($from, $to, $rate);
        } elseif (Enables::get("bancaditalia")) {
            if (Enables::get("log_plugin")) {
                MessageUtility::logMessage(Codes::get("INFO_GENERIC"), "Conversione da {$from} a {$to} con plugin bancaditalia", "log_currency", "currency", MessageUtility::logSource("CurrencyUtility", "convert"));
            }
            return ManagerBI::convert($from, $to, $rate);
        }
        return 0;
    }

    static function setFieldCurrency(&$data, $class, $fields = array(), $currencycod = null) {
        if (empty($currencycod)) {
            $currencycod = CakeSession::read('Config.currency');
        }
        if (!ArrayUtility::isEmpty($fields) && !empty($currencycod)) {
            $currencyEntity = new Currency();
            $currencyTo = $currencyEntity->find('first', array(
                'conditions' => array(
                    'cod' => $currencycod,
                ),
            ));
            foreach ($data as &$obj) {
                if (array_key_exists($class, $obj)) {
                    $currencyEntity = new Currency();
                    foreach ($fields as $field) {
                        if (array_key_exists($field, $obj[$class])) {
                            $currencyFrom = $currencyEntity->find('first', array(
                                'conditions' => array(
                                    'id' => $obj[$class]['currencyid'],
                                ),
                                'fields' => array(
                                    'cod',
                                ),
                            ));
                            if (!empty($currencyFrom)) {
                                $val = CurrencyUtility::convert($currencyFrom['Currency']['cod'], $currencycod, $obj[$class][$field]);
                                if (!empty($val)) {
                                    $obj[$class][$field] = $val;
                                }
                            }
                        }
                    }
                    $obj[$class]['currencyid'] = $currencyTo['Currency']['id'];
                }
            }
        }
    }

    static function getCurrencySystem() {
        $currencyBS = new CurrencyBS();
        $currencyBS->addCondition("cod", CakeSession::read("Config.currency"));
        return $currencyBS->unique();
    }
}