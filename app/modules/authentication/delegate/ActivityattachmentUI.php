<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("ActivityattachmentBS", "modules/authentication/business");
App::uses("FileUtility", "modules/coreutils/utility");
App::uses("ActivityBS", "modules/authentication/business");
App::uses("AttachmentUI", "modules/resources/delegate");

class ActivityattachmentUI extends AppGenericUI {

    function __construct() {
        parent::__construct("ActivityattachmentUI");
        $this->localefile = "activityattachment";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("flgprincipal", null, 0),
            new ObjPropertyEntity("attachment", null, 0),
            new ObjPropertyEntity("activity", null, 0),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_ACTIVITYATTACHMENT_NOT_FOUND");
                return "";
            }
            $activityattachmentBS = new ActivityattachmentBS();
            $activityattachmentBS->json = $this->json;
            parent::completeByJsonFkVf($activityattachmentBS);
            if (!empty($cod)) {
                $activityattachmentBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $activityattachmentBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_ACTIVITYATTACHMENT_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $activityattachmentBS = !empty($bs) ? $bs : new ActivityattachmentBS();
            $activityattachmentBS->json = $this->json;
            parent::completeByJsonFkVf($activityattachmentBS);
            parent::evalConditions($activityattachmentBS, $conditions);
            parent::evalOrders($activityattachmentBS, $orders);
            $activityattachments = $activityattachmentBS->table($conditions, $orders, $paginate);
            parent::evalPagination($activityattachmentBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($activityattachments);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($activityattachmentIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $activityattachment = DelegateUtility::getEntityToSave(new ActivityattachmentBS(), $activityattachmentIn, $this->obj);

            if (!empty($activityattachment)) {

                $activityattachmentBS = new ActivityattachmentBS();
                $id_activityattachment = $activityattachmentBS->save($activityattachment);
                parent::saveInGroup($activityattachmentBS, $id_activityattachment);

                parent::commitTransaction();
                if (!empty($id_activityattachment)) {
                    $this->ok(TranslatorUtility::__translate("INFO_ACTIVITYATTACHMENT_SAVE", $this->localefile));
                    return $id_activityattachment;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_ACTIVITYATTACHMENT_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_ACTIVITYATTACHMENT_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_ACTIVITYATTACHMENT_SAVE");
            return 0;
        }
    }

    function edit($id, $activityattachmentIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $activityattachment = DelegateUtility::getEntityToEdit(new ActivityattachmentBS(), $activityattachmentIn, $this->obj, $id);

            if (!empty($activityattachment)) {
                $activityattachmentBS = new ActivityattachmentBS();
                $id_activityattachment = $activityattachmentBS->save($activityattachment);
                parent::saveInGroup($activityattachmentBS, $id_activityattachment);
                parent::delInGroup($activityattachmentBS, $id_activityattachment);

                parent::commitTransaction();
                if (!empty($id_activityattachment)) {
                    $this->ok(TranslatorUtility::__translate("INFO_ACTIVITYATTACHMENT_EDIT", $this->localefile));
                    return $id_activityattachment;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_ACTIVITYATTACHMENT_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_ACTIVITYATTACHMENT_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_ACTIVITYATTACHMENT_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $activityattachmentBS = new ActivityattachmentBS();
                $activityattachmentBS->delete($id);
                parent::delInGroup($activityattachmentBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_ACTIVITYATTACHMENT_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_ACTIVITYATTACHMENT_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_ACTIVITYATTACHMENT_DELETE");
            return false;
        }
    }

    // ------------- PRINCIPAL
    function getPrincipal($id_activity = null, $piva = null, $type = null) {
        $this->LOG_FUNCTION = "getPrincipal";
        try {
            if (empty($id_activity) && empty($piva)) {
                DelegateUtility::paramsNull($this, "ERROR_ACTIVITYATTACHMENT_NOT_FOUND");
                return "";
            }
            if (empty($type)) {
                DelegateUtility::paramsNull($this, "ERROR_ACTIVITYATTACHMENT_NOT_FOUND");
                return "";
            }
            $id_activity = DelegateUtility::getEntityIdByFields(new ActivityBS(), array(
                "piva" => $piva,
            ), $id_activity);
            $activityattachmentBS = new ActivityattachmentBS();
            $activityattachmentBS->json = $this->json;
            parent::completeByJsonFkVf($activityattachmentBS);
            $activityattachmentBS->addCondition("activity", $id_activity);
            $activityattachmentBS->addCondition("tpattachment", $type);
            $activityattachmentBS->addCondition("flgprincipal", 1);

            $this->ok();
            return $activityattachmentBS->unique();
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_ACTIVITYATTACHMENT_NOT_FOUND");
            return "";
        }
    }

    function setPrincipal($id_activity = null, $piva = null, $id_activityattachment = null, $cod_activityattachment = null, $type = null) {
        $this->LOG_FUNCTION = "setPrincipal";
        try {
            if ((empty($id_activity) && empty($piva)) || (empty($id_activityattachment) && empty($cod_activityattachment))) {
                DelegateUtility::paramsNull($this, "ERROR_ACTIVITYATTACHMENT_SET_PRINCIPAL");
                return false;
            }
            if (empty($type)) {
                DelegateUtility::paramsNull($this, "ERROR_ACTIVITYATTACHMENT_NOT_FOUND");
                return "";
            }
            $id_activity = DelegateUtility::getEntityIdByFields(new ActivityBS(), array(
                "piva" => $piva,
            ), $id_activity);

            $id_activityattachment = DelegateUtility::getEntityIdByFields(new ActivityattachmentBS(), array(
                "cod" => $cod_activityattachment,
                "tpattachment" => $type,
            ), $id_activityattachment);

            parent::startTransaction();

            $activityattachmentBS = new ActivityattachmentBS();
            $activityattachmentBS->resetPrincipal($id_activity, $type, $this->groups, $this->likegroups, $this->json);

            $activityattachmentBS = new ActivityattachmentBS();
            $activityattachmentBS->updateField($id_activityattachment, "flgprincipal", 1);

            parent::commitTransaction();

            $this->ok(TranslatorUtility::__translate("INFO_ACTIVITYATTACHMENT_SET_PRINCIPAL", $this->localefile));
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_ACTIVITYATTACHMENT_SET_PRINCIPAL");
            return false;
        }
    }

    // ------------- RELATION
    function saveRelation($id_activity, $attachmentIn, $tpattachment, $flgprincipal = false) {
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

            $activityattachmentBS = new ActivityattachmentBS();
            $activityattachment = $activityattachmentBS->instance();
            $activityattachment['Activityattachment']['cod'] = FileUtility::uuid_medium_unique();
            $activityattachment['Activityattachment']['activity'] = $id_activity;
            $activityattachment['Activityattachment']['attachment'] = $id_attachment;
            $activityattachment['Activityattachment']['tpattachment'] = $tpattachment;
            $id_activityattachment = $activityattachmentBS->save($activityattachment);
            parent::saveInGroup($activityattachmentBS, $id_activityattachment);

            if (!empty($id_activityattachment)) {
                if ($flgprincipal) {
                    $activityattachmentBS = new ActivityattachmentBS();
                    $activityattachmentBS->resetPrincipal($id_activity, $tpattachment);

                    $activityattachmentBS = new ActivityattachmentBS();
                    $activityattachmentBS->updateField($id_activityattachment, "flgprincipal", 1);
                }
                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_ACTIVITYATTACHMENT_SAVE", $this->localefile));
                return $id_activityattachment;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::nonSalvato($this, "ERROR_ACTIVITYATTACHMENT_SAVE");
                return 0;
            }

        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_ACTIVITYATTACHMENT_SAVE");
            return 0;
        }
    }
}
