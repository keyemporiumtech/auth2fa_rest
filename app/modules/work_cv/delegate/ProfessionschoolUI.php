<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("ProfessionschoolBS", "modules/work_cv/business");
App::uses("FileUtility", "modules/coreutils/utility");

class ProfessionschoolUI extends AppGenericUI {

    public function __construct() {
        parent::__construct("ProfessionschoolUI");
        $this->localefile = "professionschool";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("name", null, ""),
            new ObjPropertyEntity("description", null, ""),
            new ObjPropertyEntity("institute", null, 0),
            new ObjPropertyEntity("profession", null, 0),
            new ObjPropertyEntity("levelval", null, 0),
            new ObjPropertyEntity("levelmax", null, 0),
            new ObjPropertyEntity("leveldesc", null, ""),
            new ObjPropertyEntity("dtainit", null, ""),
            new ObjPropertyEntity("dtaend", null, ""),
        );
    }

    public function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_PROFESSIONSCHOOL_NOT_FOUND");
                return "";
            }
            $professionschoolBS = new ProfessionschoolBS();
            $professionschoolBS->json = $this->json;
            parent::completeByJsonFkVf($professionschoolBS);
            if (!empty($cod)) {
                $professionschoolBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $professionschoolBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_PROFESSIONSCHOOL_NOT_FOUND");
            return "";
        }
    }

    public function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $professionschoolBS = !empty($bs) ? $bs : new ProfessionschoolBS();
            $professionschoolBS->json = $this->json;
            parent::completeByJsonFkVf($professionschoolBS);
            parent::evalConditions($professionschoolBS, $conditions);
            parent::evalOrders($professionschoolBS, $orders);
            $professionschools = $professionschoolBS->table($conditions, $orders, $paginate);
            parent::evalPagination($professionschoolBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($professionschools);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    public function save($professionschoolIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $professionschool = DelegateUtility::getEntityToSave(new ProfessionschoolBS(), $professionschoolIn, $this->obj);

            if (!empty($professionschool)) {

                $professionschoolBS = new ProfessionschoolBS();
                $id_professionschool = $professionschoolBS->save($professionschool);
                parent::saveInGroup($professionschoolBS, $id_professionschool);

                parent::commitTransaction();
                if (!empty($id_professionschool)) {
                    $this->ok(TranslatorUtility::__translate("INFO_PROFESSIONSCHOOL_SAVE", $this->localefile));
                    return $id_professionschool;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_PROFESSIONSCHOOL_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_PROFESSIONSCHOOL_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PROFESSIONSCHOOL_SAVE");
            return 0;
        }
    }

    public function edit($id, $professionschoolIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $professionschool = DelegateUtility::getEntityToEdit(new ProfessionschoolBS(), $professionschoolIn, $this->obj, $id);

            if (!empty($professionschool)) {
                $professionschoolBS = new ProfessionschoolBS();
                $id_professionschool = $professionschoolBS->save($professionschool);
                parent::saveInGroup($professionschoolBS, $id_professionschool);
                parent::delInGroup($professionschoolBS, $id_professionschool);

                parent::commitTransaction();
                if (!empty($id_professionschool)) {
                    $this->ok(TranslatorUtility::__translate("INFO_PROFESSIONSCHOOL_EDIT", $this->localefile));
                    return $id_professionschool;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_PROFESSIONSCHOOL_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_PROFESSIONSCHOOL_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PROFESSIONSCHOOL_EDIT");
            return 0;
        }
    }

    public function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $professionschoolBS = new ProfessionschoolBS();
                $professionschoolBS->delete($id);
                parent::delInGroup($professionschoolBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_PROFESSIONSCHOOL_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_PROFESSIONSCHOOL_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PROFESSIONSCHOOL_DELETE");
            return false;
        }
    }
}
