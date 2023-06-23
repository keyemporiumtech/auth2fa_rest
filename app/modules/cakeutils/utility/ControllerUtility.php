<?php
require_once ROOT . "/app/Config/system/cores.php";
App::uses("Enables", "Config/system");
App::uses("Codes", "Config/system");
App::uses("AppGenericUI", "modules/cakeutils/classes");
// App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("ArrayUtility", "modules/coreutils/utility");
App::uses("LogUtility", "modules/coreutils/utility");
App::uses("PageUtility", "modules/coreutils/utility");
App::uses("ObjEntity", "modules/cakeutils/classes");
App::uses("ObjCodMessage", "modules/cakeutils/classes");
App::uses("TranslatorUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("EnumResponseCode", "modules/cakeutils/config");

class ControllerUtility {

    static function checkUtilityManager(AppController $ctrl) {
        $json = Cores::readJson(ROOT . "" . DS . "app" . DS . "Config" . DS . "json" . DS . "utility_manager.json");
        if (!Cores::isEmpty($json)) {
            $classname = $json['classname'];
            $path = $json['path'];
            App::uses($classname, $path);
            $util = new $classname();
            $arr = $util->api;
            if (!ArrayUtility::isEmpty($arr)) {
                $urlRef = null;
                foreach ($arr as $key => $obj) {
                    if ($obj['controller'] == $ctrl->request->params['controller'] && $obj['action'] == $ctrl->request->params['action']) {
                        $urlRef = $key;
                        break;
                    }
                }
                if (!empty($urlRef)) {
                    $util->checkApi($ctrl, $urlRef);
                }
            }
        }
    }
    static function checkApiManager(AppController $ctrl) {
        $flgApiManager = ControllerUtility::isEvalApiManager($ctrl);
        if ($flgApiManager) {
            $json = Cores::readJson(ROOT . "" . DS . "app" . DS . "Config" . DS . "json" . DS . "api_manager.json");
            if (!Cores::isEmpty($json)) {
                $classname = $json['classname'];
                $path = $json['path'];
                $controller = $json['controller'];
                $action = $json['action'];
                App::uses($classname, $path);
                $ui = new $classname();
                $arr = $ui->api;
                if (!ArrayUtility::isEmpty($arr) && !ControllerUtility::isThisUrl($ctrl, $json)) {
                    $urlRef = null;
                    foreach ($arr as $key => $obj) {
                        if ($obj['controller'] == $ctrl->request->params['controller'] && $obj['action'] == $ctrl->request->params['action']) {
                            $urlRef = $key;
                            break;
                        }
                    }
                    if (!empty($urlRef)) {
                        $params = array();
                        $params = array_merge($ctrl->request->data, $ctrl->request->query);
                        $parameters = array();
                        $parameters['params'] = json_encode($params);
                        $parameters['url'] = $urlRef;
                        $ctrl->goToPageQueryMode($action, $controller, $parameters);
                    }
                }
            }
        }
    }

    static function isThisUrl(AppController $ctrl, $json) {
        return $ctrl->request->params['controller'] == $json['controller'] && $ctrl->request->params['action'] == $json['action'];
    }

    static function isEvalApiManager(AppController $ctrl) {
        if (array_key_exists("evalApiManager", $ctrl->request->query) && $ctrl->request->query['evalApiManager'] == 0) {
            return 0;
        }
        if (array_key_exists("evalApiManager", $ctrl->request->data) && $ctrl->request->data['evalApiManager'] == 0) {
            return 0;
        }
        return 1;
    }
}
