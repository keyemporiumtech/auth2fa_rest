<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("UserreferenceBS", "modules/authentication/business");
App::uses("FileUtility", "modules/coreutils/utility");
App::uses("UserBS", "modules/authentication/business");
App::uses("ContactreferenceUI", "modules/authentication/delegate");

class UserreferenceUI extends AppGenericUI {

    function __construct() {
        parent::__construct("UserreferenceUI");
        $this->localefile = "userreference";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("flgprincipal", null, 0),
            new ObjPropertyEntity("contactreference", null, 0),
            new ObjPropertyEntity("tpcontactreference", null, 0),
            new ObjPropertyEntity("user", null, 0),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_USERREFERENCE_NOT_FOUND");
                return "";
            }
            $userreferenceBS = new UserreferenceBS();
            $userreferenceBS->json = $this->json;
            parent::completeByJsonFkVf($userreferenceBS);
            if (!empty($cod)) {
                $userreferenceBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $userreferenceBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_USERREFERENCE_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $userreferenceBS = !empty($bs) ? $bs : new UserreferenceBS();
            $userreferenceBS->json = $this->json;
            parent::completeByJsonFkVf($userreferenceBS);
            parent::evalConditions($userreferenceBS, $conditions);
            parent::evalOrders($userreferenceBS, $orders);
            $userreferences = $userreferenceBS->table($conditions, $orders, $paginate);
            parent::evalPagination($userreferenceBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($userreferences);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($userreferenceIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $userreference = DelegateUtility::getEntityToSave(new UserreferenceBS(), $userreferenceIn, $this->obj);

            if (!empty($userreference)) {

                $userreferenceBS = new UserreferenceBS();
                $id_userreference = $userreferenceBS->save($userreference);
                parent::saveInGroup($userreferenceBS, $id_userreference);

                parent::commitTransaction();
                if (!empty($id_userreference)) {
                    $this->ok(TranslatorUtility::__translate("INFO_USERREFERENCE_SAVE", $this->localefile));
                    return $id_userreference;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_USERREFERENCE_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_USERREFERENCE_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_USERREFERENCE_SAVE");
            return 0;
        }
    }

    function edit($id, $userreferenceIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $userreference = DelegateUtility::getEntityToEdit(new UserreferenceBS(), $userreferenceIn, $this->obj, $id);

            if (!empty($userreference)) {
                $userreferenceBS = new UserreferenceBS();
                $id_userreference = $userreferenceBS->save($userreference);
                parent::saveInGroup($userreferenceBS, $id_userreference);
                parent::delInGroup($userreferenceBS, $id_userreference);

                parent::commitTransaction();
                if (!empty($id_userreference)) {
                    $this->ok(TranslatorUtility::__translate("INFO_USERREFERENCE_EDIT", $this->localefile));
                    return $id_userreference;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_USERREFERENCE_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_USERREFERENCE_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_USERREFERENCE_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $userreferenceBS = new UserreferenceBS();
                $userreferenceBS->delete($id);
                parent::delInGroup($userreferenceBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_USERREFERENCE_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_USERREFERENCE_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_USERREFERENCE_DELETE");
            return false;
        }
    }

    // ------------- PRINCIPAL
    function getPrincipal($id_user = null, $username = null, $type = null) {
        $this->LOG_FUNCTION = "getPrincipal";
        try {
            if (empty($id_user) && empty($username)) {
                DelegateUtility::paramsNull($this, "ERROR_USERREFERENCE_NOT_FOUND");
                return "";
            }
            if (empty($type)) {
                DelegateUtility::paramsNull($this, "ERROR_USERREFERENCE_NOT_FOUND");
                return "";
            }
            $id_user = DelegateUtility::getEntityIdByFields(new UserBS(), array(
                "username" => $username,
            ), $id_user);
            $userreferenceBS = new UserreferenceBS();
            $userreferenceBS->json = $this->json;
            parent::completeByJsonFkVf($userreferenceBS);

            $belongs = DelegateUtility::getObjList($this->json, $this->belongs, true);
            if (ArrayUtility::contains($belongs, "contactreference_fk")) {
                DelegateUtility::excludeFieldsByQuery($userreferenceBS, array("tpcontactreference"));
                $userreferenceBS->addCondition("contactreference_fk.tpcontactreference", $type);
            } else {
                $userreferenceBS->addCondition("tpcontactreference", $type);
            }

            $userreferenceBS->addCondition("user", $id_user);
            $userreferenceBS->addCondition("flgprincipal", 1);

            $this->ok();
            return $userreferenceBS->unique();
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_USERREFERENCE_NOT_FOUND");
            return "";
        }
    }

    function setPrincipal($id_user = null, $username = null, $id_userreference = null, $cod_userreference = null, $type = null) {
        $this->LOG_FUNCTION = "setPrincipal";
        try {
            if ((empty($id_user) && empty($username)) || (empty($id_userreference) && empty($cod_userreference))) {
                DelegateUtility::paramsNull($this, "ERROR_USERREFERENCE_SET_PRINCIPAL");
                return false;
            }
            if (empty($type)) {
                DelegateUtility::paramsNull($this, "ERROR_USERREFERENCE_NOT_FOUND");
                return "";
            }
            $id_user = DelegateUtility::getEntityIdByFields(new UserBS(), array(
                "username" => $username,
            ), $id_user);

            $id_userreference = DelegateUtility::getEntityIdByFields(new UserreferenceBS(), array(
                "cod" => $cod_userreference,
                "tpcontactreference" => $type,
            ), $id_userreference);

            parent::startTransaction();

            $userreferenceBS = new UserreferenceBS();
            $userreferenceBS->resetPrincipal($id_user, $type, $this->groups, $this->likegroups, $this->json);

            $userreferenceBS = new UserreferenceBS();
            $userreferenceBS->updateField($id_userreference, "flgprincipal", 1);

            parent::commitTransaction();

            $this->ok(TranslatorUtility::__translate("INFO_USERREFERENCE_SET_PRINCIPAL", $this->localefile));
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_USERREFERENCE_SET_PRINCIPAL");
            return false;
        }
    }

    // ------------- RELATION
    function saveRelation($id_user, $contactreferenceIn, $tpcontactreference, $flgprincipal = false) {
        $this->LOG_FUNCTION = "saveRelation";
        try {
            parent::startTransaction();

            $contactreferenceUI = new ContactreferenceUI();
            $contactreferenceUI->json = $this->json;
            $contactreferenceUI->transactional = true;
            $id_contactreference = $contactreferenceUI->save($contactreferenceIn);
            if (empty($id_contactreference)) {
                parent::rollbackTransaction();
                parent::mappingDelegate($contactreferenceUI);
                return 0;
            }

            $usercontactreferenceBS = new UserreferenceBS();
            $usercontactreference = $usercontactreferenceBS->instance();
            $usercontactreference['Userreference']['cod'] = FileUtility::uuid_medium_unique();
            $usercontactreference['Userreference']['user'] = $id_user;
            $usercontactreference['Userreference']['contactreference'] = $id_contactreference;
            $usercontactreference['Userreference']['tpcontactreference'] = $tpcontactreference;
            $id_usercontactreference = $usercontactreferenceBS->save($usercontactreference);
            parent::saveInGroup($usercontactreferenceBS, $id_usercontactreference);

            if (!empty($id_usercontactreference)) {
                if ($flgprincipal) {
                    $userreferenceBS = new UserreferenceBS();
                    $userreferenceBS->resetPrincipal($id_user, $tpcontactreference);

                    $userreferenceBS = new UserreferenceBS();
                    $userreferenceBS->updateField($id_usercontactreference, "flgprincipal", 1);
                }
                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_USERREFERENCE_SAVE", $this->localefile));
                return $id_usercontactreference;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::nonSalvato($this, "ERROR_USERREFERENCE_SAVE");
                return 0;
            }

        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_USERREFERENCE_SAVE");
            return 0;
        }
    }
}
