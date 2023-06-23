<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("TicketBS", "modules/shop_warehouse/business");
App::uses("FileUtility", "modules/coreutils/utility");

class TicketUI extends AppGenericUI {

    function __construct() {
        parent::__construct("TicketUI");
        $this->localefile = "ticket";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("name", null, ""),
            new ObjPropertyEntity("description", null, ""),
            new ObjPropertyEntity("image", null, 0),
            new ObjPropertyEntity("quantity", null, 0),
            new ObjPropertyEntity("event", null, 0),
            new ObjPropertyEntity("category", null, 0),
            new ObjPropertyEntity("price", null, 0),
            new ObjPropertyEntity("note", null, ""),
            new ObjPropertyEntity("dtafrom", null, ""),
            new ObjPropertyEntity("dtato", null, ""),
            new ObjPropertyEntity("flgdeleted", null, 0),
            new ObjPropertyEntity("flgwarehouse", null, 0),
            new ObjPropertyEntity("flgreserve", null, 0),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_TICKET_NOT_FOUND");
                return "";
            }
            $ticketBS = new TicketBS();
            $ticketBS->json = $this->json;
            parent::completeByJsonFkVf($ticketBS);
            if (!empty($cod)) {
                $ticketBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $ticketBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_TICKET_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $ticketBS = !empty($bs) ? $bs : new TicketBS();
            $ticketBS->json = $this->json;
            parent::completeByJsonFkVf($ticketBS);
            parent::evalConditions($ticketBS, $conditions);
            parent::evalOrders($ticketBS, $orders);
            $tickets = $ticketBS->table($conditions, $orders, $paginate);
            parent::evalPagination($ticketBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($tickets);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($ticketIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $ticket = DelegateUtility::getEntityToSave(new TicketBS(), $ticketIn, $this->obj);

            if (!empty($ticket)) {

                $ticketBS = new TicketBS();
                $id_ticket = $ticketBS->save($ticket);
                parent::saveInGroup($ticketBS, $id_ticket);

                parent::commitTransaction();
                if (!empty($id_ticket)) {
                    $this->ok(TranslatorUtility::__translate("INFO_TICKET_SAVE", $this->localefile));
                    return $id_ticket;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_TICKET_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_TICKET_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_TICKET_SAVE");
            return 0;
        }
    }

    function edit($id, $ticketIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $ticket = DelegateUtility::getEntityToEdit(new TicketBS(), $ticketIn, $this->obj, $id);

            if (!empty($ticket)) {
                $ticketBS = new TicketBS();
                $id_ticket = $ticketBS->save($ticket);
                parent::saveInGroup($ticketBS, $id_ticket);
                parent::delInGroup($ticketBS, $id_ticket);

                parent::commitTransaction();
                if (!empty($id_ticket)) {
                    $this->ok(TranslatorUtility::__translate("INFO_TICKET_EDIT", $this->localefile));
                    return $id_ticket;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_TICKET_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_TICKET_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_TICKET_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $ticketBS = new TicketBS();
                $ticketBS->delete($id);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_TICKET_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_TICKET_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_TICKET_DELETE");
            return false;
        }
    }

    // ------------------ PRICE
    function addPrice($priceIn, $id) {
        $this->LOG_FUNCTION = "addPrice";
        try {
            if (empty($priceIn) || empty($id)) {
                DelegateUtility::paramsNull($this, "ERROR_TICKET_EDIT");
                return false;
            }
            parent::startTransaction();

            DelegatePriceUtility::addPrice($priceIn, $id, "Ticket");

            parent::commitTransaction();
            $this->ok(TranslatorUtility::__translate("INFO_TICKET_EDIT", $this->localefile));
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_TICKET_EDIT");
            return false;
        }
    }

    function editPrice($priceIn, $id) {
        $this->LOG_FUNCTION = "editPrice";
        try {
            if (empty($priceIn) || empty($id)) {
                DelegateUtility::paramsNull($this, "ERROR_TICKET_EDIT");
                return false;
            }
            parent::startTransaction();

            DelegatePriceUtility::editPrice($priceIn, $id, "Ticket");

            parent::commitTransaction();
            $this->ok(TranslatorUtility::__translate("INFO_TICKET_EDIT", $this->localefile));
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_TICKET_EDIT");
            return false;
        }
    }

    // ------------------ DISCOUNT
    function addDiscount($discountIn, $id) {
        $this->LOG_FUNCTION = "addDiscount";
        try {
            if (empty($discountIn) || empty($id)) {
                DelegateUtility::paramsNull($this, "ERROR_TICKET_EDIT");
                return false;
            }
            parent::startTransaction();

            DelegatePriceUtility::addDiscount($discountIn, $id, "Ticket");

            parent::commitTransaction();
            $this->ok(TranslatorUtility::__translate("INFO_TICKET_EDIT", $this->localefile));
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_TICKET_EDIT");
            return false;
        }
    }

    function editDiscount($discountIn, $id_discount, $id) {
        $this->LOG_FUNCTION = "editDiscount";
        try {
            if (empty($discountIn) || empty($id_discount) || empty($id)) {
                DelegateUtility::paramsNull($this, "ERROR_TICKET_EDIT");
                return false;
            }
            parent::startTransaction();

            DelegatePriceUtility::editDiscount($discountIn, $id_discount, $id, "Ticket");

            parent::commitTransaction();
            $this->ok(TranslatorUtility::__translate("INFO_TICKET_EDIT", $this->localefile));
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_TICKET_EDIT");
            return false;
        }
    }

    function deleteDiscount($id_discount, $id) {
        $this->LOG_FUNCTION = "deleteDiscount";
        try {
            if (empty($id_discount) || empty($id)) {
                DelegateUtility::paramsNull($this, "ERROR_TICKET_EDIT");
                return false;
            }
            parent::startTransaction();

            DelegatePriceUtility::deleteDiscount($id_discount, $id, "Ticket");

            parent::commitTransaction();
            $this->ok(TranslatorUtility::__translate("INFO_TICKET_EDIT", $this->localefile));
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_TICKET_EDIT");
            return false;
        }
    }

    // ------------------ TAX
    function addTax($taxIn, $id) {
        $this->LOG_FUNCTION = "addTax";
        try {
            if (empty($taxIn) || empty($id)) {
                DelegateUtility::paramsNull($this, "ERROR_TICKET_EDIT");
                return false;
            }
            parent::startTransaction();

            DelegatePriceUtility::addTax($this, $taxIn, $id, "Ticket");

            parent::commitTransaction();
            $this->ok(TranslatorUtility::__translate("INFO_TICKET_EDIT", $this->localefile));
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_TICKET_EDIT");
            return false;
        }
    }

    function editTax($taxIn, $id_tax, $id) {
        $this->LOG_FUNCTION = "editTax";
        try {
            if (empty($taxIn) || empty($id_tax) || empty($id)) {
                DelegateUtility::paramsNull($this, "ERROR_TICKET_EDIT");
                return false;
            }
            parent::startTransaction();

            DelegatePriceUtility::editTax($this, $taxIn, $id_tax, $id, "Ticket");

            parent::commitTransaction();
            $this->ok(TranslatorUtility::__translate("INFO_TICKET_EDIT", $this->localefile));
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_TICKET_EDIT");
            return false;
        }
    }

    function deleteTax($id_tax, $id) {
        $this->LOG_FUNCTION = "deleteTax";
        try {
            if (empty($id_tax) || empty($id)) {
                DelegateUtility::paramsNull($this, "ERROR_TICKET_EDIT");
                return false;
            }
            parent::startTransaction();

            DelegatePriceUtility::deleteTax($this, $id_tax, $id, "Ticket");

            parent::commitTransaction();
            $this->ok(TranslatorUtility::__translate("INFO_TICKET_EDIT", $this->localefile));
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_TICKET_EDIT");
            return false;
        }
    }
}
