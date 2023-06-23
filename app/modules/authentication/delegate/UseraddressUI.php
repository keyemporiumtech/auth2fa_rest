<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("UseraddressBS", "modules/authentication/business");
App::uses("FileUtility", "modules/coreutils/utility");
App::uses("UserBS", "modules/authentication/business");
App::uses("AddressUI", "modules/localesystem/delegate");

class UseraddressUI extends AppGenericUI {

    function __construct() {
        parent::__construct("UseraddressUI");
        $this->localefile = "useraddress";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("flgprincipal", null, 0),
            new ObjPropertyEntity("address", null, 0),
            new ObjPropertyEntity("tpaddress", null, 0),
            new ObjPropertyEntity("user", null, 0),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_USERADDRESS_NOT_FOUND");
                return "";
            }
            $useraddressBS = new UseraddressBS();
            $useraddressBS->json = $this->json;
            parent::completeByJsonFkVf($useraddressBS);
            if (!empty($cod)) {
                $useraddressBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $useraddressBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_USERADDRESS_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $useraddressBS = !empty($bs) ? $bs : new UseraddressBS();
            $useraddressBS->json = $this->json;
            parent::completeByJsonFkVf($useraddressBS);
            parent::evalConditions($useraddressBS, $conditions);
            parent::evalOrders($useraddressBS, $orders);
            $useraddress = $useraddressBS->table($conditions, $orders, $paginate);
            parent::evalPagination($useraddressBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($useraddress);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($useraddressIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $useraddress = DelegateUtility::getEntityToSave(new UseraddressBS(), $useraddressIn, $this->obj);

            if (!empty($useraddress)) {

                $useraddressBS = new UseraddressBS();
                $id_useraddress = $useraddressBS->save($useraddress);
                parent::saveInGroup($useraddressBS, $id_useraddress);

                parent::commitTransaction();
                if (!empty($id_useraddress)) {
                    $this->ok(TranslatorUtility::__translate("INFO_USERADDRESS_SAVE", $this->localefile));
                    return $id_useraddress;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_USERADDRESS_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_USERADDRESS_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_USERADDRESS_SAVE");
            return 0;
        }
    }

    function edit($id, $useraddressIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $useraddress = DelegateUtility::getEntityToEdit(new UseraddressBS(), $useraddressIn, $this->obj, $id);

            if (!empty($useraddress)) {
                $useraddressBS = new UseraddressBS();
                $id_useraddress = $useraddressBS->save($useraddress);
                parent::saveInGroup($useraddressBS, $id_useraddress);
                parent::delInGroup($useraddressBS, $id_useraddress);

                parent::commitTransaction();
                if (!empty($id_useraddress)) {
                    $this->ok(TranslatorUtility::__translate("INFO_USERADDRESS_EDIT", $this->localefile));
                    return $id_useraddress;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_USERADDRESS_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_USERADDRESS_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_USERADDRESS_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $useraddressBS = new UseraddressBS();
                $useraddressBS->delete($id);
                parent::delInGroup($useraddressBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_USERADDRESS_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_USERADDRESS_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_USERADDRESS_DELETE");
            return false;
        }
    }

    // ------------- PRINCIPAL
    function getPrincipal($id_user = null, $username = null, $type = null) {
        $this->LOG_FUNCTION = "getPrincipal";
        try {
            if (empty($id_user) && empty($username)) {
                DelegateUtility::paramsNull($this, "ERROR_USERADDRESS_NOT_FOUND");
                return "";
            }
            if (empty($type)) {
                DelegateUtility::paramsNull($this, "ERROR_USERADDRESS_NOT_FOUND");
                return "";
            }
            $id_user = DelegateUtility::getEntityIdByFields(new UserBS(), array(
                "username" => $username,
            ), $id_user);
            $useraddressBS = new UseraddressBS();
            $useraddressBS->json = $this->json;
            parent::completeByJsonFkVf($useraddressBS);

            $belongs = DelegateUtility::getObjList($this->json, $this->belongs, true);
            if (ArrayUtility::contains($belongs, "address_fk")) {
                DelegateUtility::excludeFieldsByQuery($useraddressBS, array("tpaddress"));
                $useraddressBS->addCondition("address_fk.tpaddress", $type);
            } else {
                $useraddressBS->addCondition("tpaddress", $type);
            }

            $useraddressBS->addCondition("user", $id_user);
            $useraddressBS->addCondition("flgprincipal", 1);

            $this->ok();
            return $useraddressBS->unique();
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_USERADDRESS_NOT_FOUND");
            return "";
        }
    }

    function setPrincipal($id_user = null, $username = null, $id_useraddress = null, $cod_useraddress = null, $type = null) {
        $this->LOG_FUNCTION = "setPrincipal";
        try {
            if ((empty($id_user) && empty($username)) || (empty($id_useraddress) && empty($cod_useraddress))) {
                DelegateUtility::paramsNull($this, "ERROR_USERADDRESS_SET_PRINCIPAL");
                return false;
            }
            if (empty($type)) {
                DelegateUtility::paramsNull($this, "ERROR_USERADDRESS_NOT_FOUND");
                return false;
            }

            $id_user = DelegateUtility::getEntityIdByFields(new UserBS(), array(
                "username" => $username,
            ), $id_user);

            $id_useraddress = DelegateUtility::getEntityIdByFields(new UseraddressBS(), array(
                "cod" => $cod_useraddress,
                "tpattachment" => $type,
            ), $id_useraddress);

            parent::startTransaction();

            $useraddressBS = new UseraddressBS();
            $useraddressBS->resetPrincipal($id_user, $this->groups, $this->likegroups, $this->json);

            $useraddressBS = new UseraddressBS();
            $useraddressBS->updateField($id_useraddress, "flgprincipal", 1);

            parent::commitTransaction();

            $this->ok(TranslatorUtility::__translate("INFO_USERADDRESS_SET_PRINCIPAL", $this->localefile));
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_USERADDRESS_SET_PRINCIPAL");
            return false;
        }
    }

    // ------------- RELATION
    function saveRelation($id_user, $addressIn, $tpaddress, $flgprincipal = false) {
        $this->LOG_FUNCTION = "saveRelation";
        try {
            parent::startTransaction();

            $addressUI = new AddressUI();
            $addressUI->json = $this->json;
            $addressUI->transactional = true;
            $id_address = $addressUI->save($addressIn);
            if (empty($id_address)) {
                parent::rollbackTransaction();
                parent::mappingDelegate($addressUI);
                return 0;
            }

            $useraddressBS = new UseraddressBS();
            $useraddress = $useraddressBS->instance();
            $useraddress['Useraddress']['cod'] = FileUtility::uuid_medium_unique();
            $useraddress['Useraddress']['user'] = $id_user;
            $useraddress['Useraddress']['address'] = $id_address;
            $useraddress['Useraddress']['tpaddress'] = $tpaddress;
            $id_useraddress = $useraddressBS->save($useraddress);
            parent::saveInGroup($useraddressBS, $id_useraddress);

            if (!empty($id_useraddress)) {
                if ($flgprincipal) {
                    $useraddressBS = new UseraddressBS();
                    $useraddressBS->resetPrincipal($id_user);

                    $useraddressBS = new UseraddressBS();
                    $useraddressBS->updateField($id_useraddress, "flgprincipal", 1);
                }
                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_USERADDRESS_SAVE", $this->localefile));
                return $id_useraddress;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::nonSalvato($this, "ERROR_USERADDRESS_SAVE");
                return 0;
            }

        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_USERADDRESS_SAVE");
            return 0;
        }
    }
}
