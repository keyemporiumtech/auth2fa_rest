<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("ConnectionManager", "Model");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("AttachmentBS", "modules/resources/business");
App::uses("FileUtility", "modules/coreutils/utility");
App::uses("TypologicalUI", "modules/cakeutils/delegate");

class AttachmentUI extends AppGenericUI {

    function __construct() {
        parent::__construct("AttachmentUI");
        $this->localefile = "attachment";
        $this->obj = array(
            new ObjPropertyEntity("url", null, ""),
            new ObjPropertyEntity("path", null, ""),
            new ObjPropertyEntity("name", null, ""),
            new ObjPropertyEntity("cid", null, ""),
            new ObjPropertyEntity("cod", null, FileUtility::uuid_short()),
            new ObjPropertyEntity("description", null, ""),
            new ObjPropertyEntity("size", null, 0),
            new ObjPropertyEntity("ext", null, ""),
            new ObjPropertyEntity("mimetype", null, ""),
            new ObjPropertyEntity("type", null, ""),
            new ObjPropertyEntity("flgpre", null, 0),
            new ObjPropertyEntity("flgpost", null, 0),
            new ObjPropertyEntity("prehtml", null, ""),
            new ObjPropertyEntity("posthtml", null, ""),
            new ObjPropertyEntity("tpattachment", null, 0),
            new ObjPropertyEntity("content", null, ""),
        );
    }

    function get($id = null, $cod = null, $name = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod) && empty($name)) {
                DelegateUtility::paramsNull($this, "ERROR_ATTACHMENT_NOT_FOUND");
                return "";
            }
            $attachmentBS = new AttachmentBS();
            $attachmentBS->json = $this->json;
            parent::completeByJsonFkVf($attachmentBS);
            if (!empty($cod)) {
                $attachmentBS->addCondition("cod", $cod);
            }
            if (!empty($name)) {
                $attachmentBS->addCondition("name", $name);
            }
            $this->ok();
            return $attachmentBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_ATTACHMENT_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $attachmentBS = !empty($bs) ? $bs : new AttachmentBS();
            $attachmentBS->json = $this->json;
            parent::completeByJsonFkVf($attachmentBS);
            parent::evalConditions($attachmentBS, $conditions);
            parent::evalOrders($attachmentBS, $orders);
            $attachments = $attachmentBS->table($conditions, $orders, $paginate);
            parent::evalPagination($attachmentBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($attachments);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($attachmentIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $attachment = DelegateUtility::getEntityToSave(new AttachmentBS(), $attachmentIn, $this->obj);

            if (!empty($attachment)) {

                $attachmentBS = new AttachmentBS();
                $id_attachment = $attachmentBS->save($attachment);
                parent::saveInGroup($attachmentBS, $id_attachment);

                parent::commitTransaction();
                if (!empty($id_attachment)) {
                    DelegateUtility::integratEntityCod(new AttachmentBS(), $attachment, $id_attachment);
                    $this->ok(TranslatorUtility::__translate("INFO_ATTACHMENT_SAVE", $this->localefile));
                    return $id_attachment;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_ATTACHMENT_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_ATTACHMENT_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_ATTACHMENT_SAVE");
            return 0;
        }
    }

    function edit($id, $attachmentIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $attachment = DelegateUtility::getEntityToEdit(new AttachmentBS(), $attachmentIn, $this->obj, $id);

            if (!empty($attachment)) {
                $attachmentBS = new AttachmentBS();
                $id_attachment = $attachmentBS->save($attachment);
                parent::saveInGroup($attachmentBS, $id_attachment);
                parent::delInGroup($attachmentBS, $id_attachment);

                parent::commitTransaction();
                if (!empty($id_attachment)) {
                    $this->ok(TranslatorUtility::__translate("INFO_ATTACHMENT_EDIT", $this->localefile));
                    return $id_attachment;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_ATTACHMENT_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_ATTACHMENT_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_ATTACHMENT_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $attachmentBS = new AttachmentBS();
                $attachmentBS->delete($id);
                parent::delInGroup($attachmentBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_ATTACHMENT_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_ATTACHMENT_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_ATTACHMENT_DELETE");
            return false;
        }
    }

    // ---------------- TYPOLOGICAL
    function tpattachment($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "tpattachment";
        try {
            $typologicalUI = new TypologicalUI("Tpattachment", "resources");
            $typologicalUI->json = $this->json;
            parent::assignToDelegate($typologicalUI);
            $result = $typologicalUI->table($conditions, $orders, $paginate, $bs);
            parent::mappingDelegate($typologicalUI);
            return $result;
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }
}
