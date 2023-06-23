<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("CategoryBS", "modules/shop_warehouse/business");
App::uses("FileUtility", "modules/coreutils/utility");

class CategoryUI extends AppGenericUI {

    function __construct() {
        parent::__construct("CategoryUI");
        $this->localefile = "category";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("name", null, ""),
            new ObjPropertyEntity("description", null, ""),
            new ObjPropertyEntity("image", null, 0),
            new ObjPropertyEntity("parent_id", null, 0),
            new ObjPropertyEntity("lft", null, 0),
            new ObjPropertyEntity("rght", null, 0),
            new ObjPropertyEntity("reftable", null, ""),
        );
    }

    function get($id = null, $cod = null, $name = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod) && empty($name)) {
                DelegateUtility::paramsNull($this, "ERROR_CATEGORY_NOT_FOUND");
                return "";
            }
            $categoryBS = new CategoryBS();
            $categoryBS->json = $this->json;
            parent::completeByJsonFkVf($categoryBS);
            if (!empty($cod)) {
                $categoryBS->addCondition("cod", $cod);
            }
            if (!empty($name)) {
                $categoryBS->addCondition("name", $name);
            }
            $this->ok();
            return $categoryBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_CATEGORY_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $categoryBS = !empty($bs) ? $bs : new CategoryBS();
            $categoryBS->json = $this->json;
            parent::completeByJsonFkVf($categoryBS);
            parent::evalConditions($categoryBS, $conditions);
            parent::evalOrders($categoryBS, $orders);
            $categorys = $categoryBS->table($conditions, $orders, $paginate);
            parent::evalPagination($categoryBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($categorys);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($categoryIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $category = DelegateUtility::getEntityToSave(new CategoryBS(), $categoryIn, $this->obj);

            if (!empty($category)) {

                $categoryBS = new CategoryBS();
                $id_category = $categoryBS->save($category);
                parent::saveInGroup($categoryBS, $id_category);

                parent::commitTransaction();
                if (!empty($id_category)) {
                    $this->ok(TranslatorUtility::__translate("INFO_CATEGORY_SAVE", $this->localefile));
                    return $id_category;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_CATEGORY_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_CATEGORY_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_CATEGORY_SAVE");
            return 0;
        }
    }

    function edit($id, $categoryIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $category = DelegateUtility::getEntityToEdit(new CategoryBS(), $categoryIn, $this->obj, $id);

            if (!empty($category)) {
                $categoryBS = new CategoryBS();
                $id_category = $categoryBS->save($category);
                parent::saveInGroup($categoryBS, $id_category);
                parent::delInGroup($categoryBS, $id_category);

                parent::commitTransaction();
                if (!empty($id_category)) {
                    $this->ok(TranslatorUtility::__translate("INFO_CATEGORY_EDIT", $this->localefile));
                    return $id_category;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_CATEGORY_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_CATEGORY_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_CATEGORY_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $categoryBS = new CategoryBS();
                $categoryBS->delete($id);
                parent::delInGroup($categoryBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_CATEGORY_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_CATEGORY_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_CATEGORY_DELETE");
            return false;
        }
    }
}
