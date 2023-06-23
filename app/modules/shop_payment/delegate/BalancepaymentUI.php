<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("BalancepaymentBS", "modules/shop_payment/business");
App::uses("FileUtility", "modules/coreutils/utility");

class BalancepaymentUI extends AppGenericUI {

    function __construct() {
        parent::__construct("BalancepaymentUI");
        $this->localefile = "balancepayment";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("balance", null, 0),
            new ObjPropertyEntity("payment", null, 0),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_BALANCEPAYMENT_NOT_FOUND");
                return "";
            }
            $balancepaymentBS = new BalancepaymentBS();
            $balancepaymentBS->json = $this->json;
            parent::completeByJsonFkVf($balancepaymentBS);
            if (!empty($cod)) {
                $balancepaymentBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $balancepaymentBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_BALANCEPAYMENT_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $balancepaymentBS = !empty($bs) ? $bs : new BalancepaymentBS();
            $balancepaymentBS->json = $this->json;
            parent::completeByJsonFkVf($balancepaymentBS);
            parent::evalConditions($balancepaymentBS, $conditions);
            parent::evalOrders($balancepaymentBS, $orders);
            $balancepayments = $balancepaymentBS->table($conditions, $orders, $paginate);
            parent::evalPagination($balancepaymentBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($balancepayments);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($balancepaymentIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $balancepayment = DelegateUtility::getEntityToSave(new BalancepaymentBS(), $balancepaymentIn, $this->obj);

            if (!empty($balancepayment)) {

                $balancepaymentBS = new BalancepaymentBS();
                $id_balancepayment = $balancepaymentBS->save($balancepayment);
                parent::saveInGroup($balancepaymentBS, $id_balancepayment);

                parent::commitTransaction();
                if (!empty($id_balancepayment)) {
                    $this->ok(TranslatorUtility::__translate("INFO_BALANCEPAYMENT_SAVE", $this->localefile));
                    return $id_balancepayment;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_BALANCEPAYMENT_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_BALANCEPAYMENT_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_BALANCEPAYMENT_SAVE");
            return 0;
        }
    }

    function edit($id, $balancepaymentIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $balancepayment = DelegateUtility::getEntityToEdit(new BalancepaymentBS(), $balancepaymentIn, $this->obj, $id);

            if (!empty($balancepayment)) {
                $balancepaymentBS = new BalancepaymentBS();
                $id_balancepayment = $balancepaymentBS->save($balancepayment);
                parent::saveInGroup($balancepaymentBS, $id_balancepayment);
                parent::delInGroup($balancepaymentBS, $id_balancepayment);

                parent::commitTransaction();
                if (!empty($id_balancepayment)) {
                    $this->ok(TranslatorUtility::__translate("INFO_BALANCEPAYMENT_EDIT", $this->localefile));
                    return $id_balancepayment;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_BALANCEPAYMENT_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_BALANCEPAYMENT_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_BALANCEPAYMENT_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $balancepaymentBS = new BalancepaymentBS();
                $balancepaymentBS->delete($id);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_BALANCEPAYMENT_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_BALANCEPAYMENT_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_BALANCEPAYMENT_DELETE");
            return false;
        }
    }
}
