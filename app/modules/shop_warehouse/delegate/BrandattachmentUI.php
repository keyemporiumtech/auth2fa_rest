<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("BrandattachmentBS", "modules/shop_warehouse/business");
App::uses("FileUtility", "modules/coreutils/utility");

class BrandattachmentUI extends AppGenericUI {

    function __construct() {
        parent::__construct("BrandattachmentUI");
        $this->localefile = "brandattachment";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("brand", null, 0),
            new ObjPropertyEntity("attachment", null, 0),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_BRANDATTACHMENT_NOT_FOUND");
                return "";
            }
            $brandattachmentBS = new BrandattachmentBS();
            $brandattachmentBS->json = $this->json;
            parent::completeByJsonFkVf($brandattachmentBS);
            if (!empty($cod)) {
                $brandattachmentBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $brandattachmentBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_BRANDATTACHMENT_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $brandattachmentBS = !empty($bs) ? $bs : new BrandattachmentBS();
            $brandattachmentBS->json = $this->json;
            parent::completeByJsonFkVf($brandattachmentBS);
            parent::evalConditions($brandattachmentBS, $conditions);
            parent::evalOrders($brandattachmentBS, $orders);
            $brandattachments = $brandattachmentBS->table($conditions, $orders, $paginate);
            parent::evalPagination($brandattachmentBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($brandattachments);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($brandattachmentIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $brandattachment = DelegateUtility::getEntityToSave(new BrandattachmentBS(), $brandattachmentIn, $this->obj);

            if (!empty($brandattachment)) {

                $brandattachmentBS = new BrandattachmentBS();
                $id_brandattachment = $brandattachmentBS->save($brandattachment);
                parent::saveInGroup($brandattachmentBS, $id_brandattachment);

                parent::commitTransaction();
                if (!empty($id_brandattachment)) {
                    $this->ok(TranslatorUtility::__translate("INFO_BRANDATTACHMENT_SAVE", $this->localefile));
                    return $id_brandattachment;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_BRANDATTACHMENT_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_BRANDATTACHMENT_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_BRANDATTACHMENT_SAVE");
            return 0;
        }
    }

    function edit($id, $brandattachmentIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $brandattachment = DelegateUtility::getEntityToEdit(new BrandattachmentBS(), $brandattachmentIn, $this->obj, $id);

            if (!empty($brandattachment)) {
                $brandattachmentBS = new BrandattachmentBS();
                $id_brandattachment = $brandattachmentBS->save($brandattachment);
                parent::saveInGroup($brandattachmentBS, $id_brandattachment);
                parent::delInGroup($brandattachmentBS, $id_brandattachment);

                parent::commitTransaction();
                if (!empty($id_brandattachment)) {
                    $this->ok(TranslatorUtility::__translate("INFO_BRANDATTACHMENT_EDIT", $this->localefile));
                    return $id_brandattachment;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_BRANDATTACHMENT_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_BRANDATTACHMENT_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_BRANDATTACHMENT_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $brandattachmentBS = new BrandattachmentBS();
                $brandattachmentBS->delete($id);
                parent::delInGroup($brandattachmentBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_BRANDATTACHMENT_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_BRANDATTACHMENT_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_BRANDATTACHMENT_DELETE");
            return false;
        }
    }
}
