<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("ServicediscountBS", "modules/shop_warehouse/business");
App::uses("FileUtility", "modules/coreutils/utility");

class ServicediscountUI extends AppGenericUI {

    function __construct() {
        parent::__construct("ServicediscountUI");
        $this->localefile = "servicediscount";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("service", null, 0),
            new ObjPropertyEntity("discount", null, 0),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_SERVICEDISCOUNT_NOT_FOUND");
                return "";
            }
            $servicediscountBS = new ServicediscountBS();
            $servicediscountBS->json = $this->json;
            parent::completeByJsonFkVf($servicediscountBS);
            if (!empty($cod)) {
                $servicediscountBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $servicediscountBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_SERVICEDISCOUNT_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $servicediscountBS = !empty($bs) ? $bs : new ServicediscountBS();
            $servicediscountBS->json = $this->json;
            parent::completeByJsonFkVf($servicediscountBS);
            parent::evalConditions($servicediscountBS, $conditions);
            parent::evalOrders($servicediscountBS, $orders);
            $servicediscounts = $servicediscountBS->table($conditions, $orders, $paginate);
            parent::evalPagination($servicediscountBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($servicediscounts);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($servicediscountIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $servicediscount = DelegateUtility::getEntityToSave(new ServicediscountBS(), $servicediscountIn, $this->obj);

            if (!empty($servicediscount)) {

                $servicediscountBS = new ServicediscountBS();
                $id_servicediscount = $servicediscountBS->save($servicediscount);
                parent::saveInGroup($servicediscountBS, $id_servicediscount);

                parent::commitTransaction();
                if (!empty($id_servicediscount)) {
                    $this->ok(TranslatorUtility::__translate("INFO_SERVICEDISCOUNT_SAVE", $this->localefile));
                    return $id_servicediscount;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_SERVICEDISCOUNT_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_SERVICEDISCOUNT_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_SERVICEDISCOUNT_SAVE");
            return 0;
        }
    }

    function edit($id, $servicediscountIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $servicediscount = DelegateUtility::getEntityToEdit(new ServicediscountBS(), $servicediscountIn, $this->obj, $id);

            if (!empty($servicediscount)) {
                $servicediscountBS = new ServicediscountBS();
                $id_servicediscount = $servicediscountBS->save($servicediscount);
                parent::saveInGroup($servicediscountBS, $id_servicediscount);
                parent::delInGroup($servicediscountBS, $id_servicediscount);

                parent::commitTransaction();
                if (!empty($id_servicediscount)) {
                    $this->ok(TranslatorUtility::__translate("INFO_SERVICEDISCOUNT_EDIT", $this->localefile));
                    return $id_servicediscount;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_SERVICEDISCOUNT_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_SERVICEDISCOUNT_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_SERVICEDISCOUNT_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $servicediscountBS = new ServicediscountBS();
                $servicediscountBS->delete($id);
                parent::delInGroup($servicediscountBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_SERVICEDISCOUNT_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_SERVICEDISCOUNT_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_SERVICEDISCOUNT_DELETE");
            return false;
        }
    }
}
