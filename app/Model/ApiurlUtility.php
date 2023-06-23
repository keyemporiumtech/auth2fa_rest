<?php
App::uses("Enables", "Config/system");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("ArrayUtility", "modules/coreutils/utility");
App::uses("ApploginUtility", "modules/authentication/utility");
App::uses("PermissionUtility", "modules/authentication/utility");

class ApiurlUtility {

    // -------- API UTILITY
    /**
     * Costruisce un array contenente tutte le api di CRUD (Unique=get, List=table, Save=save, Edit=edit, Delete=delete) di un controller
     * impostando i campi di check al valore iniziale false
     * @param $ctrlName Nome del controller
     * @return mixed[] array di api
     */
    static function buildGenericArray($ctrlName) {
        return [
            $ctrlName . "Unique" => array(
                "controller" => $ctrlName,
                "action" => "get",
                "postLogin" => false,
                "profilePermission" => false,
                "permissions" => [],
                "checkUser" => false,
                "activityPermission" => false,
                "activityPermissions" => [],
                "checkUserActivity" => false,
                "checkActivity" => false
            ),
            $ctrlName . "List" => array(
                "controller" => $ctrlName,
                "action" => "table",
                "postLogin" => false,
                "profilePermission" => false,
                "permissions" => [],
                "checkUser" => false,
                "activityPermission" => false,
                "activityPermissions" => [],
                "checkUserActivity" => false,
                "checkActivity" => false
            ),
            $ctrlName . "Save" => array(
                "controller" => $ctrlName,
                "action" => "save",
                "postLogin" => false,
                "profilePermission" => false,
                "permissions" => [],
                "checkUser" => false,
                "activityPermission" => false,
                "activityPermissions" => [],
                "checkUserActivity" => false,
                "checkActivity" => false
            ),
            $ctrlName . "Edit" => array(
                "controller" => $ctrlName,
                "action" => "edit",
                "postLogin" => false,
                "profilePermission" => false,
                "permissions" => [],
                "checkUser" => false,
                "activityPermission" => false,
                "activityPermissions" => [],
                "checkUserActivity" => false,
                "checkActivity" => false
            ),
            $ctrlName . "Delete" => array(
                "controller" => $ctrlName,
                "action" => "delete",
                "postLogin" => false,
                "profilePermission" => false,
                "permissions" => [],
                "checkUser" => false,
                "activityPermission" => false,
                "activityPermissions" => [],
                "checkUserActivity" => false,
                "checkActivity" => false
            ),
        ];
    }

    /**
     * Aggiunge una api ad un array esistente
     * @param string $ctrlName Nome del controller
     * @param string $actionName Nome della action
     * @param mixed[] $api array esistente di api
     */
    static function assignAction($ctrlName, $actionName, &$api) {
        $api[$ctrlName . ucfirst($actionName)] = array(
            "controller" => $ctrlName,
            "action" => $actionName,
            "postLogin" => false,
            "profilePermission" => false,
            "permissions" => [],
            "checkUser" => false,
            "activityPermission" => false,
            "activityPermissions" => [],
            "checkUserActivity" => false,
            "checkActivity" => false
        );

    }

    static function assignReserve($keys = array(), $flag = true, &$api = array()) {
        if (ArrayUtility::isEmpty($keys)) {
            $keys = array_keys($api);
        }
        foreach ($keys as $key) {
            if (array_key_exists($key, $api)) {
                $api[$key]['postLogin'] = $flag;
            }
        }
    }

    static function assignCheckUser($keys = array(), $flag = true, &$api = array()) {
        if (ArrayUtility::isEmpty($keys)) {
            $keys = array_keys($api);
        }
        foreach ($keys as $key) {
            if (array_key_exists($key, $api)) {
                $api[$key]['checkUser'] = $flag;
            }
        }
    }

    static function assignCheckUserActivity($keys = array(), $flag = true, &$api = array()) {
        if (ArrayUtility::isEmpty($keys)) {
            $keys = array_keys($api);
        }
        foreach ($keys as $key) {
            if (array_key_exists($key, $api)) {
                $api[$key]['checkUserActivity'] = $flag;
            }
        }
    }

    static function assignCheckActivity($keys = array(), $flag = true, &$api = array()) {
        if (ArrayUtility::isEmpty($keys)) {
            $keys = array_keys($api);
        }
        foreach ($keys as $key) {
            if (array_key_exists($key, $api)) {
                $api[$key]['checkActivity'] = $flag;
            }
        }
    }

    static function assignUserprofilePermissions($keys = array(), $permissions = array(), $flag = true, &$api = array()) {
        if (ArrayUtility::isEmpty($keys)) {
            $keys = array_keys($api);
        }
        foreach ($keys as $key) {
            if (array_key_exists($key, $api)) {
                $api[$key]['profilePermission'] = $flag;
                $api[$key]['permissions'] = $permissions;
                array_push($api[$key]['permissions'], "SUPERVISOR", "ALL_PERMISSIONS");
            }
        }
    }
    static function assignActivityprofilePermissions($keys = array(), $permissions = array(), $flag = true, &$api = array()) {
        if (ArrayUtility::isEmpty($keys)) {
            $keys = array_keys($api);
        }
        foreach ($keys as $key) {
            if (array_key_exists($key, $api)) {
                $api[$key]['activityPermission'] = $flag;
                $api[$key]['activityPermissions'] = $permissions;
                array_push($api[$key]['activityPermissions'], "SUPERVISOR", "MANAGER", "ALLACTIVITY_PERMISSIONS");
            }
        }
    }

    static function buildGenericApi($ctrlName, $permissionName, &$api, $flgreserve) {
        if ($flgreserve) {
            ApiurlUtility::assignReserve(
                array(
                    $ctrlName . 'Unique',
                    $ctrlName . 'List',
                    $ctrlName . 'Save',
                    $ctrlName . 'Edit',
                    $ctrlName . 'Delete',
                ), true, $api);
        }
        ApiurlUtility::assignUserprofilePermissions(
            array(
                $ctrlName . 'Unique',
                $ctrlName . 'List',
            ), array("VIEW_" . $permissionName), true, $api);
        ApiurlUtility::assignActivityprofilePermissions(
            array(
                $ctrlName . 'Unique',
                $ctrlName . 'List',
            ), array("VIEW_ACTIVITY_" . $permissionName), true, $api);
        ApiurlUtility::assignUserprofilePermissions(
            array(
                $ctrlName . 'Save',
                $ctrlName . 'Edit',
                $ctrlName . 'Delete',
            ), array("MANAGE_" . $permissionName), true, $api);
        ApiurlUtility::assignActivityprofilePermissions(
            array(
                $ctrlName . 'Save',
                $ctrlName . 'Edit',
                $ctrlName . 'Delete',
            ), array("MANAGE_ACTIVITY_" . $permissionName), true, $api);
    }

    static function buildGenericApiForActions($ctrlName, $actionsName = array(), $permissionName, &$api, $flgreserve, $flgEdit = false) {
        $keys = array();
        foreach ($actionsName as $actionName) {
            array_push($keys, $ctrlName . ucfirst($actionName));
        }
        if ($flgreserve) {
            ApiurlUtility::assignReserve($keys, true, $api);
        }
        if ($flgEdit) {
            ApiurlUtility::assignUserprofilePermissions($keys, array("MANAGE_" . $permissionName), true, $api);
            ApiurlUtility::assignActivityprofilePermissions($keys, array("MANAGE_ACTIVITY_" . $permissionName), true, $api);
        } else {
            ApiurlUtility::assignUserprofilePermissions($keys, array("VIEW_" . $permissionName), true, $api);
            ApiurlUtility::assignActivityprofilePermissions($keys, array("VIEW_ACTIVITY_" . $permissionName), true, $api);
        }
    }

    // -------- API MANAGEMENT PROFILES
    static function buildApiProfilesManagement($flgreserve) {
        // profile
        $PROFILE = ApiurlUtility::buildGenericArray("profile");
        ApiurlUtility::buildGenericApi("profile", "PROFILES", $PROFILE, $flgreserve);
        ApiurlUtility::assignCheckUser(null, true, $PROFILE);
        ApiurlUtility::assignCheckUserActivity(null, true, $PROFILE);
        ApiurlUtility::assignCheckActivity(null, true, $PROFILE);

        // permission
        $PERMISSION = ApiurlUtility::buildGenericArray("permission");
        ApiurlUtility::buildGenericApi("permission", "PROFILES", $PERMISSION, $flgreserve);
        ApiurlUtility::assignCheckUser(null, true, $PERMISSION);
        ApiurlUtility::assignCheckUserActivity(null, true, $PERMISSION);
        ApiurlUtility::assignCheckActivity(null, true, $PERMISSION);

        // profilepermission
        $PROFILEPERMISSION = ApiurlUtility::buildGenericArray("profilepermission");
        ApiurlUtility::buildGenericApi("profilepermission", "PROFILES", $PROFILEPERMISSION, $flgreserve);
        ApiurlUtility::assignAction("profilepermission", "updatepermissions", $PROFILEPERMISSION);
        ApiurlUtility::buildGenericApiForActions("profilepermission", array("updatepermissions"), "PROFILES", $PROFILEPERMISSION, $flgreserve, true);
        ApiurlUtility::assignCheckUser(null, true, $PROFILEPERMISSION);
        ApiurlUtility::assignCheckUserActivity(null, true, $PROFILEPERMISSION);
        ApiurlUtility::assignCheckActivity(null, true, $PROFILEPERMISSION);

        // userprofile
        $USERPROFILE = ApiurlUtility::buildGenericArray("userprofile");
        ApiurlUtility::buildGenericApi("userprofile", "PROFILES", $USERPROFILE, $flgreserve);
        ApiurlUtility::assignAction("userprofile", "createprofile", $USERPROFILE);
        ApiurlUtility::assignAction("userprofile", "removeprofile", $USERPROFILE);
        ApiurlUtility::buildGenericApiForActions("userprofile", array("createprofile", "removeprofile"), "PROFILES", $USERPROFILE, $flgreserve, true);
        ApiurlUtility::assignCheckUser(null, true, $USERPROFILE);
        ApiurlUtility::assignCheckUserActivity(null, true, $USERPROFILE);
        ApiurlUtility::assignCheckActivity(null, true, $USERPROFILE);

        // activityprofile
        $ACTIVITYPROFILE = ApiurlUtility::buildGenericArray("activityprofile");
        ApiurlUtility::buildGenericApi("activityprofile", "PROFILES", $ACTIVITYPROFILE, $flgreserve);
        ApiurlUtility::assignAction("activityprofile", "createprofile", $ACTIVITYPROFILE);
        ApiurlUtility::assignAction("activityprofile", "removeprofile", $ACTIVITYPROFILE);
        ApiurlUtility::buildGenericApiForActions("activityprofile", array("createprofile", "removeprofile"), "PROFILES", $ACTIVITYPROFILE, $flgreserve, true);
        ApiurlUtility::assignCheckUser(null, true, $ACTIVITYPROFILE);
        ApiurlUtility::assignCheckUserActivity(null, true, $ACTIVITYPROFILE);
        ApiurlUtility::assignCheckActivity(null, true, $ACTIVITYPROFILE);

        return array_merge($PROFILE, $PERMISSION, $PROFILEPERMISSION, $USERPROFILE, $ACTIVITYPROFILE);
    }

}
?>