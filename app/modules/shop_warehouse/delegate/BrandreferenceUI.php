<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("BrandreferenceBS", "modules/shop_warehouse/business");
App::uses("FileUtility", "modules/coreutils/utility");

class BrandreferenceUI extends AppGenericUI {

    function __construct() {
        parent::__construct("BrandreferenceUI");
        $this->localefile = "brandreference";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("brand", null, 0),
            new ObjPropertyEntity("contactreference", null, 0),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_BRANDREFERENCE_NOT_FOUND");
                return "";
            }
            $brandreferenceBS = new BrandreferenceBS();
            $brandreferenceBS->json = $this->json;
            parent::completeByJsonFkVf($brandreferenceBS);
            if (!empty($cod)) {
                $brandreferenceBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $brandreferenceBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_BRANDREFERENCE_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $brandreferenceBS = !empty($bs) ? $bs : new BrandreferenceBS();
            $brandreferenceBS->json = $this->json;
            parent::completeByJsonFkVf($brandreferenceBS);
            parent::evalConditions($brandreferenceBS, $conditions);
            parent::evalOrders($brandreferenceBS, $orders);
            $brandreferences = $brandreferenceBS->table($conditions, $orders, $paginate);
            parent::evalPagination($brandreferenceBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($brandreferences);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($brandreferenceIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $brandreference = DelegateUtility::getEntityToSave(new BrandreferenceBS(), $brandreferenceIn, $this->obj);

            if (!empty($brandreference)) {

                $brandreferenceBS = new BrandreferenceBS();
                $id_brandreference = $brandreferenceBS->save($brandreference);
                parent::saveInGroup($brandreferenceBS, $id_brandreference);

                parent::commitTransaction();
                if (!empty($id_brandreference)) {
                    $this->ok(TranslatorUtility::__translate("INFO_BRANDREFERENCE_SAVE", $this->localefile));
                    return $id_brandreference;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_BRANDREFERENCE_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_BRANDREFERENCE_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_BRANDREFERENCE_SAVE");
            return 0;
        }
    }

    function edit($id, $brandreferenceIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $brandreference = DelegateUtility::getEntityToEdit(new BrandreferenceBS(), $brandreferenceIn, $this->obj, $id);

            if (!empty($brandreference)) {
                $brandreferenceBS = new BrandreferenceBS();
                $id_brandreference = $brandreferenceBS->save($brandreference);
                parent::saveInGroup($brandreferenceBS, $id_brandreference);
                parent::delInGroup($brandreferenceBS, $id_brandreference);

                parent::commitTransaction();
                if (!empty($id_brandreference)) {
                    $this->ok(TranslatorUtility::__translate("INFO_BRANDREFERENCE_EDIT", $this->localefile));
                    return $id_brandreference;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_BRANDREFERENCE_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_BRANDREFERENCE_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_BRANDREFERENCE_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $brandreferenceBS = new BrandreferenceBS();
                $brandreferenceBS->delete($id);
                parent::delInGroup($brandreferenceBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_BRANDREFERENCE_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_BRANDREFERENCE_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_BRANDREFERENCE_DELETE");
            return false;
        }
    }
}
