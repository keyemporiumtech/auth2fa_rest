<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("ProductBS", "modules/shop_warehouse/business");
App::uses("FileUtility", "modules/coreutils/utility");
App::uses("DiscountBS", "modules/shop_warehouse/business");
App::uses("ProductdiscountBS", "modules/shop_warehouse/business");
App::uses("PriceBS", "modules/shop_warehouse/business");
App::uses("ProducttaxBS", "modules/shop_warehouse/business");
App::uses("ProducttaxUI", "modules/shop_warehouse/delegate");
App::uses("DelegatePriceUtility", "modules/shop_warehouse/utility");

class ProductUI extends AppGenericUI {

    function __construct() {
        parent::__construct("ProductUI");
        $this->localefile = "product";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("name", null, ""),
            new ObjPropertyEntity("description", null, ""),
            new ObjPropertyEntity("image", null, 0),
            new ObjPropertyEntity("quantity", null, 0),
            new ObjPropertyEntity("brand", null, 0),
            new ObjPropertyEntity("category", null, 0),
            new ObjPropertyEntity("price", null, 0),
            new ObjPropertyEntity("note", null, ""),
            new ObjPropertyEntity("weight", null, 0.00),
            new ObjPropertyEntity("length", null, 0.00),
            new ObjPropertyEntity("width", null, 0.00),
            new ObjPropertyEntity("height", null, 0.00),
            new ObjPropertyEntity("flgdeleted", null, 0),
            new ObjPropertyEntity("flgwarehouse", null, 0),
            new ObjPropertyEntity("flgreserve", null, 0),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_PRODUCT_NOT_FOUND");
                return "";
            }
            $productBS = new ProductBS();
            $productBS->json = $this->json;
            parent::completeByJsonFkVf($productBS);
            if (!empty($cod)) {
                $productBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $productBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_PRODUCT_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $productBS = !empty($bs) ? $bs : new ProductBS();
            $productBS->json = $this->json;
            parent::completeByJsonFkVf($productBS);
            parent::evalConditions($productBS, $conditions);
            parent::evalOrders($productBS, $orders);
            $products = $productBS->table($conditions, $orders, $paginate);
            parent::evalPagination($productBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($products);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($productIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $product = DelegateUtility::getEntityToSave(new ProductBS(), $productIn, $this->obj);

            if (!empty($product)) {

                $productBS = new ProductBS();
                $id_product = $productBS->save($product);
                parent::saveInGroup($productBS, $id_product);

                parent::commitTransaction();
                if (!empty($id_product)) {
                    $this->ok(TranslatorUtility::__translate("INFO_PRODUCT_SAVE", $this->localefile));
                    return $id_product;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_PRODUCT_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_PRODUCT_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PRODUCT_SAVE");
            return 0;
        }
    }

    function edit($id, $productIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $product = DelegateUtility::getEntityToEdit(new ProductBS(), $productIn, $this->obj, $id);

            if (!empty($product)) {
                $productBS = new ProductBS();
                $id_product = $productBS->save($product);
                parent::saveInGroup($productBS, $id_product);
                parent::delInGroup($productBS, $id_product);

                parent::commitTransaction();
                if (!empty($id_product)) {
                    $this->ok(TranslatorUtility::__translate("INFO_PRODUCT_EDIT", $this->localefile));
                    return $id_product;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_PRODUCT_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_PRODUCT_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PRODUCT_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $productBS = new ProductBS();
                $productBS->delete($id);
                parent::delInGroup($productBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_PRODUCT_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_PRODUCT_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PRODUCT_DELETE");
            return false;
        }
    }

    // ------------------ PRICE
    function addPrice($priceIn, $id) {
        $this->LOG_FUNCTION = "addPrice";
        try {
            if (empty($priceIn) || empty($id)) {
                DelegateUtility::paramsNull($this, "ERROR_PRODUCT_EDIT");
                return false;
            }
            parent::startTransaction();

            DelegatePriceUtility::addPrice($priceIn, $id, "Product");

            parent::commitTransaction();
            $this->ok(TranslatorUtility::__translate("INFO_PRODUCT_EDIT", $this->localefile));
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PRODUCT_EDIT");
            return false;
        }
    }

    function editPrice($priceIn, $id) {
        $this->LOG_FUNCTION = "editPrice";
        try {
            if (empty($priceIn) || empty($id)) {
                DelegateUtility::paramsNull($this, "ERROR_PRODUCT_EDIT");
                return false;
            }
            parent::startTransaction();

            DelegatePriceUtility::editPrice($priceIn, $id, "Product");

            parent::commitTransaction();
            $this->ok(TranslatorUtility::__translate("INFO_PRODUCT_EDIT", $this->localefile));
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PRODUCT_EDIT");
            return false;
        }
    }

    // ------------------ DISCOUNT
    function addDiscount($discountIn, $id) {
        $this->LOG_FUNCTION = "addDiscount";
        try {
            if (empty($discountIn) || empty($id)) {
                DelegateUtility::paramsNull($this, "ERROR_PRODUCT_EDIT");
                return false;
            }
            parent::startTransaction();

            DelegatePriceUtility::addDiscount($discountIn, $id, "Product");

            parent::commitTransaction();
            $this->ok(TranslatorUtility::__translate("INFO_PRODUCT_EDIT", $this->localefile));
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PRODUCT_EDIT");
            return false;
        }
    }

    function editDiscount($discountIn, $id_discount, $id) {
        $this->LOG_FUNCTION = "editDiscount";
        try {
            if (empty($discountIn) || empty($id_discount) || empty($id)) {
                DelegateUtility::paramsNull($this, "ERROR_PRODUCT_EDIT");
                return false;
            }
            parent::startTransaction();

            DelegatePriceUtility::editDiscount($discountIn, $id_discount, $id, "Product");

            parent::commitTransaction();
            $this->ok(TranslatorUtility::__translate("INFO_PRODUCT_EDIT", $this->localefile));
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PRODUCT_EDIT");
            return false;
        }
    }

    function deleteDiscount($id_discount, $id) {
        $this->LOG_FUNCTION = "deleteDiscount";
        try {
            if (empty($id_discount) || empty($id)) {
                DelegateUtility::paramsNull($this, "ERROR_PRODUCT_EDIT");
                return false;
            }
            parent::startTransaction();

            DelegatePriceUtility::deleteDiscount($id_discount, $id, "Product");

            parent::commitTransaction();
            $this->ok(TranslatorUtility::__translate("INFO_PRODUCT_EDIT", $this->localefile));
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PRODUCT_EDIT");
            return false;
        }
    }

    // ------------------ TAX
    function addTax($taxIn, $id) {
        $this->LOG_FUNCTION = "addTax";
        try {
            if (empty($taxIn) || empty($id)) {
                DelegateUtility::paramsNull($this, "ERROR_PRODUCT_EDIT");
                return false;
            }
            parent::startTransaction();

            DelegatePriceUtility::addTax($this, $taxIn, $id, "Product");

            parent::commitTransaction();
            $this->ok(TranslatorUtility::__translate("INFO_PRODUCT_EDIT", $this->localefile));
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PRODUCT_EDIT");
            return false;
        }
    }

    function editTax($taxIn, $id_tax, $id) {
        $this->LOG_FUNCTION = "editTax";
        try {
            if (empty($taxIn) || empty($id_tax) || empty($id)) {
                DelegateUtility::paramsNull($this, "ERROR_PRODUCT_EDIT");
                return false;
            }
            parent::startTransaction();

            DelegatePriceUtility::editTax($this, $taxIn, $id_tax, $id, "Product");

            parent::commitTransaction();
            $this->ok(TranslatorUtility::__translate("INFO_PRODUCT_EDIT", $this->localefile));
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PRODUCT_EDIT");
            return false;
        }
    }

    function deleteTax($id_tax, $id) {
        $this->LOG_FUNCTION = "deleteTax";
        try {
            if (empty($id_tax) || empty($id)) {
                DelegateUtility::paramsNull($this, "ERROR_PRODUCT_EDIT");
                return false;
            }
            parent::startTransaction();

            DelegatePriceUtility::deleteTax($this, $id_tax, $id, "Product");

            parent::commitTransaction();
            $this->ok(TranslatorUtility::__translate("INFO_PRODUCT_EDIT", $this->localefile));
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PRODUCT_EDIT");
            return false;
        }
    }
}
