<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("BasketserviceBS", "modules/shop_payment/business");
App::uses("FileUtility", "modules/coreutils/utility");

class BasketserviceUI extends AppGenericUI {

    function __construct() {
        parent::__construct("BasketserviceUI");
        $this->localefile = "basketservice";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("title", null, ""),
            new ObjPropertyEntity("service", null, 0),
            new ObjPropertyEntity("basket", null, 0),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_BASKETSERVICE_NOT_FOUND");
                return "";
            }
            $basketserviceBS = new BasketserviceBS();
            $basketserviceBS->json = $this->json;
            parent::completeByJsonFkVf($basketserviceBS);
            if (!empty($cod)) {
                $basketserviceBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $basketserviceBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_BASKETSERVICE_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $basketserviceBS = !empty($bs) ? $bs : new BasketserviceBS();
            $basketserviceBS->json = $this->json;
            parent::completeByJsonFkVf($basketserviceBS);
            parent::evalConditions($basketserviceBS, $conditions);
            parent::evalOrders($basketserviceBS, $orders);
            $basketservices = $basketserviceBS->table($conditions, $orders, $paginate);
            parent::evalPagination($basketserviceBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($basketservices);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($basketserviceIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $basketservice = DelegateUtility::getEntityToSave(new BasketserviceBS(), $basketserviceIn, $this->obj);

            if (!empty($basketservice)) {

                $basketserviceBS = new BasketserviceBS();
                $id_basketservice = $basketserviceBS->save($basketservice);
                parent::saveInGroup($basketserviceBS, $id_basketservice);

                parent::commitTransaction();
                if (!empty($id_basketservice)) {
                    $this->ok(TranslatorUtility::__translate("INFO_BASKETSERVICE_SAVE", $this->localefile));
                    return $id_basketservice;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_BASKETSERVICE_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_BASKETSERVICE_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_BASKETSERVICE_SAVE");
            return 0;
        }
    }

    function edit($id, $basketserviceIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $basketservice = DelegateUtility::getEntityToEdit(new BasketserviceBS(), $basketserviceIn, $this->obj, $id);

            if (!empty($basketservice)) {
                $basketserviceBS = new BasketserviceBS();
                $id_basketservice = $basketserviceBS->save($basketservice);
                parent::saveInGroup($basketserviceBS, $id_basketservice);
                parent::delInGroup($basketserviceBS, $id_basketservice);

                parent::commitTransaction();
                if (!empty($id_basketservice)) {
                    $this->ok(TranslatorUtility::__translate("INFO_BASKETSERVICE_EDIT", $this->localefile));
                    return $id_basketservice;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_BASKETSERVICE_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_BASKETSERVICE_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_BASKETSERVICE_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $basketserviceBS = new BasketserviceBS();
                $basketserviceBS->delete($id);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_BASKETSERVICE_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_BASKETSERVICE_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_BASKETSERVICE_DELETE");
            return false;
        }
    }
}
