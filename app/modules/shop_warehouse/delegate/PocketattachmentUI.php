<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("PocketattachmentBS", "modules/shop_warehouse/business");
App::uses("FileUtility", "modules/coreutils/utility");

class PocketattachmentUI extends AppGenericUI {

    function __construct() {
        parent::__construct("PocketattachmentUI");
        $this->localefile = "pocketattachment";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("pocket", null, 0),
            new ObjPropertyEntity("attachment", null, 0),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_POCKETATTACHMENT_NOT_FOUND");
                return "";
            }
            $pocketattachmentBS = new PocketattachmentBS();
            $pocketattachmentBS->json = $this->json;
            parent::completeByJsonFkVf($pocketattachmentBS);
            if (!empty($cod)) {
                $pocketattachmentBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $pocketattachmentBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_POCKETATTACHMENT_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $pocketattachmentBS = !empty($bs) ? $bs : new PocketattachmentBS();
            $pocketattachmentBS->json = $this->json;
            parent::completeByJsonFkVf($pocketattachmentBS);
            parent::evalConditions($pocketattachmentBS, $conditions);
            parent::evalOrders($pocketattachmentBS, $orders);
            $pocketattachments = $pocketattachmentBS->table($conditions, $orders, $paginate);
            parent::evalPagination($pocketattachmentBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($pocketattachments);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($pocketattachmentIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $pocketattachment = DelegateUtility::getEntityToSave(new PocketattachmentBS(), $pocketattachmentIn, $this->obj);

            if (!empty($pocketattachment)) {

                $pocketattachmentBS = new PocketattachmentBS();
                $id_pocketattachment = $pocketattachmentBS->save($pocketattachment);
                parent::saveInGroup($pocketattachmentBS, $id_pocketattachment);

                parent::commitTransaction();
                if (!empty($id_pocketattachment)) {
                    $this->ok(TranslatorUtility::__translate("INFO_POCKETATTACHMENT_SAVE", $this->localefile));
                    return $id_pocketattachment;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_POCKETATTACHMENT_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_POCKETATTACHMENT_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_POCKETATTACHMENT_SAVE");
            return 0;
        }
    }

    function edit($id, $pocketattachmentIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $pocketattachment = DelegateUtility::getEntityToEdit(new PocketattachmentBS(), $pocketattachmentIn, $this->obj, $id);

            if (!empty($pocketattachment)) {
                $pocketattachmentBS = new PocketattachmentBS();
                $id_pocketattachment = $pocketattachmentBS->save($pocketattachment);
                parent::saveInGroup($pocketattachmentBS, $id_pocketattachment);
                parent::delInGroup($pocketattachmentBS, $id_pocketattachment);

                parent::commitTransaction();
                if (!empty($id_pocketattachment)) {
                    $this->ok(TranslatorUtility::__translate("INFO_POCKETATTACHMENT_EDIT", $this->localefile));
                    return $id_pocketattachment;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_POCKETATTACHMENT_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_POCKETATTACHMENT_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_POCKETATTACHMENT_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $pocketattachmentBS = new PocketattachmentBS();
                $pocketattachmentBS->delete($id);
                parent::delInGroup($pocketattachmentBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_POCKETATTACHMENT_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_POCKETATTACHMENT_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_POCKETATTACHMENT_DELETE");
            return false;
        }
    }
}
