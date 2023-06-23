<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("ProfessionreferenceBS", "modules/work_cv/business");
App::uses("FileUtility", "modules/coreutils/utility");

class ProfessionreferenceUI extends AppGenericUI {

    public function __construct() {
        parent::__construct("ProfessionreferenceUI");
        $this->localefile = "professionreference";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("profession", null, 0),
            new ObjPropertyEntity("contactreference", null, 0),
        );
    }

    public function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_PROFESSIONREFERENCE_NOT_FOUND");
                return "";
            }
            $professionreferenceBS = new ProfessionreferenceBS();
            $professionreferenceBS->json = $this->json;
            parent::completeByJsonFkVf($professionreferenceBS);
            if (!empty($cod)) {
                $professionreferenceBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $professionreferenceBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_PROFESSIONREFERENCE_NOT_FOUND");
            return "";
        }
    }

    public function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $professionreferenceBS = !empty($bs) ? $bs : new ProfessionreferenceBS();
            $professionreferenceBS->json = $this->json;
            parent::completeByJsonFkVf($professionreferenceBS);
            parent::evalConditions($professionreferenceBS, $conditions);
            parent::evalOrders($professionreferenceBS, $orders);
            $professionreferences = $professionreferenceBS->table($conditions, $orders, $paginate);
            parent::evalPagination($professionreferenceBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($professionreferences);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    public function save($professionreferenceIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $professionreference = DelegateUtility::getEntityToSave(new ProfessionreferenceBS(), $professionreferenceIn, $this->obj);

            if (!empty($professionreference)) {

                $professionreferenceBS = new ProfessionreferenceBS();
                $id_professionreference = $professionreferenceBS->save($professionreference);
                parent::saveInGroup($professionreferenceBS, $id_professionreference);

                parent::commitTransaction();
                if (!empty($id_professionreference)) {
                    $this->ok(TranslatorUtility::__translate("INFO_PROFESSIONREFERENCE_SAVE", $this->localefile));
                    return $id_professionreference;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_PROFESSIONREFERENCE_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_PROFESSIONREFERENCE_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PROFESSIONREFERENCE_SAVE");
            return 0;
        }
    }

    public function edit($id, $professionreferenceIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $professionreference = DelegateUtility::getEntityToEdit(new ProfessionreferenceBS(), $professionreferenceIn, $this->obj, $id);

            if (!empty($professionreference)) {
                $professionreferenceBS = new ProfessionreferenceBS();
                $id_professionreference = $professionreferenceBS->save($professionreference);
                parent::saveInGroup($professionreferenceBS, $id_professionreference);
                parent::delInGroup($professionreferenceBS, $id_professionreference);

                parent::commitTransaction();
                if (!empty($id_professionreference)) {
                    $this->ok(TranslatorUtility::__translate("INFO_PROFESSIONREFERENCE_EDIT", $this->localefile));
                    return $id_professionreference;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_PROFESSIONREFERENCE_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_PROFESSIONREFERENCE_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PROFESSIONREFERENCE_EDIT");
            return 0;
        }
    }

    public function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $professionreferenceBS = new ProfessionreferenceBS();
                $professionreferenceBS->delete($id);
                parent::delInGroup($professionreferenceBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_PROFESSIONREFERENCE_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_PROFESSIONREFERENCE_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PROFESSIONREFERENCE_DELETE");
            return false;
        }
    }
}
