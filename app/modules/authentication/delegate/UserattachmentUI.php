<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("UserattachmentBS", "modules/authentication/business");
App::uses("FileUtility", "modules/coreutils/utility");
App::uses("UserBS", "modules/authentication/business");
App::uses("AttachmentUI", "modules/resources/delegate");

class UserattachmentUI extends AppGenericUI {

    function __construct() {
        parent::__construct("UserattachmentUI");
        $this->localefile = "userattachment";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("flgprincipal", null, 0),
            new ObjPropertyEntity("attachment", null, 0),
            new ObjPropertyEntity("tpattachment", null, 0),
            new ObjPropertyEntity("user", null, 0),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_USERATTACHMENT_NOT_FOUND");
                return "";
            }
            $userattachmentBS = new UserattachmentBS();
            $userattachmentBS->json = $this->json;
            parent::completeByJsonFkVf($userattachmentBS);
            if (!empty($cod)) {
                $userattachmentBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $userattachmentBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_USERATTACHMENT_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $userattachmentBS = !empty($bs) ? $bs : new UserattachmentBS();
            $userattachmentBS->json = $this->json;
            parent::completeByJsonFkVf($userattachmentBS);
            parent::evalConditions($userattachmentBS, $conditions);
            parent::evalOrders($userattachmentBS, $orders);
            $userattachments = $userattachmentBS->table($conditions, $orders, $paginate);
            parent::evalPagination($userattachmentBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($userattachments);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($userattachmentIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $userattachment = DelegateUtility::getEntityToSave(new UserattachmentBS(), $userattachmentIn, $this->obj);

            if (!empty($userattachment)) {

                $userattachmentBS = new UserattachmentBS();
                $id_userattachment = $userattachmentBS->save($userattachment);
                parent::saveInGroup($userattachmentBS, $id_userattachment);

                parent::commitTransaction();
                if (!empty($id_userattachment)) {
                    $this->ok(TranslatorUtility::__translate("INFO_USERATTACHMENT_SAVE", $this->localefile));
                    return $id_userattachment;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_USERATTACHMENT_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_USERATTACHMENT_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_USERATTACHMENT_SAVE");
            return 0;
        }
    }

    function edit($id, $userattachmentIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $userattachment = DelegateUtility::getEntityToEdit(new UserattachmentBS(), $userattachmentIn, $this->obj, $id);

            if (!empty($userattachment)) {
                $userattachmentBS = new UserattachmentBS();
                $id_userattachment = $userattachmentBS->save($userattachment);
                parent::saveInGroup($userattachmentBS, $id_userattachment);
                parent::delInGroup($userattachmentBS, $id_userattachment);

                parent::commitTransaction();
                if (!empty($id_userattachment)) {
                    $this->ok(TranslatorUtility::__translate("INFO_USERATTACHMENT_EDIT", $this->localefile));
                    return $id_userattachment;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_USERATTACHMENT_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_USERATTACHMENT_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_USERATTACHMENT_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $userattachmentBS = new UserattachmentBS();
                $userattachmentBS->delete($id);
                parent::delInGroup($userattachmentBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_USERATTACHMENT_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_USERATTACHMENT_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_USERATTACHMENT_DELETE");
            return false;
        }
    }
    // ------------- PRINCIPAL
    function getPrincipal($id_user = null, $username = null, $type = null) {
        $this->LOG_FUNCTION = "getPrincipal";
        try {
            if (empty($id_user) && empty($username)) {
                DelegateUtility::paramsNull($this, "ERROR_USERATTACHMENT_NOT_FOUND");
                return "";
            }
            if (empty($type)) {
                DelegateUtility::paramsNull($this, "ERROR_USERATTACHMENT_NOT_FOUND");
                return "";
            }
            $id_user = DelegateUtility::getEntityIdByFields(new UserBS(), array(
                "username" => $username,
            ), $id_user);
            $userattachmentBS = new UserattachmentBS();
            $userattachmentBS->json = $this->json;
            parent::completeByJsonFkVf($userattachmentBS);

            $belongs = DelegateUtility::getObjList($this->json, $this->belongs, true);
            if (ArrayUtility::contains($belongs, "attachment_fk")) {
                DelegateUtility::excludeFieldsByQuery($userattachmentBS, array("tpattachment"));
                $userattachmentBS->addCondition("attachment_fk.tpattachment", $type);
            } else {
                $userattachmentBS->addCondition("tpattachment", $type);
            }

            $userattachmentBS->addCondition("user", $id_user);
            $userattachmentBS->addCondition("flgprincipal", 1);

            $this->ok();
            return $userattachmentBS->unique();
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_USERATTACHMENT_NOT_FOUND");
            return "";
        }
    }

    function setPrincipal($id_user = null, $username = null, $id_userattachment = null, $cod_userattachment = null, $type = null) {
        $this->LOG_FUNCTION = "setPrincipal";
        try {
            if ((empty($id_user) && empty($username)) || (empty($id_userattachment) && empty($cod_userattachment))) {
                DelegateUtility::paramsNull($this, "ERROR_USERATTACHMENT_SET_PRINCIPAL");
                return false;
            }
            if (empty($type)) {
                DelegateUtility::paramsNull($this, "ERROR_USERATTACHMENT_NOT_FOUND");
                return false;
            }
            $id_user = DelegateUtility::getEntityIdByFields(new UserBS(), array(
                "username" => $username,
            ), $id_user);

            $id_userattachment = DelegateUtility::getEntityIdByFields(new UserattachmentBS(), array(
                "cod" => $cod_userattachment,
                "tpattachment" => $type,
            ), $id_userattachment);

            parent::startTransaction();

            $userattachmentBS = new UserattachmentBS();
            $userattachmentBS->resetPrincipal($id_user, $type, $this->groups, $this->likegroups, $this->json);

            $userattachmentBS = new UserattachmentBS();
            $userattachmentBS->updateField($id_userattachment, "flgprincipal", 1);

            parent::commitTransaction();

            $this->ok(TranslatorUtility::__translate("INFO_USERATTACHMENT_SET_PRINCIPAL", $this->localefile));
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_USERATTACHMENT_SET_PRINCIPAL");
            return false;
        }
    }

    // ------------- RELATION
    function saveRelation($id_user, $attachmentIn, $tpattachment, $flgprincipal = false) {
        $this->LOG_FUNCTION = "saveRelation";
        try {
            parent::startTransaction();

            $attachmentUI = new AttachmentUI();
            $attachmentUI->json = $this->json;
            $attachmentUI->transactional = true;
            $id_attachment = $attachmentUI->save($attachmentIn);
            if (empty($id_attachment)) {
                parent::rollbackTransaction();
                parent::mappingDelegate($attachmentUI);
                return 0;
            }

            $userattachmentBS = new UserattachmentBS();
            $userattachment = $userattachmentBS->instance();
            $userattachment['Userattachment']['cod'] = FileUtility::uuid_medium_unique();
            $userattachment['Userattachment']['user'] = $id_user;
            $userattachment['Userattachment']['attachment'] = $id_attachment;
            $userattachment['Userattachment']['tpattachment'] = $tpattachment;
            $id_userattachment = $userattachmentBS->save($userattachment);
            parent::saveInGroup($userattachmentBS, $id_userattachment);

            if (!empty($id_userattachment)) {
                if ($flgprincipal) {
                    $userattachmentBS = new UserattachmentBS();
                    $userattachmentBS->resetPrincipal($id_user, $tpattachment);

                    $userattachmentBS = new UserattachmentBS();
                    $userattachmentBS->updateField($id_userattachment, "flgprincipal", 1);
                }
                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_USERATTACHMENT_SAVE", $this->localefile));
                return $id_userattachment;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::nonSalvato($this, "ERROR_USERATTACHMENT_SAVE");
                return 0;
            }

        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_USERATTACHMENT_SAVE");
            return 0;
        }
    }
}
