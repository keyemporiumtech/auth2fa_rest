<?php
App::uses("Enables", "Config/system");
App::uses("Codes", "Config/system");
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("ArrayUtility", "modules/coreutils/utility");
App::uses("LogUtility", "modules/coreutils/utility");
App::uses("ObjEntity", "modules/cakeutils/classes");
App::uses("ObjCodMessage", "modules/cakeutils/classes");
App::uses("TranslatorUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("EnumResponseCode", "modules/cakeutils/config");
// plugin
App::uses("JsonDecoder", "modules/cakeutils/plugin/jsonDecoder");
App::uses("DBCondition", "modules/cakeutils/classes");
App::uses("DBOrder", "modules/cakeutils/classes");
App::uses("DBPaginate", "modules/cakeutils/classes");

class DelegateUtility {

    static function eccezione($exception, AppGenericUI $ui, $key, $file = null) {
        $objInfo = MessageUtility::messageInfo($key, empty($file) ? $ui->localefile : $file, Codes::get("EXCEPTION_GENERIC"));
        $objException = MessageUtility::messageExceptionByException($exception);
        $objInternal = MessageUtility::messageInternal("ERROR_THROW_EXCEPTION", "errors", Codes::get("EXCEPTION_GENERIC"));
        $ui->error($objInfo->message, $exception, $objInternal->message, $objInfo->cod, null, $objInternal->cod, EnumResponseCode::INTERNAL_SERVER_ERROR);
    }

    static function nonSalvato(AppGenericUI $ui, $key, $file = null) {
        $objInfo = MessageUtility::messageInfo($key, empty($file) ? $ui->localefile : $file, Codes::get("ERROR_SAVE"));
        $objException = new ObjCodMessage(null, null);
        $objInternal = MessageUtility::messageInternal("ERROR_SAVE", "errors", Codes::get("ERROR_SAVE"));
        $ui->error($objInfo->message, $objException->message, $objInternal->message, $objInfo->cod, $objException->cod, $objInternal->cod, EnumResponseCode::INTERNAL_SERVER_ERROR);
    }

    static function nonModificato(AppGenericUI $ui, $key, $file = null) {
        $objInfo = MessageUtility::messageInfo($key, empty($file) ? $ui->localefile : $file, Codes::get("ERROR_SAVE"));
        $objException = new ObjCodMessage(null, null);
        $objInternal = MessageUtility::messageInternal("ERROR_EDIT", "errors", Codes::get("ERROR_SAVE"));
        $ui->error($objInfo->message, $objException->message, $objInternal->message, $objInfo->cod, $objException->cod, $objInternal->cod, EnumResponseCode::INTERNAL_SERVER_ERROR);
    }

    static function oggettoVuotoDaSalvare(AppGenericUI $ui, $key, $file = null) {
        $objInfo = MessageUtility::messageInfo($key, empty($file) ? $ui->localefile : $file, Codes::get("OBJECT_NULL"));
        $objException = new ObjCodMessage(null, null);
        $objInternal = MessageUtility::messageInternal("ERROR_SAVE_OBJECT_NULL", "errors", Codes::get("OBJECT_NULL"));
        $ui->error($objInfo->message, $objException->message, $objInternal->message, $objInfo->cod, $objException->cod, $objInternal->cod, EnumResponseCode::INTERNAL_SERVER_ERROR);
    }

    static function oggettoVuotoDaModificare(AppGenericUI $ui, $key, $file = null) {
        $objInfo = MessageUtility::messageInfo($key, empty($file) ? $ui->localefile : $file, Codes::get("OBJECT_NULL"));
        $objException = new ObjCodMessage(null, null);
        $objInternal = MessageUtility::messageInternal("ERROR_EDIT_OBJECT_NULL", "errors", Codes::get("OBJECT_NULL"));
        $ui->error($objInfo->message, $objException->message, $objInternal->message, $objInfo->cod, $objException->cod, $objInternal->cod, EnumResponseCode::INTERNAL_SERVER_ERROR);
    }

    static function idNulloDaEliminare(AppGenericUI $ui, $key, $file = null) {
        $objInfo = MessageUtility::messageInfo($key, empty($file) ? $ui->localefile : $file, Codes::get("PARAM_NULL"));
        $objException = new ObjCodMessage(null, null);
        $objInternal = MessageUtility::messageInternal("ERROR_DELETE_ID_NULL", "errors", Codes::get("PARAM_NULL"));
        $ui->error($objInfo->message, $objException->message, $objInternal->message, $objInfo->cod, $objException->cod, $objInternal->cod, EnumResponseCode::INTERNAL_SERVER_ERROR);
    }

    static function paramsNull(AppGenericUI $ui, $key, $file = null) {
        $objInfo = MessageUtility::messageInfo($key, empty($file) ? $ui->localefile : $file, Codes::get("PARAM_NULL"));
        $objException = new ObjCodMessage(null, null);
        $objInternal = MessageUtility::messageInternal("ERROR_PARAMS_NULL", "errors", Codes::get("PARAM_NULL"));
        $ui->error($objInfo->message, $objException->message, $objInternal->message, $objInfo->cod, $objException->cod, $objInternal->cod, EnumResponseCode::INTERNAL_SERVER_ERROR);
    }

    static function paramsNotValid(AppGenericUI $ui, $key, $params = array(), $file = null) {
        $objInfo = MessageUtility::messageInfo($key, empty($file) ? $ui->localefile : $file, Codes::get("PARAM_NULL"));
        $objException = new ObjCodMessage(null, null);
        $messageParams = TranslatorUtility::__translate("ERROR_PARAMS_NULL", "errors");
        if (!ArrayUtility::isEmpty($params)) {
            $messageParams .= "[ ";
            foreach ($params as $name => $value) {
                $messageParams .= "{$name}={$value};";
            }
            $messageParams .= " ]";
        }
        $objInternal = new ObjCodMessage(Codes::get("PARAM_NULL"), $messageParams);
        $ui->error($objInfo->message, $objException->message, $objInternal->message, $objInfo->cod, $objException->cod, $objInternal->cod, EnumResponseCode::INTERNAL_SERVER_ERROR);
    }

    // --------------------- GENERIC
    static function errorInternal(AppGenericUI $ui, $cod, $keyInfo, $argsInfo = null, $keyInternal, $argsInternal = null, $fileInfo = null, $fileInternal = null, $responseCod = null) {
        $objInfo = MessageUtility::messageInfo($keyInfo, empty($fileInfo) ? $ui->localefile : $fileInfo, Codes::get($cod), $argsInfo);
        $objException = new ObjCodMessage(null, null);
        $objInternal = MessageUtility::messageInternal($keyInternal, empty($fileInternal) ? $ui->localefile : $fileInternal, Codes::get($cod), $argsInternal);
        $ui->error($objInfo->message, $objException->message, $objInternal->message, $objInfo->cod, $objException->cod, $objInternal->cod, empty($responseCod) ? EnumResponseCode::INTERNAL_SERVER_ERROR : $responseCod);
    }

    static function exceptionInternal($exception, AppGenericUI $ui, $cod, $keyInfo, $argsInfo = null, $keyInternal = null, $argsInternal = null, $fileInfo = null, $fileInternal = null, $responseCod = null) {
        $objInfo = MessageUtility::messageInfo($keyInfo, empty($fileInfo) ? $ui->localefile : $fileInfo, Codes::get($cod), $argsInfo);
        $objException = new ObjCodMessage(null, null);
        $objInternal = MessageUtility::messageInternal($keyInternal, empty($fileInternal) ? $ui->localefile : $fileInternal, Codes::get($cod), $argsInternal);
        $ui->error($objInfo->message, $exception, $objInternal->message, $objInfo->cod, null, $objInternal->cod, empty($responseCod) ? EnumResponseCode::INTERNAL_SERVER_ERROR : $responseCod);
    }

    /**
     * Logga un messaggio generico (category INFORMATION) nel file di delegate
     * @param AppGenericUI $ui delegate sorgente
     * @param ObjCodMessage $obj oggetto messaggio
     * @param string $flag di default "log_delegate"
     * @param string $log di default "delegate"
     */
    static function logMessage(AppGenericUI $ui, ObjCodMessage $obj, $flag = "log_delegate", $log = "delegate") {
        MessageUtility::logMessageByObject($obj, $flag, $log, $ui->logSource());
    }

    static function getObj($flgJson, $obj, $asArray = false) {
        return $flgJson ? json_decode($obj, $asArray) : $obj;
    }

    static function getObjList($flgJson, $obj, $asArray = false) {
        return $flgJson ? json_decode($obj, $asArray) : $obj;
    }

    static function mapEntityByJson(AppGenericBS $bs, $json, $properties = array(), $id = null, $fieldForId = "id") {
        $obj = DelegateUtility::getObj(true, $json);
        $tmpEntity = null;
        if (!empty($id)) {
            $tmpEntity = $bs->unique($id);
        } elseif (!empty($fieldForId) && property_exists($obj, $fieldForId)) {
            $id = $obj->{$fieldForId};
            $tmpEntity = $bs->unique($obj->{$fieldForId});
        } else {
            $tmpEntity = $bs->instance();
        }
        $entityObj = new ObjEntity($bs->className, $obj, $properties);
        if (!empty($entityObj->value)) {
            return $entityObj->mapInstance($tmpEntity, !empty($id) ? true : false);
        }
        return null;
    }

    static function mapEntityListByJson(AppGenericBS $bs, $json, $properties = array(), $fieldForId = "id") {
        $entities = array();
        $list = DelegateUtility::getObjList(true, $json);
        foreach ($list as $obj) {
            $entity = null;
            if (!empty($fieldForId) && property_exists($obj, $fieldForId)) {
                $entity = DelegateUtility::mapEntityByJson($bs, json_encode($obj), $properties, $obj->{$fieldForId}, $fieldForId);
            } else {
                $entity = DelegateUtility::mapEntityByJson($bs, json_encode($obj), $properties, null, $fieldForId);
            }
            if (!empty($entity)) {
                array_push($entities, $entity);
            }
        }
        return $entities;
    }

    static function mapEntityJsonByDelegate(AppGenericUI $ui, AppGenericBS $bs, $json, $id = null, $fieldForId = "id") {
        $obj = DelegateUtility::getObj(true, $json);
        $tmpEntity = null;
        if (!empty($id)) {
            $tmpEntity = $bs->unique($id);
        } elseif (!empty($fieldForId) && property_exists($obj, $fieldForId)) {
            $id = $obj->{$fieldForId};
            $tmpEntity = $bs->unique($obj->{$fieldForId});
        } else {
            $tmpEntity = $bs->instance();
        }
        $entityObj = new ObjEntity($bs->className, $obj, $ui->obj);
        if (!empty($entityObj->value)) {
            return $entityObj->mapInstance($tmpEntity, !empty($id) ? true : false);
        }
        return null;
    }

    static function mapEntityListJsonByDelegate(AppGenericUI $ui, AppGenericBS $bs, $json, $properties = array(), $fieldForId = "id") {
        $entities = array();
        $list = DelegateUtility::getObjList(true, $json);
        foreach ($list as $obj) {
            $entity = null;
            if (!empty($fieldForId) && property_exists($obj, $fieldForId)) {
                $entity = DelegateUtility::mapEntityByJson($bs, json_encode($obj), $ui->obj, $obj->{$fieldForId}, $fieldForId);
            } else {
                $entity = DelegateUtility::mapEntityByJson($bs, json_encode($obj), $ui->obj, null, $fieldForId);
            }
            if (!empty($entity)) {
                array_push($entities, $entity);
            }
        }
        return $entities;
    }

    /**
     * Dato un business e un oggetto in input, con un array di properties mappabili nell'oggetto,
     * il sistema ritorna la conversione da json a Entity, se $obj è un json, altrimenti ritorna $obj stesso.
     * @param AppGenericBS $bs business che gestisce l'entity da creare
     * @param type $obj oggetto json o cake
     * @param array $properties array di ObjPropertyEntity (@see ObjPropertyEntity)
     * @return mixed entity cake in base ai dati in input (json o cake)
     */
    static function getEntityToSave(AppGenericBS $bs, $obj, $properties = array()) {
        $entity = null;
        if (!empty($obj) && !ArrayUtility::isEmpty($properties)) {
            if (is_array($obj) && array_key_exists($bs->className, $obj)) {
                return $obj;
            } else {
                $entity = DelegateUtility::mapEntityByJson($bs, $obj, $properties, null, null);
            }
        }
        return $entity;
    }

    static function getEntityToEdit(AppGenericBS $bs, $obj, $properties = array(), $id = null, $fieldForId = "id") {
        $entity = null;
        if (!empty($obj) && !ArrayUtility::isEmpty($properties)) {
            if (is_array($obj) && array_key_exists($bs->className, $obj)) {
                if (!empty($id)) {
                    $obj[$fieldForId] = $id;
                }
                return $obj;
            } else {
                $entity = DelegateUtility::mapEntityByJson($bs, $obj, $properties, $id);
            }
        }
        return $entity;
    }

    /**
     * Aggiunge underscore e l'id al valore di un codice univoco
     * @param type $bs oggetto business
     * @param type $obj oggetto entity
     * @param string $id id dell'oggetto
     * @param string $fieldForCod nome del campo codice
     * @param string $fieldForId nome del campo identificativo
     */
    static function integratEntityCod(AppGenericBS $bs, &$obj, $id = null, $fieldForCod = "cod", $fieldForId = "id") {
        if (!empty($obj[$bs->className][$fieldForCod])) {
            if (empty($id) && !empty($obj[$bs->className][$fieldForId])) {
                $id = $obj[$bs->className][$fieldForId];
            }
            if (!empty($id)) {
                $newCod = $obj[$bs->className][$fieldForCod] . "_" . $id;
                $bs->updateField($id, $fieldForCod, $newCod);
            }
        }
    }

    /**
     * Ritorna l'id di una entity a fronte di alcune condizioni di univocità
     * @param AppGenericBS $bs oggetto business
     * @param array $fields condizioni da aggiungere al business
     * @param string $id valore del campo id
     * @param string $fieldForId nome del campo id
     * @return string|int il valore dell'id di una entity a fronte di alcune condizioni di univocità
     */
    static function getEntityIdByFields(AppGenericBS $bs, $fields = array(), $id = null, $fieldForId = "id") {
        $entity = null;
        if (!empty($id)) {
            return $id;
        } elseif (!ArrayUtility::isEmpty($fields)) {
            foreach ($fields as $key => $value) {
                $bs->addCondition($key, $value);
            }
            $entity = $bs->unique();
        }
        return !empty($entity) ? $entity[$bs->className][$fieldForId] : null;
    }

    /**
     * Reimposta i campi da ritornare durante una query, escludendone alcuni
     *
     * @param AppGenericBS $bs oggetto business
     * @param array $fields lista dei campi da escludere
     * @return void
     */
    static function excludeFieldsByQuery(AppGenericBS&$bs, $fields = array()) {
        $fields = array_keys($bs->dao->getColumnTypes());
        foreach ($fields as $field) {
            $key = array_search($field, $fields);
            unset($fields[$key]);
        }
        $bs->addFields($fields, true);
    }

    // ---- PARAMETERS
    static function getConditions(AppGenericUI $ui, $conditions) {
        if ($ui->json && !empty($conditions)) {
            $jsonDecoder = new JsonDecoder();
            $conditions = $jsonDecoder->decodeMultiple($conditions, DBCondition::class);
        }
        return $conditions;
    }
    static function getOrders(AppGenericUI $ui, $orders) {
        if ($ui->json && !empty($orders)) {
            $jsonDecoder = new JsonDecoder();
            $orders = $jsonDecoder->decodeMultiple($orders, DBOrder::class);
        }
        return $orders;
    }
    static function getPagination(AppGenericUI $ui, $paginate) {
        if ($ui->json && !empty($paginate)) {
            $jsonDecoder = new JsonDecoder();
            $paginate = $jsonDecoder->decode($paginate, DBPaginate::class);
        }
        return $paginate;
    }
}
