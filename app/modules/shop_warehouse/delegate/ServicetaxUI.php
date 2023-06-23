<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("ServicetaxBS", "modules/shop_warehouse/business");
App::uses("FileUtility", "modules/coreutils/utility");
App::uses("CurrencyUtility", "modules/util_currency/utility");

class ServicetaxUI extends AppGenericUI {

    function __construct() {
        parent::__construct("ServicetaxUI");
        $this->localefile = "servicetax";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("service", null, 0),
            new ObjPropertyEntity("tax", null, 0.00),
            new ObjPropertyEntity("tax_percent", null, 0.00),
            new ObjPropertyEntity("taxdescription", null, ""),
            new ObjPropertyEntity("currencyid", null, CurrencyUtility::getCurrencySystem()['Currency']['id']),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_SERVICETAX_NOT_FOUND");
                return "";
            }
            $servicetaxBS = new ServicetaxBS();
            $servicetaxBS->json = $this->json;
            parent::completeByJsonFkVf($servicetaxBS);
            if (!empty($cod)) {
                $servicetaxBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $servicetaxBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_SERVICETAX_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $servicetaxBS = !empty($bs) ? $bs : new ServicetaxBS();
            $servicetaxBS->json = $this->json;
            parent::completeByJsonFkVf($servicetaxBS);
            parent::evalConditions($servicetaxBS, $conditions);
            parent::evalOrders($servicetaxBS, $orders);
            $servicetaxs = $servicetaxBS->table($conditions, $orders, $paginate);
            parent::evalPagination($servicetaxBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($servicetaxs);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($servicetaxIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $servicetax = DelegateUtility::getEntityToSave(new ServicetaxBS(), $servicetaxIn, $this->obj);

            if (!empty($servicetax)) {

                $servicetaxBS = new ServicetaxBS();
                $id_servicetax = $servicetaxBS->save($servicetax);
                parent::saveInGroup($servicetaxBS, $id_servicetax);

                parent::commitTransaction();
                if (!empty($id_servicetax)) {
                    $this->ok(TranslatorUtility::__translate("INFO_SERVICETAX_SAVE", $this->localefile));
                    return $id_servicetax;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_SERVICETAX_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_SERVICETAX_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_SERVICETAX_SAVE");
            return 0;
        }
    }

    function edit($id, $servicetaxIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $servicetax = DelegateUtility::getEntityToEdit(new ServicetaxBS(), $servicetaxIn, $this->obj, $id);

            if (!empty($servicetax)) {
                $servicetaxBS = new ServicetaxBS();
                $id_servicetax = $servicetaxBS->save($servicetax);
                parent::saveInGroup($servicetaxBS, $id_servicetax);
                parent::delInGroup($servicetaxBS, $id_servicetax);

                parent::commitTransaction();
                if (!empty($id_servicetax)) {
                    $this->ok(TranslatorUtility::__translate("INFO_SERVICETAX_EDIT", $this->localefile));
                    return $id_servicetax;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_SERVICETAX_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_SERVICETAX_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_SERVICETAX_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $servicetaxBS = new ServicetaxBS();
                $servicetaxBS->delete($id);
                parent::delInGroup($servicetaxBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_SERVICETAX_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_SERVICETAX_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_SERVICETAX_DELETE");
            return false;
        }
    }
}
