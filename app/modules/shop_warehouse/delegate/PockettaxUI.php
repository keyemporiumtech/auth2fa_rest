<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("PockettaxBS", "modules/shop_warehouse/business");
App::uses("FileUtility", "modules/coreutils/utility");
App::uses("CurrencyUtility", "modules/util_currency/utility");

class PockettaxUI extends AppGenericUI {

    function __construct() {
        parent::__construct("PockettaxUI");
        $this->localefile = "pockettax";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("pocket", null, 0),
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
                DelegateUtility::paramsNull($this, "ERROR_POCKETTAX_NOT_FOUND");
                return "";
            }
            $pockettaxBS = new PockettaxBS();
            $pockettaxBS->json = $this->json;
            parent::completeByJsonFkVf($pockettaxBS);
            if (!empty($cod)) {
                $pockettaxBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $pockettaxBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_POCKETTAX_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $pockettaxBS = !empty($bs) ? $bs : new PockettaxBS();
            $pockettaxBS->json = $this->json;
            parent::completeByJsonFkVf($pockettaxBS);
            parent::evalConditions($pockettaxBS, $conditions);
            parent::evalOrders($pockettaxBS, $orders);
            $pockettaxs = $pockettaxBS->table($conditions, $orders, $paginate);
            parent::evalPagination($pockettaxBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($pockettaxs);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($pockettaxIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $pockettax = DelegateUtility::getEntityToSave(new PockettaxBS(), $pockettaxIn, $this->obj);

            if (!empty($pockettax)) {

                $pockettaxBS = new PockettaxBS();
                $id_pockettax = $pockettaxBS->save($pockettax);
                parent::saveInGroup($pockettaxBS, $id_pockettax);

                parent::commitTransaction();
                if (!empty($id_pockettax)) {
                    $this->ok(TranslatorUtility::__translate("INFO_POCKETTAX_SAVE", $this->localefile));
                    return $id_pockettax;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_POCKETTAX_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_POCKETTAX_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_POCKETTAX_SAVE");
            return 0;
        }
    }

    function edit($id, $pockettaxIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $pockettax = DelegateUtility::getEntityToEdit(new PockettaxBS(), $pockettaxIn, $this->obj, $id);

            if (!empty($pockettax)) {
                $pockettaxBS = new PockettaxBS();
                $id_pockettax = $pockettaxBS->save($pockettax);
                parent::saveInGroup($pockettaxBS, $id_pockettax);
                parent::delInGroup($pockettaxBS, $id_pockettax);

                parent::commitTransaction();
                if (!empty($id_pockettax)) {
                    $this->ok(TranslatorUtility::__translate("INFO_POCKETTAX_EDIT", $this->localefile));
                    return $id_pockettax;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_POCKETTAX_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_POCKETTAX_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_POCKETTAX_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $pockettaxBS = new PockettaxBS();
                $pockettaxBS->delete($id);
                parent::delInGroup($pockettaxBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_POCKETTAX_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_POCKETTAX_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_POCKETTAX_DELETE");
            return false;
        }
    }
}
