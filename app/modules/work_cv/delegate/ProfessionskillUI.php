<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("ProfessionskillBS", "modules/work_cv/business");
App::uses("FileUtility", "modules/coreutils/utility");

class ProfessionskillUI extends AppGenericUI {

    public function __construct() {
        parent::__construct("ProfessionskillUI");
        $this->localefile = "professionskill";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("months", null, 0.0),
            new ObjPropertyEntity("profession", null, 0),
            new ObjPropertyEntity("skill", null, 0),
            new ObjPropertyEntity("levelval", null, 0),
        );
    }

    public function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_PROFESSIONSKILL_NOT_FOUND");
                return "";
            }
            $professionskillBS = new ProfessionskillBS();
            $professionskillBS->json = $this->json;
            parent::completeByJsonFkVf($professionskillBS);
            if (!empty($cod)) {
                $professionskillBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $professionskillBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_PROFESSIONSKILL_NOT_FOUND");
            return "";
        }
    }

    public function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $professionskillBS = !empty($bs) ? $bs : new ProfessionskillBS();
            $professionskillBS->json = $this->json;
            parent::completeByJsonFkVf($professionskillBS);
            parent::evalConditions($professionskillBS, $conditions);
            parent::evalOrders($professionskillBS, $orders);
            $professionskills = $professionskillBS->table($conditions, $orders, $paginate);
            parent::evalPagination($professionskillBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($professionskills);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    public function save($professionskillIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $professionskill = DelegateUtility::getEntityToSave(new ProfessionskillBS(), $professionskillIn, $this->obj);

            if (!empty($professionskill)) {

                $professionskillBS = new ProfessionskillBS();
                $id_professionskill = $professionskillBS->save($professionskill);
                parent::saveInGroup($professionskillBS, $id_professionskill);

                parent::commitTransaction();
                if (!empty($id_professionskill)) {
                    $this->ok(TranslatorUtility::__translate("INFO_PROFESSIONSKILL_SAVE", $this->localefile));
                    return $id_professionskill;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_PROFESSIONSKILL_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_PROFESSIONSKILL_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PROFESSIONSKILL_SAVE");
            return 0;
        }
    }

    public function edit($id, $professionskillIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $professionskill = DelegateUtility::getEntityToEdit(new ProfessionskillBS(), $professionskillIn, $this->obj, $id);

            if (!empty($professionskill)) {
                $professionskillBS = new ProfessionskillBS();
                $id_professionskill = $professionskillBS->save($professionskill);
                parent::saveInGroup($professionskillBS, $id_professionskill);
                parent::delInGroup($professionskillBS, $id_professionskill);

                parent::commitTransaction();
                if (!empty($id_professionskill)) {
                    $this->ok(TranslatorUtility::__translate("INFO_PROFESSIONSKILL_EDIT", $this->localefile));
                    return $id_professionskill;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_PROFESSIONSKILL_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_PROFESSIONSKILL_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PROFESSIONSKILL_EDIT");
            return 0;
        }
    }

    public function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $professionskillBS = new ProfessionskillBS();
                $professionskillBS->delete($id);
                parent::delInGroup($professionskillBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_PROFESSIONSKILL_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_PROFESSIONSKILL_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PROFESSIONSKILL_DELETE");
            return false;
        }
    }
}
