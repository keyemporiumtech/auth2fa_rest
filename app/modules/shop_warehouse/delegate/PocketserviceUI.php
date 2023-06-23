<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("PocketserviceBS", "modules/shop_warehouse/business");
App::uses("FileUtility", "modules/coreutils/utility");

class PocketserviceUI extends AppGenericUI {

    function __construct() {
        parent::__construct("PocketserviceUI");
        $this->localefile = "pocketservice";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("pocket", null, 0),
            new ObjPropertyEntity("service", null, 0),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_POCKETSERVICE_NOT_FOUND");
                return "";
            }
            $pocketserviceBS = new PocketserviceBS();
            $pocketserviceBS->json = $this->json;
            parent::completeByJsonFkVf($pocketserviceBS);
            if (!empty($cod)) {
                $pocketserviceBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $pocketserviceBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_POCKETSERVICE_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $pocketserviceBS = !empty($bs) ? $bs : new PocketserviceBS();
            $pocketserviceBS->json = $this->json;
            parent::completeByJsonFkVf($pocketserviceBS);
            parent::evalConditions($pocketserviceBS, $conditions);
            parent::evalOrders($pocketserviceBS, $orders);
            $pocketservices = $pocketserviceBS->table($conditions, $orders, $paginate);
            parent::evalPagination($pocketserviceBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($pocketservices);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($pocketserviceIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $pocketservice = DelegateUtility::getEntityToSave(new PocketserviceBS(), $pocketserviceIn, $this->obj);

            if (!empty($pocketservice)) {

                $pocketserviceBS = new PocketserviceBS();
                $id_pocketservice = $pocketserviceBS->save($pocketservice);
                parent::saveInGroup($pocketserviceBS, $id_pocketservice);

                parent::commitTransaction();
                if (!empty($id_pocketservice)) {
                    $this->ok(TranslatorUtility::__translate("INFO_POCKETSERVICE_SAVE", $this->localefile));
                    return $id_pocketservice;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_POCKETSERVICE_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_POCKETSERVICE_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_POCKETSERVICE_SAVE");
            return 0;
        }
    }

    function edit($id, $pocketserviceIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $pocketservice = DelegateUtility::getEntityToEdit(new PocketserviceBS(), $pocketserviceIn, $this->obj, $id);

            if (!empty($pocketservice)) {
                $pocketserviceBS = new PocketserviceBS();
                $id_pocketservice = $pocketserviceBS->save($pocketservice);
                parent::saveInGroup($pocketserviceBS, $id_pocketservice);
                parent::delInGroup($pocketserviceBS, $id_pocketservice);

                parent::commitTransaction();
                if (!empty($id_pocketservice)) {
                    $this->ok(TranslatorUtility::__translate("INFO_POCKETSERVICE_EDIT", $this->localefile));
                    return $id_pocketservice;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_POCKETSERVICE_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_POCKETSERVICE_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_POCKETSERVICE_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $pocketserviceBS = new PocketserviceBS();
                $pocketserviceBS->delete($id);
                parent::delInGroup($pocketserviceBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_POCKETSERVICE_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_POCKETSERVICE_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_POCKETSERVICE_DELETE");
            return false;
        }
    }
}
