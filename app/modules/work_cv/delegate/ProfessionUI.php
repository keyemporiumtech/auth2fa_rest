<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("ProfessionBS", "modules/work_cv/business");
App::uses("FileUtility", "modules/coreutils/utility");

class ProfessionUI extends AppGenericUI {

    public function __construct() {
        parent::__construct("ProfessionUI");
        $this->localefile = "profession";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("name", null, ""),
            new ObjPropertyEntity("description", null, ""),
            new ObjPropertyEntity("user", null, 0),
            new ObjPropertyEntity("address", null, 0),
            new ObjPropertyEntity("email", null, 0),
            new ObjPropertyEntity("phone", null, 0),
            new ObjPropertyEntity("image", null, 0),
        );
    }

    public function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_PROFESSION_NOT_FOUND");
                return "";
            }
            $professionBS = new ProfessionBS();
            $professionBS->json = $this->json;
            parent::completeByJsonFkVf($professionBS);
            if (!empty($cod)) {
                $professionBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $professionBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_PROFESSION_NOT_FOUND");
            return "";
        }
    }

    public function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $professionBS = !empty($bs) ? $bs : new ProfessionBS();
            $professionBS->json = $this->json;
            parent::completeByJsonFkVf($professionBS);
            parent::evalConditions($professionBS, $conditions);
            parent::evalOrders($professionBS, $orders);
            $professions = $professionBS->table($conditions, $orders, $paginate);
            parent::evalPagination($professionBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($professions);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    public function save($professionIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $profession = DelegateUtility::getEntityToSave(new ProfessionBS(), $professionIn, $this->obj);

            if (!empty($profession)) {

                $professionBS = new ProfessionBS();
                $id_profession = $professionBS->save($profession);
                parent::saveInGroup($professionBS, $id_profession);

                parent::commitTransaction();
                if (!empty($id_profession)) {
                    $this->ok(TranslatorUtility::__translate("INFO_PROFESSION_SAVE", $this->localefile));
                    return $id_profession;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_PROFESSION_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_PROFESSION_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PROFESSION_SAVE");
            return 0;
        }
    }

    public function edit($id, $professionIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $profession = DelegateUtility::getEntityToEdit(new ProfessionBS(), $professionIn, $this->obj, $id);

            if (!empty($profession)) {
                $professionBS = new ProfessionBS();
                $id_profession = $professionBS->save($profession);
                parent::saveInGroup($professionBS, $id_profession);
                parent::delInGroup($professionBS, $id_profession);

                parent::commitTransaction();
                if (!empty($id_profession)) {
                    $this->ok(TranslatorUtility::__translate("INFO_PROFESSION_EDIT", $this->localefile));
                    return $id_profession;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_PROFESSION_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_PROFESSION_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PROFESSION_EDIT");
            return 0;
        }
    }

    public function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $professionBS = new ProfessionBS();
                $professionBS->delete($id);
                parent::delInGroup($professionBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_PROFESSION_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_PROFESSION_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PROFESSION_DELETE");
            return false;
        }
    }

    // --------------------PDF
    public function pdf($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            $profession = $this->get($id, $cod);
            if (empty($profession)) {
                DelegateUtility::paramsNull($this, "ERROR_PROFESSION_NOT_FOUND");
                return "";
            }
            $this->ok();
            return "";
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_PROFESSION_NOT_FOUND");
            return "";
        }
    }
}
