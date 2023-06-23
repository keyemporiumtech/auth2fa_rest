<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("StringUtility", "modules/coreutils/utility");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
App::uses("DBCondition", "modules/cakeutils/classes");
// inner
App::uses("ApiurlUtility", "Model");
App::uses("ApploginUtility", "modules/authentication/utility");
App::uses("PermissionUtility", "modules/authentication/utility");
App::uses("PermissionRelationsUtility", "modules/authentication/utility");
App::uses("UserBS", "modules/authentication/business");
App::uses("ActivityBS", "modules/authentication/business");

class ApiurlUI extends AppGenericUI {

    function __construct($name = null) {
        parent::__construct(empty($name) ? "ApiurlUI" : $name);
        $this->localefile = "";
        $this->buildApi();
    }

    function buildApi() {

    }

    function checkApi(AppController $ctrl, $key = null) {
        if (Enables::isDebug()) {
            if (array_key_exists($key, $this->api)) {
                debug("ApiurlUtility");
                debug("chiave richiesta = {$key} da " . $ctrl->request->params['controller'] . "/" . $ctrl->request->params['action']);
                debug($this->api[$key]);
            }
        }
        PermissionUtility::logMessage("\n\nchiave richiesta = {$key} da " . $ctrl->request->params['controller'] . "/" . $ctrl->request->params['action'], "CHECK_PERMISSIONS");
    }

    function getApiObj($key = null) {
        if (!empty($key) && array_key_exists($key, $this->api)) {
            return $this->api[$key];
        }
        return null;
    }

    function manageCheck(AppController $ctrl, $objUrl) {

        // logged
        PermissionUtility::logMessage("postLogin => " . (empty($objUrl['postLogin']) ? "NONE" : "X"));
        if ($objUrl['postLogin']) {
            ApploginUtility::checkTokenLogin($ctrl);
        }

        $checkError = false;

        // user permissions
        PermissionUtility::logMessage("profilePermission => " . (empty($objUrl['profilePermission']) ? "NONE" : "X"));
        if ($objUrl['profilePermission']) {
            PermissionUtility::logMessage("permessi richiesti=" . ArrayUtility::toPrintString($objUrl['permissions'], false));
        }
        if ($objUrl['profilePermission'] && !PermissionUtility::checkpermissionsUI($ctrl->request, $this, $objUrl['permissions'])) {
            $checkError = true;
            PermissionUtility::logMessage("ESITO: KO");
        } else {
            PermissionUtility::logMessage("ESITO: OK");
        }

        // user permission for user
        PermissionUtility::logMessage("checkUser => " . (empty($objUrl['checkUser']) ? "NONE" : "X"));
        if ($checkError && $objUrl['checkUser']) {
            $username = null;
            $this->checkUserInfo($ctrl, $username);
            if (!PermissionUtility::checkGrantForUser($ctrl->request, null, $username, $objUrl['permissions'])) {
                $checkError = true;
                PermissionUtility::logMessage("ESITO: KO");
            } else {
                $checkError = false;
                PermissionUtility::logMessage("ESITO: OK");
            }
        } else {
            PermissionUtility::logMessage("ESITO: OK");
        }

        // activity permissions
        PermissionUtility::logMessage("activityPermission => " . (empty($objUrl['activityPermission']) ? "NONE" : "X"));

        if ($objUrl['activityPermission']) {
            PermissionUtility::logMessage("permessi richiesti=" . ArrayUtility::toPrintString($objUrl['activityPermissions'], false));
        }
        if ($checkError && $objUrl['activityPermission']) {
            if (!PermissionUtility::checkpermissionsActivityUI($ctrl->request, $this, $objUrl['activityPermissions'])) {
                $checkError = true;
                PermissionUtility::logMessage("ESITO: KO");
            } else {
                $checkError = false;
                PermissionUtility::logMessage("ESITO: OK");
            }
        } else {
            PermissionUtility::logMessage("ESITO: OK");
        }

        // -------------- OLTRE AD AVERE IL PERMESSO AZIENDALE DEVO VERIFICARE SE STO RECUPERANDO DATI AZIENDALI O DI UN DIPENDENTE
        // se devo cercare i dati di un utente questo deve essere legato all'azienda
        // user permission for activityuser
        PermissionUtility::logMessage("checkUserActivity => " . (empty($objUrl['checkUserActivity']) ? "NONE" : "X"));
        if ($checkError && $objUrl['checkUserActivity']) {

            $username = null;
            $this->checkUserInfo($ctrl, $username);
            $piva = null;
            $this->checkActivityInfo($ctrl, $piva);
            if (!PermissionUtility::checkGrantForUserActivity($ctrl->request, null, $username, $piva)) {
                $checkError = true;
                PermissionUtility::logMessage("ESITO: KO");
            } elseif (!PermissionUtility::checkGrantForUser($ctrl->request, null, $username, $objUrl['activityPermissions'])) {
                $checkError = true;
                PermissionUtility::logMessage("ESITO: KO");
            } else {
                $checkError = false;
                PermissionUtility::logMessage("ESITO: OK");
            }

        } else {
            PermissionUtility::logMessage("ESITO: OK");
        }

        // se sto cercando i dati di un'azienda o deve essere la mia oppure deve avermi autorizzato
        // user permission for activity
        PermissionUtility::logMessage("checkActivity => " . (empty($objUrl['checkActivity']) ? "NONE" : "X"));
        // non devo cercare i dati utente (valido per info su una activity)
        $username = null;
        $this->checkUserInfo($ctrl, $username);
        if ($checkError && empty($username) && $objUrl['checkActivity']) {
            $piva = null;
            $this->checkActivityInfo($ctrl, $piva);
            if (!PermissionUtility::checkGrantForActivity($ctrl->request, null, $piva, $objUrl['activityPermissions'])) {
                $checkError = true;
                PermissionUtility::logMessage("ESITO: KO");
            } else {
                $checkError = false;
                PermissionUtility::logMessage("ESITO: OK");
            }
        } else {
            PermissionUtility::logMessage("ESITO: OK");
        }

        if ($checkError) {
            $ctrl->forceResponse($this->status, "");
        }
    }

    function sendErrorApiNotFound($ctrl, $url) {
        $this->sendError(
            $ctrl,
            "API_NOT_FOUND",
            "ERROR_NO_DATA_FOUND",
            null,
            "ERROR_API_NULL",
            array(empty($url) ? "URL EMPTY" : $url),
            "errors",
            "errors"
        );
    }

    function sendError(AppController $ctrl, $cod, $keyInfo, $argsInfo = null, $keyInternal, $argsInternal = null, $fileInfo = null, $fileInternal = null, $responseCod = null) {
        DelegateUtility::errorInternal(
            $this,
            $cod,
            $keyInfo,
            $argsInfo,
            $keyInternal,
            $argsInternal,
            $fileInfo,
            $fileInternal,
            $responseCod);
        $ctrl->forceResponse($this->status, "");
    }

    /*
    "user" => array(
    "controller" => "user",
    "action" => "get",
    "postLogin" => true,
    "profilePermission" => true,
    "permissions" => [],
    "checkUser" => true,
    "activityPermission" => true,
    "activityPermissions" => [],
    "checkActivity" => true
    ),
     */
    public $api = [];

    // --- check parameters
    function checkUserInfo(AppController $ctrl, &$username = null) {
        $ctrl->evalParam($username, "username");
        if (empty($username)) {
            $id_user = null;
            $ctrl->evalParam($id_user, "id_user");
            if (!empty($id_user)) {
                $userBS = new UserBS();
                $userBS->acceptNull = true;
                $user = $userBS->unique($id_user);
                if ($user) {
                    $username = $user['User']['username'];
                }
            }
        }
        if (empty($username)) {
            $this->checkUserInfoByFilters($ctrl, $username);
        }
    }

    function checkUserInfoByFilters(AppController $ctrl, &$username = null) {
        $jsonFilters = null;
        $ctrl->evalParam($jsonFilters, "filters");
        if (!empty($jsonFilters)) {
            if (!empty($ctrl->delegate)) {
                /** @var DBCondition[] */
                $filters = DelegateUtility::getConditions($ctrl->delegate, $jsonFilters);
                $id_user = null;
                if (!empty($filters)) {
                    foreach ($filters as $filter) {
                        if (!empty($filter) && property_exists($filter, "key") && property_exists($filter, "value")) {
                            if (StringUtility::contains($filter->key, "username") || StringUtility::contains($filter->key, "user_fk.username")) {
                                $username = $filter->value;
                            } elseif (StringUtility::contains($filter->key, "id_user") || StringUtility::contains($filter->key, "user") || StringUtility::contains($filter->key, "user_fk.id")) {
                                $id_user = $filter->value;
                            }
                        }
                    }
                }
            }
            if (empty($username) && !empty($id_user)) {
                $userBS = new UserBS();
                $userBS->acceptNull = true;
                $user = $userBS->unique($id_user);
                if ($user) {
                    $username = $user['User']['username'];
                }
            }
        }
    }

    function checkActivityInfo(AppController $ctrl, &$piva = null) {
        $ctrl->evalParam($piva, "piva");
        if (empty($piva)) {
            $id_activity = null;
            $ctrl->evalParam($id_activity, "id_activity");
            if (!empty($id_activity)) {
                $activityBS = new ActivityBS();
                $activityBS->acceptNull = true;
                $activity = $activityBS->unique($id_activity);
                if ($activity) {
                    $piva = $activity['Activity']['piva'];
                }
            }
        }
        if (empty($piva)) {
            $this->checkActivityInfoByFilters($ctrl, $piva);
        }
    }

    function checkActivityInfoByFilters(AppController $ctrl, &$piva = null) {
        $jsonFilters = null;
        $ctrl->evalParam($jsonFilters, "filters");
        if (!empty($jsonFilters)) {
            if (!empty($ctrl->delegate)) {
                /** @var DBCondition[] */
                $filters = DelegateUtility::getConditions($ctrl->delegate, $jsonFilters);
                $id_activity = null;
                if (!empty($filters)) {
                    foreach ($filters as $filter) {
                        if (!empty($filter) && property_exists($filter, "key") && property_exists($filter, "value")) {
                            if (StringUtility::contains($filter->key, "piva") || StringUtility::contains($filter->key, "activity_fk.piva")) {
                                $piva = $filter->value;
                            } elseif (StringUtility::contains($filter->key, "id_activity") || StringUtility::contains($filter->key, "activity") || StringUtility::contains($filter->key, "activity_fk.id")) {
                                $id_activity = $filter->value;
                            }
                        }
                    }
                }
            }
            if (empty($piva) && !empty($id_activity)) {
                $activityBS = new ActivityBS();
                $activityBS->acceptNull = true;
                $activity = $activityBS->unique($id_activity);
                if ($activity) {
                    $piva = $activity['Activity']['piva'];
                }
            }
        }
    }

}
