<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("WorkuserBS", "modules/work_profile/business");
App::uses("FileUtility", "modules/coreutils/utility");
App::uses("UserBS", "modules/authentication/business");
App::uses("UserattachmentBS", "modules/authentication/business");
App::uses("EnumAttachmentType", "modules/resources/config");
App::uses("UseraddressBS", "modules/authentication/business");
App::uses("EnumAddressType", "modules/localesystem/config");
App::uses("UserreferenceBS", "modules/authentication/business");
App::uses("EnumContactreferenceType", "modules/authentication/config");

class WorkuserUI extends AppGenericUI {

    function __construct() {
        parent::__construct("WorkuserUI");
        $this->localefile = "workuser";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("user", null, 0),
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
                DelegateUtility::paramsNull($this, "ERROR_WORKUSER_NOT_FOUND");
                return "";
            }
            $workuserBS = new WorkuserBS();
            $workuserBS->json = $this->json;
            parent::completeByJsonFkVf($workuserBS);
            if (!empty($cod)) {
                $workuserBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $workuserBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_WORKUSER_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $workuserBS = !empty($bs) ? $bs : new WorkuserBS();
            $workuserBS->json = $this->json;
            parent::completeByJsonFkVf($workuserBS);
            parent::evalConditions($workuserBS, $conditions);
            parent::evalOrders($workuserBS, $orders);
            $workusers = $workuserBS->table($conditions, $orders, $paginate);
            parent::evalPagination($workuserBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($workusers);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($workuserIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $workuser = DelegateUtility::getEntityToSave(new WorkuserBS(), $workuserIn, $this->obj);

            if (!empty($workuser)) {

                $workuserBS = new WorkuserBS();
                $id_workuser = $workuserBS->save($workuser);
                parent::saveInGroup($workuserBS, $id_workuser);

                parent::commitTransaction();
                if (!empty($id_workuser)) {
                    $this->ok(TranslatorUtility::__translate("INFO_WORKUSER_SAVE", $this->localefile));
                    return $id_workuser;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_WORKUSER_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_WORKUSER_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_WORKUSER_SAVE");
            return 0;
        }
    }

    function edit($id, $workuserIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $workuser = DelegateUtility::getEntityToEdit(new WorkuserBS(), $workuserIn, $this->obj, $id);

            if (!empty($workuser)) {
                $workuserBS = new WorkuserBS();
                $id_workuser = $workuserBS->save($workuser);
                parent::saveInGroup($workuserBS, $id_workuser);
                parent::delInGroup($workuserBS, $id_workuser);

                parent::commitTransaction();
                if (!empty($id_workuser)) {
                    $this->ok(TranslatorUtility::__translate("INFO_WORKUSER_EDIT", $this->localefile));
                    return $id_workuser;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_WORKUSER_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_WORKUSER_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_WORKUSER_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $workuserBS = new WorkuserBS();
                $workuserBS->delete($id);
                parent::delInGroup($workuserBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_WORKUSER_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_WORKUSER_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_WORKUSER_DELETE");
            return false;
        }
    }

    // utility

    function createByUser($id = null, $username = null) {
        $this->LOG_FUNCTION = "createByUser";
        try {
            if (empty($id) && empty($username)) {
                DelegateUtility::paramsNull($this, "ERROR_WORKUSER_CREATE_BY_USER");
                return 0;
            }

            // user info
            $userBS = new UserBS();
            if (!empty($username)) {
                $userBS->addCondition("username", $username);
            }
            $user = $userBS->unique($id);

            // workuser info
            $workuserBS = new WorkuserBS();
            $workuserBS->acceptNull = true;
            $workuserBS->addCondition("user", $user['User']['id']);
            $workuser = $workuserBS->unique();
            if (empty($workuser)) {
                $workuser = $workuserBS->instance();
                $workuser['Workuser']['user'] = $user['User']['id'];
                $workuser['Workuser']['cod'] = FileUtility::uuid_medium();
                // image
                $userattachmentBS = new UserattachmentBS();
                $userattachmentBS->acceptNull = true;
                $userattachmentBS->addBelongsTo("attachment_fk");
                $userattachmentBS->addCondition("user", $user['User']['id']);
                $userattachmentBS->addCondition("attachment_fk.tpattachment", EnumAttachmentType::IMAGE);
                // $userattachmentBS->addCondition("flgprincipal", 1);
                $userattachmentBS->addOrder("flgprincipal", "DESC");
                $userattachment = $userattachmentBS->unique();
                if (!empty($userattachment)) {
                    $workuser['Workuser']['image'] = $userattachment['Userattachment']['attachment'];
                }
                // home
                $useraddressBS = new UseraddressBS();
                $useraddressBS->acceptNull = true;
                $useraddressBS->addBelongsTo("address_fk");
                $useraddressBS->addCondition("user", $user['User']['id']);
                $useraddressBS->addCondition("address_fk.tpaddress", EnumAddressType::HOME);
                // $useraddressBS->addCondition("flgprincipal", 1);
                $useraddressBS->addOrder("flgprincipal", "DESC");
                $useraddress = $useraddressBS->unique();
                if (!empty($useraddress)) {
                    $workuser['Workuser']['home'] = $useraddress['Useraddress']['address'];
                }

                // phone
                $userreferenceBS = new UserreferenceBS();
                $userreferenceBS->acceptNull = true;
                $userreferenceBS->addBelongsTo("contactreference_fk");
                $userreferenceBS->addCondition("user", $user['User']['id']);
                $userreferenceBS->addCondition("contactreference_fk.tpcontactreference", array(EnumContactreferenceType::CEL, EnumContactreferenceType::TEL));
                // $userreferenceBS->addCondition("flgprincipal", 1);
                $userreferenceBS->addOrder("flgprincipal", "DESC");
                $userreference = $userreferenceBS->unique();
                if (!empty($userreference)) {
                    $workuser['Workuser']['phone'] = $userreference['Userreference']['contactreference'];
                }

                // website
                $userreferenceBS = new UserreferenceBS();
                $userreferenceBS->acceptNull = true;
                $userreferenceBS->addBelongsTo("contactreference_fk");
                $userreferenceBS->addCondition("user", $user['User']['id']);
                $userreferenceBS->addCondition("contactreference_fk.tpcontactreference", array(
                    EnumContactreferenceType::SITE,
                    EnumContactreferenceType::SOCIAL,
                    EnumContactreferenceType::BLOG,
                ));
                // $userreferenceBS->addCondition("flgprincipal", 1);
                $userreferenceBS->addOrder("flgprincipal", "DESC");
                $userreference = $userreferenceBS->unique();
                if (!empty($userreference)) {
                    $workuser['Workuser']['website'] = $userreference['Userreference']['contactreference'];
                }

                $id_workuser = $workuserBS->save($workuser);
            } else {
                $id_workuser = $workuser['Workuser']['id'];
            }

            $this->ok();
            return $id_workuser;
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_WORKUSER_CREATE_BY_USER");
            return 0;
        }
    }
}
