<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("WorkactivityBS", "modules/work_profile/business");
App::uses("FileUtility", "modules/coreutils/utility");
App::uses("ActivityBS", "modules/authentication/business");
App::uses("ActivityattachmentBS", "modules/authentication/business");
App::uses("EnumAttachmentType", "modules/resources/config");
App::uses("ActivityaddressBS", "modules/authentication/business");
App::uses("EnumAddressType", "modules/localesystem/config");
App::uses("ActivityreferenceBS", "modules/authentication/business");
App::uses("EnumContactreferenceType", "modules/authentication/config");

class WorkactivityUI extends AppGenericUI {

    function __construct() {
        parent::__construct("WorkactivityUI");
        $this->localefile = "workactivity";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("activity", null, 0),
            new ObjPropertyEntity("image", null, 0),
            new ObjPropertyEntity("home", null, 0),
            new ObjPropertyEntity("email", null, 0),
            new ObjPropertyEntity("phone", null, 0),
            new ObjPropertyEntity("website", null, 0),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_WORKACTIVITY_NOT_FOUND");
                return "";
            }
            $workactivityBS = new WorkactivityBS();
            $workactivityBS->json = $this->json;
            parent::completeByJsonFkVf($workactivityBS);
            if (!empty($cod)) {
                $workactivityBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $workactivityBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_WORKACTIVITY_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $workactivityBS = !empty($bs) ? $bs : new WorkactivityBS();
            $workactivityBS->json = $this->json;
            parent::completeByJsonFkVf($workactivityBS);
            parent::evalConditions($workactivityBS, $conditions);
            parent::evalOrders($workactivityBS, $orders);
            $workactivitys = $workactivityBS->table($conditions, $orders, $paginate);
            parent::evalPagination($workactivityBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($workactivitys);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($workactivityIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $workactivity = DelegateUtility::getEntityToSave(new WorkactivityBS(), $workactivityIn, $this->obj);

            if (!empty($workactivity)) {

                $workactivityBS = new WorkactivityBS();
                $id_workactivity = $workactivityBS->save($workactivity);
                parent::saveInGroup($workactivityBS, $id_workactivity);

                parent::commitTransaction();
                if (!empty($id_workactivity)) {
                    $this->ok(TranslatorUtility::__translate("INFO_WORKACTIVITY_SAVE", $this->localefile));
                    return $id_workactivity;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_WORKACTIVITY_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_WORKACTIVITY_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_WORKACTIVITY_SAVE");
            return 0;
        }
    }

    function edit($id, $workactivityIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $workactivity = DelegateUtility::getEntityToEdit(new WorkactivityBS(), $workactivityIn, $this->obj, $id);

            if (!empty($workactivity)) {
                $workactivityBS = new WorkactivityBS();
                $id_workactivity = $workactivityBS->save($workactivity);
                parent::saveInGroup($workactivityBS, $id_workactivity);
                parent::delInGroup($workactivityBS, $id_workactivity);

                parent::commitTransaction();
                if (!empty($id_workactivity)) {
                    $this->ok(TranslatorUtility::__translate("INFO_WORKACTIVITY_EDIT", $this->localefile));
                    return $id_workactivity;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_WORKACTIVITY_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_WORKACTIVITY_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_WORKACTIVITY_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $workactivityBS = new WorkactivityBS();
                $workactivityBS->delete($id);
                parent::delInGroup($workactivityBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_WORKACTIVITY_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_WORKACTIVITY_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_WORKACTIVITY_DELETE");
            return false;
        }
    }

    // utility

    function createByActivity($id = null, $piva = null) {
        $this->LOG_FUNCTION = "createByActivity";
        try {
            if (empty($id) && empty($piva)) {
                DelegateUtility::paramsNull($this, "ERROR_WORKACTIVITY_CREATE_BY_ACTIVITY");
                return 0;
            }

            // activity info
            $activityBS = new ActivityBS();
            if (!empty($piva)) {
                $activityBS->addCondition("piva", $piva);
            }
            $activity = $activityBS->unique($id);

            // workactivity info
            $workactivityBS = new WorkactivityBS();
            $workactivityBS->acceptNull = true;
            $workactivityBS->addCondition("activity", $activity['Activity']['id']);
            $workactivity = $workactivityBS->unique();
            if (empty($workactivity)) {
                $workactivity = $workactivityBS->instance();
                $workactivity['Workactivity']['activity'] = $activity['Activity']['id'];
                $workactivity['Workactivity']['cod'] = FileUtility::uuid_medium();
                // image
                $activityattachmentBS = new ActivityattachmentBS();
                $activityattachmentBS->acceptNull = true;
                $activityattachmentBS->addBelongsTo("attachment_fk");
                $activityattachmentBS->addCondition("activity", $activity['Activity']['id']);
                $activityattachmentBS->addCondition("attachment_fk.tpattachment", EnumAttachmentType::IMAGE);
                // $activityattachmentBS->addCondition("flgprincipal", 1);
                $activityattachmentBS->addOrder("flgprincipal", "DESC");
                $activityattachment = $activityattachmentBS->unique();
                if (!empty($activityattachment)) {
                    $workactivity['Workactivity']['image'] = $activityattachment['Activityattachment']['attachment'];
                }
                // home
                $activityaddressBS = new ActivityaddressBS();
                $activityaddressBS->acceptNull = true;
                $activityaddressBS->addBelongsTo("address_fk");
                $activityaddressBS->addCondition("activity", $activity['Activity']['id']);
                $activityaddressBS->addCondition("address_fk.tpaddress", EnumAddressType::LEGAL);
                // $activityaddressBS->addCondition("flgprincipal", 1);
                $activityaddressBS->addOrder("flgprincipal", "DESC");
                $activityaddress = $activityaddressBS->unique();
                if (!empty($activityaddress)) {
                    $workactivity['Workactivity']['home'] = $activityaddress['Activityaddress']['address'];
                }

                // phone
                $activityreferenceBS = new ActivityreferenceBS();
                $activityreferenceBS->acceptNull = true;
                $activityreferenceBS->addBelongsTo("contactreference_fk");
                $activityreferenceBS->addCondition("activity", $activity['Activity']['id']);
                $activityreferenceBS->addCondition("contactreference_fk.tpcontactreference", array(EnumContactreferenceType::CEL, EnumContactreferenceType::TEL));
                // $activityreferenceBS->addCondition("flgprincipal", 1);
                $activityreferenceBS->addOrder("flgprincipal", "DESC");
                $activityreference = $activityreferenceBS->unique();
                if (!empty($activityreference)) {
                    $workactivity['Workactivity']['phone'] = $activityreference['Activityreference']['contactreference'];
                }

                // website
                $activityreferenceBS = new ActivityreferenceBS();
                $activityreferenceBS->acceptNull = true;
                $activityreferenceBS->addBelongsTo("contactreference_fk");
                $activityreferenceBS->addCondition("activity", $activity['Activity']['id']);
                $activityreferenceBS->addCondition("contactreference_fk.tpcontactreference", array(
                    EnumContactreferenceType::SITE,
                    EnumContactreferenceType::SOCIAL,
                    EnumContactreferenceType::BLOG,
                ));
                // $activityreferenceBS->addCondition("flgprincipal", 1);
                $activityreferenceBS->addOrder("flgprincipal", "DESC");
                $activityreference = $activityreferenceBS->unique();
                if (!empty($activityreference)) {
                    $workactivity['Workactivity']['website'] = $activityreference['Activityreference']['contactreference'];
                }

                $id_workactivity = $workactivityBS->save($workactivity);
            } else {
                $id_workactivity = $workactivity['Workactivity']['id'];
            }

            $this->ok();
            return $id_workactivity;
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_WORKACTIVITY_CREATE_BY_ACTIVITY");
            return 0;
        }
    }
}
