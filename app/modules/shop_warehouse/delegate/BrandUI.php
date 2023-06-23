<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("BrandBS", "modules/shop_warehouse/business");
App::uses("FileUtility", "modules/coreutils/utility");

class BrandUI extends AppGenericUI {

    function __construct() {
        parent::__construct("BrandUI");
        $this->localefile = "brand";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("name", null, ""),
            new ObjPropertyEntity("description", null, ""),
            new ObjPropertyEntity("image", null, 0),
        );
    }

    function get($id = null, $cod = null, $name = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod) && empty($name)) {
                DelegateUtility::paramsNull($this, "ERROR_BRAND_NOT_FOUND");
                return "";
            }
            $brandBS = new BrandBS();
            $brandBS->json = $this->json;
            parent::completeByJsonFkVf($brandBS);
            if (!empty($cod)) {
                $brandBS->addCondition("cod", $cod);
            }
            if (!empty($name)) {
                $brandBS->addCondition("name", $name);
            }
            $this->ok();
            return $brandBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_BRAND_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $brandBS = !empty($bs) ? $bs : new BrandBS();
            $brandBS->json = $this->json;
            parent::completeByJsonFkVf($brandBS);
            parent::evalConditions($brandBS, $conditions);
            parent::evalOrders($brandBS, $orders);
            $brands = $brandBS->table($conditions, $orders, $paginate);
            parent::evalPagination($brandBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($brands);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($brandIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $brand = DelegateUtility::getEntityToSave(new BrandBS(), $brandIn, $this->obj);

            if (!empty($brand)) {

                $brandBS = new BrandBS();
                $id_brand = $brandBS->save($brand);
                parent::saveInGroup($brandBS, $id_brand);

                parent::commitTransaction();
                if (!empty($id_brand)) {
                    $this->ok(TranslatorUtility::__translate("INFO_BRAND_SAVE", $this->localefile));
                    return $id_brand;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_BRAND_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_BRAND_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_BRAND_SAVE");
            return 0;
        }
    }

    function edit($id, $brandIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $brand = DelegateUtility::getEntityToEdit(new BrandBS(), $brandIn, $this->obj, $id);

            if (!empty($brand)) {
                $brandBS = new BrandBS();
                $id_brand = $brandBS->save($brand);
                parent::saveInGroup($brandBS, $id_brand);
                parent::delInGroup($brandBS, $id_brand);

                parent::commitTransaction();
                if (!empty($id_brand)) {
                    $this->ok(TranslatorUtility::__translate("INFO_BRAND_EDIT", $this->localefile));
                    return $id_brand;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_BRAND_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_BRAND_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_BRAND_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $brandBS = new BrandBS();
                $brandBS->delete($id);
                parent::delInGroup($brandBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_BRAND_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_BRAND_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_BRAND_DELETE");
            return false;
        }
    }
}
