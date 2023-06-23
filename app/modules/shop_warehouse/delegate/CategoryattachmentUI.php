<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("CategoryattachmentBS", "modules/shop_warehouse/business");
App::uses("FileUtility", "modules/coreutils/utility");

class CategoryattachmentUI extends AppGenericUI {

    function __construct() {
        parent::__construct("CategoryattachmentUI");
        $this->localefile = "categoryattachment";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("category", null, 0),
            new ObjPropertyEntity("attachment", null, 0),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_CATEGORYATTACHMENT_NOT_FOUND");
                return "";
            }
            $categoryattachmentBS = new CategoryattachmentBS();
            $categoryattachmentBS->json = $this->json;
            parent::completeByJsonFkVf($categoryattachmentBS);
            if (!empty($cod)) {
                $categoryattachmentBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $categoryattachmentBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_CATEGORYATTACHMENT_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $categoryattachmentBS = !empty($bs) ? $bs : new CategoryattachmentBS();
            $categoryattachmentBS->json = $this->json;
            parent::completeByJsonFkVf($categoryattachmentBS);
            parent::evalConditions($categoryattachmentBS, $conditions);
            parent::evalOrders($categoryattachmentBS, $orders);
            $categoryattachments = $categoryattachmentBS->table($conditions, $orders, $paginate);
            parent::evalPagination($categoryattachmentBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($categoryattachments);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($categoryattachmentIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $categoryattachment = DelegateUtility::getEntityToSave(new CategoryattachmentBS(), $categoryattachmentIn, $this->obj);

            if (!empty($categoryattachment)) {

                $categoryattachmentBS = new CategoryattachmentBS();
                $id_categoryattachment = $categoryattachmentBS->save($categoryattachment);
                parent::saveInGroup($categoryattachmentBS, $id_categoryattachment);

                parent::commitTransaction();
                if (!empty($id_categoryattachment)) {
                    $this->ok(TranslatorUtility::__translate("INFO_CATEGORYATTACHMENT_SAVE", $this->localefile));
                    return $id_categoryattachment;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_CATEGORYATTACHMENT_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_CATEGORYATTACHMENT_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_CATEGORYATTACHMENT_SAVE");
            return 0;
        }
    }

    function edit($id, $categoryattachmentIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $categoryattachment = DelegateUtility::getEntityToEdit(new CategoryattachmentBS(), $categoryattachmentIn, $this->obj, $id);

            if (!empty($categoryattachment)) {
                $categoryattachmentBS = new CategoryattachmentBS();
                $id_categoryattachment = $categoryattachmentBS->save($categoryattachment);
                parent::saveInGroup($categoryattachmentBS, $id_categoryattachment);
                parent::delInGroup($categoryattachmentBS, $id_categoryattachment);

                parent::commitTransaction();
                if (!empty($id_categoryattachment)) {
                    $this->ok(TranslatorUtility::__translate("INFO_CATEGORYATTACHMENT_EDIT", $this->localefile));
                    return $id_categoryattachment;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_CATEGORYATTACHMENT_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_CATEGORYATTACHMENT_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_CATEGORYATTACHMENT_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $categoryattachmentBS = new CategoryattachmentBS();
                $categoryattachmentBS->delete($id);
                parent::delInGroup($categoryattachmentBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_CATEGORYATTACHMENT_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_CATEGORYATTACHMENT_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_CATEGORYATTACHMENT_DELETE");
            return false;
        }
    }
}
