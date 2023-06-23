<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("Defaults", "Config/system");
App::uses("CurrencyBS", "modules/util_currency/business");
App::uses("FileUtility", "modules/coreutils/utility");
App::uses("CurrencyUtility", "modules/util_currency/utility");

class CurrencyUI extends AppGenericUI {

    function __construct() {
        parent::__construct("CurrencyUI");
        $this->localefile = "currency";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("title", null, ""),
            new ObjPropertyEntity("symbol", null, ""),
            new ObjPropertyEntity("flgused", null, 0),
        );
    }

    function get($id = null, $cod = null, $title = null, $symbol = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod) && empty($title) && empty($symbol)) {
                DelegateUtility::paramsNull($this, "ERROR_CURRENCY_NOT_FOUND");
                return "";
            }
            $currencyBS = new CurrencyBS();
            $currencyBS->json = $this->json;
            parent::completeByJsonFkVf($currencyBS);
            if (!empty($cod)) {
                $currencyBS->addCondition("cod", $cod);
            }
            if (!empty($title)) {
                $currencyBS->addCondition("title", $title);
            }
            if (!empty($symbol)) {
                $currencyBS->addCondition("symbol", $symbol);
            }
            $this->ok();
            return $currencyBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_CURRENCY_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $currencyBS = !empty($bs) ? $bs : new CurrencyBS();
            $currencyBS->json = $this->json;
            parent::completeByJsonFkVf($currencyBS);
            parent::evalConditions($currencyBS, $conditions);
            parent::evalOrders($currencyBS, $orders);
            $currencys = $currencyBS->table($conditions, $orders, $paginate);
            parent::evalPagination($currencyBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($currencys);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($currencyIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $currency = DelegateUtility::getEntityToSave(new CurrencyBS(), $currencyIn, $this->obj);

            if (!empty($currency)) {

                $currencyBS = new CurrencyBS();
                $id_currency = $currencyBS->save($currency);
                parent::saveInGroup($currencyBS, $id_currency);

                parent::commitTransaction();
                if (!empty($id_currency)) {
                    $this->ok(TranslatorUtility::__translate("INFO_CURRENCY_SAVE", $this->localefile));
                    return $id_currency;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_CURRENCY_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_CURRENCY_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_CURRENCY_SAVE");
            return 0;
        }
    }

    function edit($id, $currencyIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $currency = DelegateUtility::getEntityToEdit(new CurrencyBS(), $currencyIn, $this->obj, $id);

            if (!empty($currency)) {
                $currencyBS = new CurrencyBS();
                $id_currency = $currencyBS->save($currency);
                parent::saveInGroup($currencyBS, $id_currency);
                parent::delInGroup($currencyBS, $id_currency);

                parent::commitTransaction();
                if (!empty($id_currency)) {
                    $this->ok(TranslatorUtility::__translate("INFO_CURRENCY_EDIT", $this->localefile));
                    return $id_currency;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_CURRENCY_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_CURRENCY_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_CURRENCY_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $currencyBS = new CurrencyBS();
                $currencyBS->delete($id);
                parent::delInGroup($currencyBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_CURRENCY_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_CURRENCY_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_CURRENCY_DELETE");
            return false;
        }
    }

    // ------------------ SESSION
    function setupCurrency($cod, $flgMessage = false) {
        $this->LOG_FUNCTION = "setupCurrency";
        try {
            if (empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_CURRENCY_SETUP");
                return "";
            }
            //INFO_CURRENCY_CHANGE
            $current = $this->get(null, $cod);
            if (!empty($current)) {
                CakeSession::write('Config.currency', $cod);
                $objLog = new ObjCodMessage(Codes::get("CURRENCY_CHANGE"), TranslatorUtility::__translate_args("INFO_CURRENCY_CHANGE", array(
                    $cod,
                ), $this->localefile));
                DelegateUtility::logMessage($this, $objLog);
                if ($flgMessage) {
                    $this->ok(TranslatorUtility::__translate_args("INFO_CURRENCY_CHANGE", array(
                        $cod,
                    ), $this->localefile));
                } else {
                    $this->ok();
                }
                return $current;
            } else {
                DelegateUtility::errorInternal($this, "OBJECT_NULL", "ERROR_CURRENCY_SETUP", null, "ERROR_CURRENCY_COD", array(
                    $cod,
                ));
                return "";
            }
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_CURRENCY_SETUP");
            return "";
        }
    }

    function getCurrentCurrencySystem() {
        $this->LOG_FUNCTION = "getCurrentCurrencySystem";
        try {
            $cod = CakeSession::read('Config.currency');
            if (empty($cod)) {
                $cod = Defaults::get("currency");
                CakeSession::write('Config.currency', $cod);
                $objLog = new ObjCodMessage(Codes::get("CURRENCY_CHANGE"), TranslatorUtility::__translate_args("INFO_CURRENCY_CHANGE_DEFAULT", array(
                    $cod,
                ), $this->localefile));
                DelegateUtility::logMessage($this, $objLog);
            }
            if (empty($cod)) {
                DelegateUtility::errorInternal($this, "PARAM_NULL", "ERROR_CURRENCY_NOT_FOUND", null, "ERROR_CURRENCY_CHANGE_DEFAULT");
                return "";
            }
            return $this->get(null, $cod);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_CURRENCY_NOT_FOUND");
            return "";
        }
    }

    // --------------- UTILITY
    function convert($from = null, $to = null, $rate = null) {
        $this->LOG_FUNCTION = "convert";
        try {

            if (empty($from) && empty($to) && empty($rate)) {
                DelegateUtility::paramsNull($this, "ERROR_CURRENCY_CONVERTER");
                return 0;
            }
            if (empty($to)) {
                $to = CakeSession::read('Config.currency');
            }
            if (empty($rate)) {
                $rate = 1;
            }
            $this->ok();
            return CurrencyUtility::convert($from, $to, $rate);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_CURRENCY_CONVERTER");
            return 0;
        }
    }
}
