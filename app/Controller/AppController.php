<?php
App::uses('Controller', 'Controller');
App::uses("Enables", "Config/system");
App::uses("Codes", "Config/system");
App::uses("LogUtility", "modules/coreutils/utility");
App::uses("EnumStatus", "modules/cakeutils/config");
App::uses("PageUtility", "modules/coreutils/utility");
App::uses("MessageStatus", "modules/cakeutils/classes");
App::uses("TranslatorUtility", "modules/cakeutils/utility");
App::uses("CookieUtility", "modules/cakeutils/utility");
App::uses("CakeutilsCookies", "modules/cakeutils/cookies");
App::uses("ControllerUtility", "modules/cakeutils/utility");
App::uses("ApploginUtility", "modules/authentication/utility");

/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link http://cakephp.org CakePHP(tm) Project
 * @package app.Controller
 * @since CakePHP(tm) v 0.2.9
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 *
 * @property  CookieinnerComponent $Cookieinner
 */
class AppController extends Controller {
    public $debug = false;
    public $json = false;
    public $delegate = null;

    public $components = array('Cookieinner');

    public function beforeFilter() {
        // ControllerUtility::checkApiManager($this);
        ControllerUtility::checkUtilityManager($this);
        $this->debug = Enables::isDebug();
        if (!$this->debug && $this->json) {
            $this->layout = "blank";
            $this->response->type('json');
        }
        parent::beforeFilter();

        CookieUtility::create();
        CakeutilsCookies::CAKEPHP($this->Cookieinner);
        CakeutilsCookies::ddc_platform($this->Cookieinner);

        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->response->header('Access-Control-Allow-Methods', '*');
        $this->response->header('Access-Control-Allow-Credentials', 'true');
        $this->response->charset('utf-8');
        $this->response->header('Access-Control-Allow-Headers', '*');
        $this->response->header('Access-Control-Max-Age', '172800');
        $this->response->header('Access-Control-Expose-Headers', '*');
        // CakeSession::id(CakeSession::userAgent());

        if (Enables::get("log_api")) {
            LogUtility::write("controller", "API", PageUtility::getCurrentUrl());
        }
    }

    function sendTokenSession($username) {
        ApploginUtility::sendAuthtoken($username, $this);
    }

    function forceResponse(MessageStatus $status = null, $body = null) {
        if (!empty($status)) {
            $this->responseMessageStatus($status);
        }
        $this->response->body(!empty($body) ? $body : "");
        $this->response->send();
        $this->_stop();
    }

    function responseMessageStatus(MessageStatus $status) {
        $this->response->header("statuscod", $status->getStatusCod());
        $this->response->header("responsecod", !empty($status->getResponseCod()) ? $status->getResponseCod() : $this->response->statusCode());
        $this->response->header("message", mb_convert_encoding($status->getMessage(), "ISO-8859-1", "UTF-8"));
        $this->response->header("messagecod", mb_convert_encoding($status->getCod(), "ISO-8859-1", "UTF-8"));
        $this->response->header("messagetype", mb_convert_encoding($status->getMessageType(), "ISO-8859-1", "UTF-8"));
        $this->response->header("exception", mb_convert_encoding($status->getExceptionMessage(), "ISO-8859-1", "UTF-8"));
        $this->response->header("exceptioncod", mb_convert_encoding($status->getExceptionCod(), "ISO-8859-1", "UTF-8"));
        if ($this->debug && !empty($status)) {
            echo "<div class=\"p-2\">";
            echo "<strong>Exception</strong>:" . $status->getExceptionMessage() . "<br/>";
            echo "<strong>Message</strong>:" . $status->getMessage();
            echo "</div>";
        }
    }

    public function evalParam(&$param, $name, $default = null) {
        $paramRequest = PageUtility::getFieldRequest($name, $this->request);
        if (empty($paramRequest) && $default != null) {
            $param = $default;
        } elseif (!empty($paramRequest)) {
            $param = $paramRequest;
        }
    }

    public function evalParamExcludeNull(&$param, $name, $default = null) {
        $paramRequest = PageUtility::getFieldRequest($name, $this->request);
        if ($paramRequest == null && $default != null) {
            $param = $default;
        } elseif ($paramRequest !== null) {
            $param = $paramRequest;
        }
    }

    public function evalParamBool(&$param, $name, $default = null) {
        $paramRequest = PageUtility::getFieldRequest($name, $this->request, null, true);
        if ($paramRequest == null && !empty($default)) {
            $param = $default;
        } elseif ($paramRequest !== null) {
            $param = (boolean) $paramRequest;
        }
    }

    public function evalParamArray(&$param, $name, $default = null) {
        $paramRequest = PageUtility::getFieldRequest($name, $this->request);
        if (empty($paramRequest) && !empty($default)) {
            $param = $default;
        } elseif (!empty($paramRequest)) {
            $param = $this->json ? json_decode($paramRequest, true) : $paramRequest;
        } else {
            $param = array();
        }
    }

    /*
     * public function evalParamPhone(&$param, $name, $default= null) {
     * try {
     * $paramRequest= PageUtility::getFieldRequest($name, $this->request);
     * $value= strval(SmsUtility::evalNumberWithPlus($paramRequest));
     * } catch ( Exception $e ) {
     * $value= null;
     * }
     * if (empty($value) && $default != null) {
     * $param= $default;
     * } elseif (! empty($value)) {
     * $param= $paramRequest;
     * }
     * }
     */
    public function completeFkVf(&$delegate, $belongs = array(), $virtualfields = array(), $flags = array(), $properties = array(), $groups = array(), $likegroups = null) {
        $this->evalParam($belongs, 'belongs', '');
        $delegate->belongs = $belongs;
        $this->evalParam($virtualfields, 'virtualfields', '');
        $delegate->virtualfields = $virtualfields;
        $this->evalParam($flags, 'flags', '');
        $delegate->flags = $flags;
        $this->evalParam($properties, 'properties', '');
        $delegate->properties = $properties;
        $this->evalParam($groups, 'groups', '');
        $delegate->groups = $groups;
        $this->evalParam($likegroups, 'likegroups', '');
        $delegate->likegroups = $likegroups;
    }

    public function completeFkVfSave(&$delegate, $groupssave = array(), $groupsdel = array()) {
        $this->evalParam($groupssave, 'groupssave', '');
        $delegate->groupssave = $groupssave;
        $this->evalParam($groupsdel, 'groupsdel', '');
        $delegate->groupsdel = $groupsdel;
    }

    public function completeFkVfGroupSave(&$delegate, $groupssave = array(), $groupsdel = array()) {
        $this->evalParam($groupssave, 'groupssave', '');
        $delegate->groupssave = $groupssave;
        $this->evalParam($groupsdel, 'groupsdel', '');
        $delegate->groupsdel = $groupsdel;
    }

    public function completeFkVfGroup(&$delegate, $groups = array(), $likegroups = null) {
        $this->evalParam($groups, 'groups', '');
        $delegate->groups = $groups;
        $this->evalParam($likegroups, 'likegroups', '');
        $delegate->likegroups = $likegroups;
    }

    public function goToPage($action, $controller = null, $parameters = null) {
        return $this->redirect(PageUtility::getLinkCakeForRedirect($action, $controller, $parameters));
    }

    public function goToPageQueryMode($action, $controller = null, $parameters = null) {
        return $this->redirect(PageUtility::getLinkCakeForRedirectQueryMode($action, $controller, $parameters));
    }
}
