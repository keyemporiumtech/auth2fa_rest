<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("ProfessionexperienceBS", "modules/work_cv/business");
App::uses("FileUtility", "modules/coreutils/utility");

class ProfessionexperienceUI extends AppGenericUI {

    public function __construct() {
        parent::__construct("ProfessionexperienceUI");
        $this->localefile = "professionexperience";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("profession", null, 0),
            new ObjPropertyEntity("experience", null, 0),
        );
    }

    public function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_PROFESSIONEXPERIENCE_NOT_FOUND");
                return "";
            }
            $professionexperienceBS = new ProfessionexperienceBS();
            $professionexperienceBS->json = $this->json;
            parent::completeByJsonFkVf($professionexperienceBS);
            if (!empty($cod)) {
                $professionexperienceBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $professionexperienceBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_PROFESSIONEXPERIENCE_NOT_FOUND");
            return "";
        }
    }

    public function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $professionexperienceBS = !empty($bs) ? $bs : new ProfessionexperienceBS();
            $professionexperienceBS->json = $this->json;
            parent::completeByJsonFkVf($professionexperienceBS);
            parent::evalConditions($professionexperienceBS, $conditions);
            parent::evalOrders($professionexperienceBS, $orders);
            $professionexperiences = $professionexperienceBS->table($conditions, $orders, $paginate);
            parent::evalPagination($professionexperienceBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($professionexperiences);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    public function save($professionexperienceIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $professionexperience = DelegateUtility::getEntityToSave(new ProfessionexperienceBS(), $professionexperienceIn, $this->obj);

            if (!empty($professionexperience)) {

                $professionexperienceBS = new ProfessionexperienceBS();
                $id_professionexperience = $professionexperienceBS->save($professionexperience);
                parent::saveInGroup($professionexperienceBS, $id_professionexperience);

                parent::commitTransaction();
                if (!empty($id_professionexperience)) {
                    $this->ok(TranslatorUtility::__translate("INFO_PROFESSIONEXPERIENCE_SAVE", $this->localefile));
                    return $id_professionexperience;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_PROFESSIONEXPERIENCE_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_PROFESSIONEXPERIENCE_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PROFESSIONEXPERIENCE_SAVE");
            return 0;
        }
    }

    public function edit($id, $professionexperienceIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $professionexperience = DelegateUtility::getEntityToEdit(new ProfessionexperienceBS(), $professionexperienceIn, $this->obj, $id);

            if (!empty($professionexperience)) {
                $professionexperienceBS = new ProfessionexperienceBS();
                $id_professionexperience = $professionexperienceBS->save($professionexperience);
                parent::saveInGroup($professionexperienceBS, $id_professionexperience);
                parent::delInGroup($professionexperienceBS, $id_professionexperience);

                parent::commitTransaction();
                if (!empty($id_professionexperience)) {
                    $this->ok(TranslatorUtility::__translate("INFO_PROFESSIONEXPERIENCE_EDIT", $this->localefile));
                    return $id_professionexperience;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_PROFESSIONEXPERIENCE_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_PROFESSIONEXPERIENCE_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PROFESSIONEXPERIENCE_EDIT");
            return 0;
        }
    }

    public function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $professionexperienceBS = new ProfessionexperienceBS();
                $professionexperienceBS->delete($id);
                parent::delInGroup($professionexperienceBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_PROFESSIONEXPERIENCE_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_PROFESSIONEXPERIENCE_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PROFESSIONEXPERIENCE_DELETE");
            return false;
        }
    }
}
