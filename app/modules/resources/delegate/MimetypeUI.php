<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("MimetypeBS", "modules/resources/business");
App::uses("FileUtility", "modules/coreutils/utility");

class MimetypeUI extends AppGenericUI {

    function __construct() {
        parent::__construct("MimetypeUI");
        $this->localefile = "mimetype";
        $this->obj = array(
            new ObjPropertyEntity("ext", null, ""),
            new ObjPropertyEntity("value", null, ""),
            new ObjPropertyEntity("type", null, ""),
        );
    }

    function get($id = null, $ext = null, $value = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($ext) && empty($value)) {
                DelegateUtility::paramsNull($this, "ERROR_MIMETYPE_NOT_FOUND");
                return "";
            }
            $mimetypeBS = new MimetypeBS();
            $mimetypeBS->json = $this->json;
            parent::completeByJsonFkVf($mimetypeBS);
            if (!empty($ext)) {
                $mimetypeBS->addCondition("ext", $ext);
            }
            if (!empty($value)) {
                $mimetypeBS->addCondition("value", $value);
            }
            $this->ok();
            return $mimetypeBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_MIMETYPE_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $mimetypeBS = !empty($bs) ? $bs : new MimetypeBS();
            $mimetypeBS->json = $this->json;
            parent::completeByJsonFkVf($mimetypeBS);
            parent::evalConditions($mimetypeBS, $conditions);
            parent::evalOrders($mimetypeBS, $orders);
            $mimetypes = $mimetypeBS->table($conditions, $orders, $paginate);
            parent::evalPagination($mimetypeBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($mimetypes);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($mimetypeIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $mimetype = DelegateUtility::getEntityToSave(new MimetypeBS(), $mimetypeIn, $this->obj);

            if (!empty($mimetype)) {

                $mimetypeBS = new MimetypeBS();
                $id_mimetype = $mimetypeBS->save($mimetype);
                parent::saveInGroup($mimetypeBS, $id_mimetype);

                parent::commitTransaction();
                if (!empty($id_mimetype)) {
                    $this->ok(TranslatorUtility::__translate("INFO_MIMETYPE_SAVE", $this->localefile));
                    return $id_mimetype;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_MIMETYPE_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_MIMETYPE_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_MIMETYPE_SAVE");
            return 0;
        }
    }

    function edit($id, $mimetypeIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $mimetype = DelegateUtility::getEntityToEdit(new MimetypeBS(), $mimetypeIn, $this->obj, $id);

            if (!empty($mimetype)) {
                $mimetypeBS = new MimetypeBS();
                $id_mimetype = $mimetypeBS->save($mimetype);
                parent::saveInGroup($mimetypeBS, $id_mimetype);
                parent::delInGroup($mimetypeBS, $id_mimetype);

                parent::commitTransaction();
                if (!empty($id_mimetype)) {
                    $this->ok(TranslatorUtility::__translate("INFO_MIMETYPE_EDIT", $this->localefile));
                    return $id_mimetype;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_MIMETYPE_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_MIMETYPE_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_MIMETYPE_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $mimetypeBS = new MimetypeBS();
                $mimetypeBS->delete($id);
                parent::delInGroup($mimetypeBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_MIMETYPE_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_MIMETYPE_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_MIMETYPE_DELETE");
            return false;
        }
    }
}
