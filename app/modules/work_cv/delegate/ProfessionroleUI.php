<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("ProfessionroleBS", "modules/work_cv/business");
App::uses("FileUtility", "modules/coreutils/utility");

class ProfessionroleUI extends AppGenericUI {

    public function __construct() {
        parent::__construct("ProfessionroleUI");
        $this->localefile = "professionrole";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("months", null, 0.0),
            new ObjPropertyEntity("profession", null, 0),
            new ObjPropertyEntity("role", null, 0),
        );
    }

    public function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_PROFESSIONROLE_NOT_FOUND");
                return "";
            }
            $professionroleBS = new ProfessionroleBS();
            $professionroleBS->json = $this->json;
            parent::completeByJsonFkVf($professionroleBS);
            if (!empty($cod)) {
                $professionroleBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $professionroleBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_PROFESSIONROLE_NOT_FOUND");
            return "";
        }
    }

    public function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $professionroleBS = !empty($bs) ? $bs : new ProfessionroleBS();
            $professionroleBS->json = $this->json;
            parent::completeByJsonFkVf($professionroleBS);
            parent::evalConditions($professionroleBS, $conditions);
            parent::evalOrders($professionroleBS, $orders);
            $professionroles = $professionroleBS->table($conditions, $orders, $paginate);
            parent::evalPagination($professionroleBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($professionroles);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    public function save($professionroleIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $professionrole = DelegateUtility::getEntityToSave(new ProfessionroleBS(), $professionroleIn, $this->obj);

            if (!empty($professionrole)) {

                $professionroleBS = new ProfessionroleBS();
                $id_professionrole = $professionroleBS->save($professionrole);
                parent::saveInGroup($professionroleBS, $id_professionrole);

                parent::commitTransaction();
                if (!empty($id_professionrole)) {
                    $this->ok(TranslatorUtility::__translate("INFO_PROFESSIONROLE_SAVE", $this->localefile));
                    return $id_professionrole;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_PROFESSIONROLE_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_PROFESSIONROLE_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PROFESSIONROLE_SAVE");
            return 0;
        }
    }

    public function edit($id, $professionroleIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $professionrole = DelegateUtility::getEntityToEdit(new ProfessionroleBS(), $professionroleIn, $this->obj, $id);

            if (!empty($professionrole)) {
                $professionroleBS = new ProfessionroleBS();
                $id_professionrole = $professionroleBS->save($professionrole);
                parent::saveInGroup($professionroleBS, $id_professionrole);
                parent::delInGroup($professionroleBS, $id_professionrole);

                parent::commitTransaction();
                if (!empty($id_professionrole)) {
                    $this->ok(TranslatorUtility::__translate("INFO_PROFESSIONROLE_EDIT", $this->localefile));
                    return $id_professionrole;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_PROFESSIONROLE_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_PROFESSIONROLE_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PROFESSIONROLE_EDIT");
            return 0;
        }
    }

    public function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $professionroleBS = new ProfessionroleBS();
                $professionroleBS->delete($id);
                parent::delInGroup($professionroleBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_PROFESSIONROLE_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_PROFESSIONROLE_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PROFESSIONROLE_DELETE");
            return false;
        }
    }
}
