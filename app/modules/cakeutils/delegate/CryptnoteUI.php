<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("ConnectionManager", "Model");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("CryptnoteBS", "modules/cakeutils/business");
App::uses("FileUtility", "modules/coreutils/utility");
App::uses("AttachmentUI", "modules/resources/delegate");
App::uses("GroupBS", "modules/cakeutils/business");
App::uses("GrouprelationBS", "modules/cakeutils/business");

class CryptnoteUI extends AppGenericUI {

    function __construct() {
        parent::__construct("CryptnoteUI");
        $this->localefile = "cryptnote";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_short()),
            new ObjPropertyEntity("title", null, ""),
            new ObjPropertyEntity("description", null, ""),
            new ObjPropertyEntity("crypt", null, ""),
            new ObjPropertyEntity("symbol", null, ""),
            new ObjPropertyEntity("flgused", null, 0),
        );
    }

    function get($id = null, $cod = null, $title = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod) && empty($title)) {
                DelegateUtility::paramsNull($this, "ERROR_CRYPTNOTE_NOT_FOUND");
                return "";
            }
            $cryptnoteBS = new CryptnoteBS();
            $cryptnoteBS->json = $this->json;
            parent::completeByJsonFkVf($cryptnoteBS);
            if (!empty($cod)) {
                $cryptnoteBS->addCondition("cod", $cod);
            }
            if (!empty($title)) {
                $cryptnoteBS->addCondition("title", $title);
            }
            $this->ok();
            return $cryptnoteBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_CRYPTNOTE_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $cryptnoteBS = !empty($bs) ? $bs : new CryptnoteBS();
            $cryptnoteBS->json = $this->json;
            parent::completeByJsonFkVf($cryptnoteBS);
            parent::evalConditions($cryptnoteBS, $conditions);
            parent::evalOrders($cryptnoteBS, $orders);
            $cryptnotes = $cryptnoteBS->table($conditions, $orders, $paginate);
            parent::evalPagination($cryptnoteBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($cryptnotes);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($cryptnoteIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $cryptnote = DelegateUtility::getEntityToSave(new CryptnoteBS(), $cryptnoteIn, $this->obj);

            if (!empty($cryptnote)) {

                $cryptnoteBS = new CryptnoteBS();
                $id_cryptnote = $cryptnoteBS->save($cryptnote);
                parent::saveInGroup($cryptnoteBS, $id_cryptnote);

                parent::commitTransaction();
                if (!empty($id_cryptnote)) {
                    DelegateUtility::integratEntityCod(new CryptnoteBS(), $cryptnote, $id_cryptnote);
                    $this->ok(TranslatorUtility::__translate("INFO_CRYPTNOTE_SAVE", $this->localefile));
                    return $id_cryptnote;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_CRYPTNOTE_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_CRYPTNOTE_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_CRYPTNOTE_SAVE");
            return 0;
        }
    }

    function edit($id, $cryptnoteIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $cryptnote = DelegateUtility::getEntityToEdit(new CryptnoteBS(), $cryptnoteIn, $this->obj, $id);

            if (!empty($cryptnote)) {
                $cryptnoteBS = new CryptnoteBS();
                $id_cryptnote = $cryptnoteBS->save($cryptnote);
                parent::saveInGroup($cryptnoteBS, $id_cryptnote);
                parent::delInGroup($cryptnoteBS, $id_cryptnote);

                parent::commitTransaction();
                if (!empty($id_cryptnote)) {
                    $this->ok(TranslatorUtility::__translate("INFO_CRYPTNOTE_EDIT", $this->localefile));
                    return $id_cryptnote;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_CRYPTNOTE_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_CRYPTNOTE_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_CRYPTNOTE_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $cryptnoteBS = new CryptnoteBS();
                $cryptnoteBS->delete($id);
                parent::delInGroup($cryptnoteBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_CRYPTNOTE_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_CRYPTNOTE_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_CRYPTNOTE_DELETE");
            return false;
        }
    }

    // ---- PHOTO Group
    function saveAttachment($id_group, $attachmentIn) {
        $this->LOG_FUNCTION = "saveAttachment";
        try {
            parent::startTransaction();

            $attachmentUI = new AttachmentUI();
            $attachmentUI->json = $this->json;
            $attachmentUI->transactional = true;
            $id_attachment = $attachmentUI->save($attachmentIn);
            if (empty($id_attachment)) {
                parent::rollbackTransaction();
                parent::mappingDelegate($attachmentUI);
                return 0;
            }

            if (empty($id_group)) {
                parent::rollbackTransaction();
                DelegateUtility::paramsNull($this, "ERROR_ATTACHMENT_SAVE", "attachment");
                return 0;
            }

            $groupBS = new GroupBS();
            $group = $groupBS->unique($id_group);
            if (empty($group)) {
                parent::rollbackTransaction();
                DelegateUtility::nonSalvato($this, "ERROR_ATTACHMENT_SAVE", "attachment");
                return 0;
            }

            if (!empty($id_attachment)) {

                $grouprelationBS = new GrouprelationBS();
                $grouprelation = $grouprelationBS->instance();

                $grouprelation['Grouprelation']['cod'] = $group['Group']['cod'] . "_" . FileUtility::uuid_short();
                $grouprelation['Grouprelation']['group'] = $id_group;
                $grouprelation['Grouprelation']['groupcod'] = $group['Group']['cod'];
                $grouprelation['Grouprelation']['tableid'] = $id_attachment;
                $grouprelation['Grouprelation']['tablename'] = 'attachment';

                $grouprelationBS = new GrouprelationBS();
                $id_grouprelation = $grouprelationBS->save($grouprelation);

                parent::commitTransaction();
                if (!empty($id_grouprelation)) {
                    $this->ok(TranslatorUtility::__translate("INFO_ATTACHMENT_SAVE", "attachment"));
                    return $id_attachment;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_ATTACHMENT_SAVE", "attachment");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_ATTACHMENT_SAVE", "attachment");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_ATTACHMENT_SAVE", "attachment");
            return 0;
        }
    }

    function deleteAttachment($id_group) {
        $this->LOG_FUNCTION = "deleteAttachment";
        try {
            parent::startTransaction();

            if (empty($id_group)) {
                parent::rollbackTransaction();
                DelegateUtility::nonSalvato($this, "ERROR_ATTACHMENT_DELETE", "attachment");
                return false;
            }

            $grouprelationBS = new GrouprelationBS();
            $grouprelationBS->addCondition("group", $id_group);
            $grouprelationBS->addCondition("tablename", "attachment");
            $grouprelation = $grouprelationBS->unique();
            if (empty($grouprelation)) {
                parent::rollbackTransaction();
                DelegateUtility::nonSalvato($this, "ERROR_ATTACHMENT_DELETE", "attachment");
                return false;
            }
            $id_attachment = $grouprelation['Grouprelation']['tableid'];

            $grouprelationBS = new GrouprelationBS();
            $flgOp = $grouprelationBS->delete($grouprelation['Grouprelation']['id']);
            if (empty($flgOp)) {
                parent::rollbackTransaction();
                DelegateUtility::nonSalvato($this, "ERROR_ATTACHMENT_DELETE", "attachment");
                return false;
            }

            $attachmentUI = new AttachmentUI();
            $attachmentUI->json = $this->json;
            $attachmentUI->transactional = true;
            $op_delete = $attachmentUI->delete($id_attachment);
            if (empty($op_delete)) {
                parent::rollbackTransaction();
                parent::mappingDelegate($attachmentUI);
                return false;
            }

            if (!empty($id_attachment)) {
                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_ATTACHMENT_DELETE", "attachment"));
                return true;

            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_ATTACHMENT_DELETE", "attachment");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_ATTACHMENT_DELETE", "attachment");
            return false;
        }
    }

}
