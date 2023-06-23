<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("GroupBS", "modules/cakeutils/business");
App::uses("FileUtility", "modules/coreutils/utility");

class GroupUI extends AppGenericUI {

    function __construct() {
        parent::__construct("GroupUI");
        $this->localefile = "group";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("title", null, ""),
            new ObjPropertyEntity("description", null, ""),
            new ObjPropertyEntity("user", null, 0),
            new ObjPropertyEntity("activity", null, 0),
            new ObjPropertyEntity("symbol", null, ""),
            new ObjPropertyEntity("flgused", null, 0),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_GROUP_NOT_FOUND");
                return "";
            }
            $groupBS = new GroupBS();
            $groupBS->json = $this->json;
            parent::completeByJsonFkVf($groupBS);
            if (!empty($cod)) {
                $groupBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $groupBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_GROUP_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $groupBS = !empty($bs) ? $bs : new GroupBS();
            $groupBS->json = $this->json;
            parent::completeByJsonFkVf($groupBS);
            parent::evalConditions($groupBS, $conditions);
            parent::evalOrders($groupBS, $orders);
            $groups = $groupBS->table($conditions, $orders, $paginate);
            parent::evalPagination($groupBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($groups);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($groupIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $group = DelegateUtility::getEntityToSave(new GroupBS(), $groupIn, $this->obj);

            if (!empty($group)) {

                $groupBS = new GroupBS();
                $id_group = $groupBS->save($group);
                parent::saveInGroup($groupBS, $id_group);

                parent::commitTransaction();
                if (!empty($id_group)) {
                    $this->ok(TranslatorUtility::__translate("INFO_GROUP_SAVE", $this->localefile));
                    return $id_group;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_GROUP_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_GROUP_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_GROUP_SAVE");
            return 0;
        }
    }

    function edit($id, $groupIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $group = DelegateUtility::getEntityToEdit(new GroupBS(), $groupIn, $this->obj, $id);

            if (!empty($group)) {
                $groupBS = new GroupBS();
                $id_group = $groupBS->save($group);
                parent::saveInGroup($groupBS, $id_group);
                parent::delInGroup($groupBS, $id_group);

                parent::commitTransaction();
                if (!empty($id_group)) {
                    $this->ok(TranslatorUtility::__translate("INFO_GROUP_EDIT", $this->localefile));
                    return $id_group;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_GROUP_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_GROUP_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_GROUP_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $groupBS = new GroupBS();
                $groupBS->delete($id);
                parent::delInGroup($groupBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_GROUP_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_GROUP_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_GROUP_DELETE");
            return false;
        }
    }
}
