<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("ProfessionattachmentBS", "modules/work_cv/business");
App::uses("FileUtility", "modules/coreutils/utility");

class ProfessionattachmentUI extends AppGenericUI {

    public function __construct() {
        parent::__construct("ProfessionattachmentUI");
        $this->localefile = "professionattachment";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("profession", null, 0),
            new ObjPropertyEntity("attachment", null, 0),
        );
    }

    public function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_PROFESSIONATTACHMENT_NOT_FOUND");
                return "";
            }
            $professionattachmentBS = new ProfessionattachmentBS();
            $professionattachmentBS->json = $this->json;
            parent::completeByJsonFkVf($professionattachmentBS);
            if (!empty($cod)) {
                $professionattachmentBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $professionattachmentBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_PROFESSIONATTACHMENT_NOT_FOUND");
            return "";
        }
    }

    public function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $professionattachmentBS = !empty($bs) ? $bs : new ProfessionattachmentBS();
            $professionattachmentBS->json = $this->json;
            parent::completeByJsonFkVf($professionattachmentBS);
            parent::evalConditions($professionattachmentBS, $conditions);
            parent::evalOrders($professionattachmentBS, $orders);
            $professionattachments = $professionattachmentBS->table($conditions, $orders, $paginate);
            parent::evalPagination($professionattachmentBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($professionattachments);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    public function save($professionattachmentIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $professionattachment = DelegateUtility::getEntityToSave(new ProfessionattachmentBS(), $professionattachmentIn, $this->obj);

            if (!empty($professionattachment)) {

                $professionattachmentBS = new ProfessionattachmentBS();
                $id_professionattachment = $professionattachmentBS->save($professionattachment);
                parent::saveInGroup($professionattachmentBS, $id_professionattachment);

                parent::commitTransaction();
                if (!empty($id_professionattachment)) {
                    $this->ok(TranslatorUtility::__translate("INFO_PROFESSIONATTACHMENT_SAVE", $this->localefile));
                    return $id_professionattachment;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_PROFESSIONATTACHMENT_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_PROFESSIONATTACHMENT_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PROFESSIONATTACHMENT_SAVE");
            return 0;
        }
    }

    public function edit($id, $professionattachmentIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $professionattachment = DelegateUtility::getEntityToEdit(new ProfessionattachmentBS(), $professionattachmentIn, $this->obj, $id);

            if (!empty($professionattachment)) {
                $professionattachmentBS = new ProfessionattachmentBS();
                $id_professionattachment = $professionattachmentBS->save($professionattachment);
                parent::saveInGroup($professionattachmentBS, $id_professionattachment);
                parent::delInGroup($professionattachmentBS, $id_professionattachment);

                parent::commitTransaction();
                if (!empty($id_professionattachment)) {
                    $this->ok(TranslatorUtility::__translate("INFO_PROFESSIONATTACHMENT_EDIT", $this->localefile));
                    return $id_professionattachment;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_PROFESSIONATTACHMENT_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_PROFESSIONATTACHMENT_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PROFESSIONATTACHMENT_EDIT");
            return 0;
        }
    }

    public function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $professionattachmentBS = new ProfessionattachmentBS();
                $professionattachmentBS->delete($id);
                parent::delInGroup($professionattachmentBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_PROFESSIONATTACHMENT_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_PROFESSIONATTACHMENT_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PROFESSIONATTACHMENT_DELETE");
            return false;
        }
    }
}
