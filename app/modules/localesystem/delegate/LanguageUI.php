<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("Defaults", "Config/system");
App::uses("LanguageBS", "modules/localesystem/business");
App::uses("FileUtility", "modules/coreutils/utility");

class LanguageUI extends AppGenericUI {

    function __construct() {
        parent::__construct("LanguageUI");
        $this->localefile = "language";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("title", null, ""),
            new ObjPropertyEntity("symbol", null, ""),
            new ObjPropertyEntity("flgused", null, 0),
        );
    }

    function get($id = null, $cod = null, $title = null, $symbol = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod) && empty($title) && empty($symbol)) {
                DelegateUtility::paramsNull($this, "ERROR_LANGUAGE_NOT_FOUND");
                return "";
            }
            $languageBS = new LanguageBS();
            $languageBS->json = $this->json;
            parent::completeByJsonFkVf($languageBS);
            if (!empty($cod)) {
                $languageBS->addCondition("cod", $cod);
            }
            if (!empty($title)) {
                $languageBS->addCondition("title", $title);
            }
            if (!empty($symbol)) {
                $languageBS->addCondition("symbol", $symbol);
            }
            $this->ok();
            return $languageBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_LANGUAGE_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $languageBS = !empty($bs) ? $bs : new LanguageBS();
            $languageBS->json = $this->json;
            parent::completeByJsonFkVf($languageBS);
            parent::evalConditions($languageBS, $conditions);
            parent::evalOrders($languageBS, $orders);
            $languages = $languageBS->table($conditions, $orders, $paginate);
            parent::evalPagination($languageBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($languages);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($languageIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $language = DelegateUtility::getEntityToSave(new LanguageBS(), $languageIn, $this->obj);

            if (!empty($language)) {

                $languageBS = new LanguageBS();
                $id_language = $languageBS->save($language);
                parent::saveInGroup($languageBS, $id_language);

                parent::commitTransaction();
                if (!empty($id_language)) {
                    $this->ok(TranslatorUtility::__translate("INFO_LANGUAGE_SAVE", $this->localefile));
                    return $id_language;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_LANGUAGE_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_LANGUAGE_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_LANGUAGE_SAVE");
            return 0;
        }
    }

    function edit($id, $languageIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $language = DelegateUtility::getEntityToEdit(new LanguageBS(), $languageIn, $this->obj, $id);

            if (!empty($language)) {
                $languageBS = new LanguageBS();
                $id_language = $languageBS->save($language);
                parent::saveInGroup($languageBS, $id_language);
                parent::delInGroup($languageBS, $id_language);

                parent::commitTransaction();
                if (!empty($id_language)) {
                    $this->ok(TranslatorUtility::__translate("INFO_LANGUAGE_EDIT", $this->localefile));
                    return $id_language;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_LANGUAGE_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_LANGUAGE_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_LANGUAGE_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $languageBS = new LanguageBS();
                $languageBS->delete($id);
                parent::delInGroup($languageBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_LANGUAGE_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_LANGUAGE_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_LANGUAGE_DELETE");
            return false;
        }
    }

    // ------------------ SESSION
    function setupLanguage($cod, $flgMessage = false) {
        $this->LOG_FUNCTION = "setupLanguage";
        try {
            if (empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_LANGUAGE_SETUP");
                return "";
            }
            //INFO_LANGUAGE_CHANGE
            $current = $this->get(null, $cod);
            if (!empty($current)) {
                CakeSession::write('Config.language', $cod);
                $objLog = new ObjCodMessage(Codes::get("LANGUAGE_CHANGE"), TranslatorUtility::__translate_args("INFO_LANGUAGE_CHANGE", array(
                    $cod,
                ), $this->localefile));
                DelegateUtility::logMessage($this, $objLog);
                if ($flgMessage) {
                    $this->ok(TranslatorUtility::__translate_args("INFO_LANGUAGE_CHANGE", array(
                        $cod,
                    ), $this->localefile));
                } else {
                    $this->ok();
                }
                return $current;
            } else {
                DelegateUtility::errorInternal($this, "OBJECT_NULL", "ERROR_LANGUAGE_SETUP", null, "ERROR_LANGUAGE_COD", array(
                    $cod,
                ));
                return "";
            }
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_LANGUAGE_SETUP");
            return "";
        }
    }

    function getCurrentLanguageSystem() {
        $this->LOG_FUNCTION = "getCurrentLanguageSystem";
        try {
            $cod = CakeSession::read('Config.language');
            if (empty($cod)) {
                $cod = Defaults::get("language");
                CakeSession::write('Config.language', $cod);
                $objLog = new ObjCodMessage(Codes::get("LANGUAGE_CHANGE"), TranslatorUtility::__translate_args("INFO_LANGUAGE_CHANGE_DEFAULT", array(
                    $cod,
                ), $this->localefile));
                DelegateUtility::logMessage($this, $objLog);
            }
            if (empty($cod)) {
                DelegateUtility::errorInternal($this, "PARAM_NULL", "ERROR_LANGUAGE_NOT_FOUND", null, "ERROR_LANGUAGE_CHANGE_DEFAULT");
                return "";
            }
            return $this->get(null, $cod);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_LANGUAGE_NOT_FOUND");
            return "";
        }
    }
}
