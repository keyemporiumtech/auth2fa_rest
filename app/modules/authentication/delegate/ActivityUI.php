<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("ActivityBS", "modules/authentication/business");
App::uses("UserprofileBS", "modules/authentication/business");
App::uses("TypologicalUI", "modules/cakeutils/delegate");
App::uses("ApploginUtility", "modules/authentication/utility");
App::uses("AppactivityUtility", "modules/authentication/utility");

class ActivityUI extends AppGenericUI {

    function __construct() {
        parent::__construct("ActivityUI");
        $this->localefile = "activity";
        $this->obj = array(
            new ObjPropertyEntity("name", null, ""),
            new ObjPropertyEntity("namecod", null, ""),
            new ObjPropertyEntity("description", null, ""),
            new ObjPropertyEntity("piva", null, ""),
            new ObjPropertyEntity("born", null, ""),
            new ObjPropertyEntity("tpactivity", null, 0),
            new ObjPropertyEntity("tpcat", null, 0),
            new ObjPropertyEntity("parent_id", null, 0),
            new ObjPropertyEntity("lft", null, 0),
            new ObjPropertyEntity("rght", null, 0),
            new ObjPropertyEntity("flgtest", null, 0),
        );
    }

    function get($id = null, $piva = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($piva)) {
                DelegateUtility::paramsNull($this, "ERROR_ACTIVITY_NOT_FOUND");
                return "";
            }
            $activityBS = new ActivityBS();
            $activityBS->json = $this->json;
            parent::completeByJsonFkVf($activityBS);
            if (!empty($piva)) {
                $activityBS->addCondition("piva", $piva);
            }
            $this->ok();
            return $activityBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_ACTIVITY_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $activityBS = !empty($bs) ? $bs : new ActivityBS();
            $activityBS->json = $this->json;
            parent::completeByJsonFkVf($activityBS);
            parent::evalConditions($activityBS, $conditions);
            parent::evalOrders($activityBS, $orders);
            $activitys = $activityBS->table($conditions, $orders, $paginate);
            parent::evalPagination($activityBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($activitys);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($activityIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $activity = DelegateUtility::getEntityToSave(new ActivityBS(), $activityIn, $this->obj);

            if (!empty($activity)) {

                $activityBS = new ActivityBS();
                $id_activity = $activityBS->save($activity);
                parent::saveInGroup($activityBS, $id_activity);

                parent::commitTransaction();
                if (!empty($id_activity)) {
                    $this->ok(TranslatorUtility::__translate("INFO_ACTIVITY_SAVE", $this->localefile));
                    return $id_activity;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_ACTIVITY_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_ACTIVITY_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_ACTIVITY_SAVE");
            return 0;
        }
    }

    function edit($id, $activityIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $activity = DelegateUtility::getEntityToEdit(new ActivityBS(), $activityIn, $this->obj, $id);

            if (!empty($activity)) {
                $activityBS = new ActivityBS();
                $id_activity = $activityBS->save($activity);
                parent::saveInGroup($activityBS, $id_activity);
                parent::delInGroup($activityBS, $id_activity);

                parent::commitTransaction();
                if (!empty($id_activity)) {
                    $this->ok(TranslatorUtility::__translate("INFO_ACTIVITY_EDIT", $this->localefile));
                    return $id_activity;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_ACTIVITY_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_ACTIVITY_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_ACTIVITY_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $activityBS = new ActivityBS();
                $activityBS->delete($id);
                parent::delInGroup($activityBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_ACTIVITY_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_ACTIVITY_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_ACTIVITY_DELETE");
            return false;
        }
    }

    // ---------------- TYPOLOGICAL
    function tpactivity($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "tpactivity";
        try {
            $typologicalUI = new TypologicalUI("Tpactivity", "authentication");
            $typologicalUI->json = $this->json;
            parent::assignToDelegate($typologicalUI);
            $result = $typologicalUI->table($conditions, $orders, $paginate, $bs);
            parent::mappingDelegate($typologicalUI);
            return $result;
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function tpcat($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "tpcat";
        try {
            $typologicalUI = new TypologicalUI("Tpcat", "authentication");
            $typologicalUI->json = $this->json;
            parent::assignToDelegate($typologicalUI);
            $result = $typologicalUI->table($conditions, $orders, $paginate, $bs);
            parent::mappingDelegate($typologicalUI);
            return $result;
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    // ---------------- TREE
    function tree($id_activity = null, $async = false, $conditions = null, $orders = null, $fields = null) {
        $this->LOG_FUNCTION = "tree";
        try {
            $activityBS = new ActivityBS();
            $activityBS->json = $this->json;
            if (!empty($fields)) {
                if ($this->json) {
                    $fields = json_decode($fields, true);
                }
                if (!ArrayUtility::isEmpty($fields)) {
                    $activityBS->addFields($fields, true);
                    array_push($activityBS->params['fields'], "parent_id");
                }
            }
            parent::completeByJsonFkVf($activityBS);
            parent::evalConditions($activityBS, $conditions);
            parent::evalOrders($activityBS, $orders);
            $activities = $activityBS->tree($id_activity, "parent_id", $async);
            $this->ok();
            return $activities;
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    // --------------------------- MANAGE PROFILE
    function getActivityLogged(CakeRequest $request) {
        $this->LOG_FUNCTION = "getActivityLogged";
        try {

            $username = ApploginUtility::getUsernameLogged($request);
            $this->ok();
            return AppactivityUtility::getActivityLogged($username);
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_ACTIVITY_NOT_FOUND");
            return false;
        }
    }
    function changeActivity($id = null, $piva = null, $id_user = null, $username = null) {
        $this->LOG_FUNCTION = "changeActivity";
        try {
            if (empty($id) && empty($piva)) {
                DelegateUtility::paramsNull($this, "ERROR_CHANGE_ACTIVITY");
                return false;
            }
            if (empty($id_user) && empty($username)) {
                DelegateUtility::paramsNull($this, "ERROR_CHANGE_ACTIVITY");
                return false;
            }

            $activityBS = new ActivityBS();
            if (!empty($piva)) {
                $activityBS->addCondition("piva", $piva);
            }
            $activity = $activityBS->unique($id);

            $userBS = new UserBS();
            if (!empty($username)) {
                $userBS->addCondition("username", $username);
            }
            $user = $userBS->unique($id_user);

            if (!CakeSession::check($user['User']['username'])) {
                DelegateUtility::errorInternal($this, "USERNOT_IN_SESSION", "ERROR_CHANGE_PROFILE", null, "ERROR_CHANGE_ACTIVITY_USER_NOT_SESSION", array(
                    $activity['Activity']['namecod'],
                    $user['User']['username'],
                ));
                return false;
            }

            AppactivityUtility::memoActivity($user['User']['username'], $activity['Activity']['piva']);

            $this->ok(TranslatorUtility::__translate_args("INFO_CHANGE_ACTIVITY", array($activity['Activity']['namecod']), $this->localefile));
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_CHANGE_ACTIVITY");
            return false;
        }
    }

    // ------------ EMPLOYERS
    function employers($id = null, $piva = null, $conditions = null, $orders = null, $paginate = null) {
        $this->LOG_FUNCTION = "employers";
        try {
            if (empty($id) && empty($piva)) {
                DelegateUtility::paramsNull($this, "ERROR_CHANGE_ACTIVITY");
                return false;
            }

            $activityBS = new ActivityBS();
            if (!empty($piva)) {
                $activityBS->addCondition("piva", $piva);
            }
            $activity = $activityBS->unique($id);

            $userprofileBS = new UserprofileBS();
            $userprofileBS->acceptNull = true;
            $filtersID = $userprofileBS->filterUserActivity($activity['Activity']['id']);
            $ids = array();
            foreach ($filtersID as $filterId) {
                array_push($ids, $filterId['Userprofile']['user']);
            }

            $userBS = new UserBS();
            $userBS->json = $this->json;
            $userBS->acceptNull = true;
            $userBS->addCondition("id", $ids);
            parent::completeByJsonFkVf($userBS);
            parent::evalConditions($userBS, $conditions);
            parent::evalOrders($userBS, $orders);
            $users = $userBS->table($conditions, $orders, $paginate);
            parent::evalPagination($userBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($users);

        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_CHANGE_ACTIVITY");
            return false;
        }
    }
}
