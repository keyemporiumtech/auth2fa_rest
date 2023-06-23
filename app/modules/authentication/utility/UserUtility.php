<?php
App::uses("Defaults", "Config/system");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
App::uses("ConfirmationcodeRequest", "modules/authentication/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("TranslatorUtility", "modules/cakeutils/utility");
App::uses("MailUser", "modules/communication/classes");
App::uses("MailerBS", "modules/communication/business");
App::uses("MailUtility", "modules/communication/utility");
App::uses("PhoneUser", "modules/communication/classes");
App::uses("ConfirmoperationUtility", "modules/authentication/utility");
App::uses("UserBS", "modules/authentication/business");

class UserUtility {

    static function sendConfirmationcodeToUser($json, $user, $codOperation, $token, $i18n = array(), $confirmoperationRequest = null) {
        $objRequest = DelegateUtility::getObj($json, $confirmoperationRequest); // ConfirmoperationRequest

        if (property_exists($objRequest, "flgemail") && $objRequest->flgemail) {
            $sender = new MailUser();
            $sender->name = Defaults::get("default_sender_name");
            $sender->email = Defaults::get("default_sender_email");
            $destinators = array();
            $destinator = new MailUser();
            $destinator->name = $user['User']['completename'];
            $destinator->email = !empty($objRequest->email) ? $objRequest->email : $user['User']['username'];
            $destinator->id_user = $user['User']['id'];
            array_push($destinators, $destinator);

            $subject = $i18n['mail_subject'];
            $message = $i18n['mail_message'];
            $codEmail = $i18n['mail_code'];

            $mailConfig = null;
            if (!empty($objRequest->mailer)) {
                $mailer = $json ? DelegateUtility::mapEntityByJson(new MailerBS(), json_encode($objRequest->mailer), array(
                    new ObjPropertyEntity("cod", null, ""),
                    new ObjPropertyEntity("name", null, ""),
                    new ObjPropertyEntity("host", null, ""),
                    new ObjPropertyEntity("port", null, ""),
                    new ObjPropertyEntity("username", null, ""),
                    new ObjPropertyEntity("password", null, ""),
                    new ObjPropertyEntity("sendername", null, ""),
                    new ObjPropertyEntity("senderemail", null, ""),
                    new ObjPropertyEntity("crypttype", null, ""),
                ), null, null) : $objRequest->mailer;
                if (!empty($mailer['Mailer']['sendername'])) {
                    $sender->name = $mailer['Mailer']['sendername'];
                }
                if (!empty($mailer['Mailer']['senderemail'])) {
                    $sender->email = $mailer['Mailer']['senderemail'];
                }
                $mailConfig = MailUtility::getMailConfigByMailer($mailer);
            }
            $flgSendCode = ConfirmoperationUtility::sendCodeMail($codOperation, $sender, $destinators, $subject, $message, $codEmail, $token, $mailConfig);
        }
        if (property_exists($objRequest, "flgsms") && $objRequest->flgsms) {
            $sender = new PhoneUser();
            $sender->name = Defaults::get("default_sender_name");
            $sender->phone = Defaults::get("default_sender_phone");
            $destinators = array();
            $destinator = new PhoneUser();
            $destinator->name = $user['User']['completename'];
            $destinator->phone = !empty($objRequest->phone) ? $objRequest->phone : $user['User']['phone'];
            // $destinator->id_user = $user['User']['id'];
            array_push($destinators, $destinator);

            $message = $i18n['phone_message'];
            $codSms = $i18n['phone_code'];

            $phoneConfig = null;

            $flgSendCode = ConfirmoperationUtility::sendCodeSms($codOperation, $sender, $destinators, $message, $codSms, $token, $phoneConfig);
        }
    }

    static function getUsernameById($id_user = null) {
        if (empty($id_user)) {
            return null;
        }
        $userBS = new UserBS();
        $userBS->acceptNull = true;
        $user = $userBS->unique($id_user);

        return !empty($user) ? $user['User']['username'] : null;
    }

    static function getIdUserByUsername($username = null) {
        if (empty($username)) {
            return null;
        }
        $userBS = new UserBS();
        $userBS->acceptNull = true;
        $userBS->addCondition("username", $username);
        $user = $userBS->unique();

        return !empty($user) ? $user['User']['id'] : null;
    }
}