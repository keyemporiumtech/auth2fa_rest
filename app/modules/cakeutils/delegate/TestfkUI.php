<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
App::uses("FileUtility", "modules/coreutils/utility");
App::uses("TestfkBS", "modules/cakeutils/business");

class TestfkUI extends AppGenericUI {

    function __construct() {
        parent::__construct("TestfkUI");
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("title", null, ""),
            new ObjPropertyEntity("description", null, ""),
            new ObjPropertyEntity("result", null, 0),
            new ObjPropertyEntity("test", null, 0),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {

            if (empty($id) && empty($cod)) {
                $this->error("Nessun parametro fornito in input", "INPUT ERROR");
                return "";
            }
            $testBS = new TestfkBS();
            $testBS->json = $this->json;
            parent::completeByJsonFkVf($testBS);
            if (!empty($cod)) {
                $testBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $testBS->unique($id);
        } catch (Exception $e) {
            $this->error(mb_convert_encoding("" . __d("errors", "ERROR_NO_DATA_FOUND"), 'UTF-8'), $e);
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $testBS = !empty($bs) ? $bs : new TestfkBS();
            $testBS->json = $this->json;
            parent::completeByJsonFkVf($testBS);
            parent::evalConditions($testBS, $conditions);
            parent::evalOrders($testBS, $orders);
            $tests = $testBS->table($conditions, $orders, $paginate);
            parent::evalPagination($testBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($tests);
        } catch (Exception $e) {
            $this->error(mb_convert_encoding("" . __d("errors", "ERROR_NO_DATA_FOUND"), 'UTF-8'), $e);
            return "";
        }
    }

    function save($testIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $test = DelegateUtility::getEntityToSave(new TestfkBS(), $testIn, $this->obj);

            if (!empty($test)) {

                $testBS = new TestfkBS();
                $id_test = $testBS->save($test);
                parent::saveInGroup($testBS, $id_test);

                parent::commitTransaction();
                if (!empty($id_test)) {
                    $this->ok();
                    return $id_test;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_SAVE", "errors");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_SAVE", "errors");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_SAVE", "errors");
            return 0;
        }
    }

    function edit($id, $testIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $test = DelegateUtility::getEntityToEdit(new TestfkBS(), $testIn, $this->obj, $id);

            if (!empty($test)) {
                $testBS = new TestfkBS();
                $id_test = $testBS->save($test);
                parent::saveInGroup($testBS, $id_test);
                parent::delInGroup($testBS, $id_test);                

                parent::commitTransaction();
                if (!empty($id_test)) {
                    $this->ok();
                    return $id_test;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_EDIT", "errors");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_EDIT", "errors");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_EDIT", "errors");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $testBS = new TestfkBS();
                $testBS->delete($id);
                parent::delInGroup($testBS, $id, true);

                parent::commitTransaction();
                $this->ok();
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_DELETE", "errors");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_DELETE", "errors");
            return false;
        }
    }
}