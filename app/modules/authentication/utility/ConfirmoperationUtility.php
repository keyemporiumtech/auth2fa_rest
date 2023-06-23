<?php
App::uses("Defaults", "Config/system");
App::uses("Codes", "Config/system");
App::uses("MailUser", "modules/communication/classes");
App::uses("MailUtility", "modules/communication/utility");
App::uses("MailUI", "modules/communication/delegate");
App::uses("FileUtility", "modules/coreutils/utility");
App::uses("ApploginUtility", "modules/authentication/utility");
App::uses("ConfirmoperationBS", "modules/authentication/business");
App::uses("ConfirmoperationUI", "modules/authentication/delegate");
App::uses("PhoneUser", "modules/communication/classes");
App::uses("PhoneUI", "modules/communication/delegate");

class ConfirmoperationUtility {

    static function sendCodeMail($codOperation, MailUser $sender, $destinators = array(), $subject, $message, $pin = null, $token = null, $mailConfig = null, $flgSend = true) {
        $mailUI = new MailUI();
        $mailUI->transactional = true;
        $flgSendMail = $mailUI->send($sender, $subject, $destinators, null, null, null, null, $message, $mailConfig, $flgSend);
        if ($flgSendMail) {
            if (empty($pin)) {
                $pin = FileUtility::uuid_number();
            }
            if (empty($token)) {
                $payload = array(
                    "name" => $sender->name,
                    "email" => $sender->email,
                    "subject" => $subject,
                    "message" => $message,
                    "session" => CakeSession::id(),
                );
                $token = ApploginUtility::buildToken($sender->email, json_encode($payload));
            }
            foreach ($destinators as $destinator) {
                $confirmoperationBS = new ConfirmoperationBS();
                $confirmoperation = $confirmoperationBS->instance();
                $confirmoperation['Confirmoperation']['codoperation'] = $codOperation;
                $confirmoperation['Confirmoperation']['description'] = $message;
                $confirmoperation['Confirmoperation']['email'] = $destinator->email;
                $confirmoperation['Confirmoperation']['codemail'] = $pin;
                $confirmoperation['Confirmoperation']['user'] = $destinator->id_user;
                $confirmoperation['Confirmoperation']['token'] = $token;
                $id_confirmoperationMail = $confirmoperationBS->save($confirmoperation);
            }
            return true;
        }
        return false;
    }

    static function sendCodeSms($codOperation, PhoneUser $sender, $destinators = array(), $message, $pin = null, $token = null, $mailConfig = null, $flgSend = true) {
        $phoneUI = new PhoneUI();
        $phoneUI->transactional = true;
        $flgSendPhone = $phoneUI->send($sender, $destinators, $message);
        if ($flgSendPhone) {
            if (empty($pin)) {
                $pin = FileUtility::uuid_number();
            }
            if (empty($token)) {
                $payload = array(
                    "name" => $sender->name,
                    "phone" => $sender->phone,
                    "message" => $message,
                    "session" => CakeSession::id(),
                );
                $token = ApploginUtility::buildToken($sender->phone, json_encode($payload));
            }
            foreach ($destinators as $destinator) {
                $confirmoperationBS = new ConfirmoperationBS();
                $confirmoperation = $confirmoperationBS->instance();
                $confirmoperation['Confirmoperation']['codoperation'] = $codOperation;
                $confirmoperation['Confirmoperation']['description'] = $message;
                $confirmoperation['Confirmoperation']['phone'] = $destinator->phone;
                $confirmoperation['Confirmoperation']['codsms'] = $pin;
                $confirmoperation['Confirmoperation']['user'] = $destinator->id_user;
                $confirmoperation['Confirmoperation']['token'] = $token;
                $id_confirmoperationPhone = $confirmoperationBS->save($confirmoperation);
            }
            return true;
        }
        return false;
    }

    static function verifyCode($codOperation, $pin, $key) {
        $confirmoperationUI = new ConfirmoperationUI();
        $confirmoperationUI->transactional = true;
        $authtoken = $confirmoperationUI->getOperationToken($codOperation);
        if (empty($authtoken)) {
            throw new Exception("token for pin not found", Codes::get("CONTROL_CODE_NOT_FOUND"));
        } elseif (!ApploginUtility::verifyToken($authtoken)) {
            // chiudo tutte le operazioni ancora aperte
            $confirmoperationUI = new ConfirmoperationUI();
            $confirmoperationUI->transactional = true;
            $flgClose = $confirmoperationUI->closeAll($codOperation);
            if (!$flgClose) {
                throw new Exception("operation for pin not closed", Codes::get("EXCEPTION_GENERIC"));
            }
            throw new Exception("token for pin not valid", Codes::get("CONTROL_CODE_INVALID"));
        } elseif (!ApploginUtility::verifySession($authtoken)) {
            // chiudo tutte le operazioni ancora aperte
            $confirmoperationUI = new ConfirmoperationUI();
            $confirmoperationUI->transactional = true;
            $flgClose = $confirmoperationUI->closeAll($codOperation);
            if (!$flgClose) {
                throw new Exception("operation for pin not closed", Codes::get("EXCEPTION_GENERIC"));
            }
            throw new Exception("token for pin expired", Codes::get("CONTROL_CODE_EXPIRED"));
        } else {
            $tokenDecode = ApploginUtility::decodeTokenLogin($authtoken);
            if (!$tokenDecode['auth']) {
                throw new Exception("token for pin not valid", Codes::get("CONTROL_CODE_INVALID"));
            }
            if (empty($tokenDecode['payload'] || $tokenDecode['payload']['aud'] !== $key)) {
                throw new Exception("pin is not mine", Codes::get("THREAT_INTRUSION"));
            }
        }

        $flgManage = $confirmoperationUI->checkAndManageOperationByCod($codOperation, $pin);
        if (!$flgManage) {
            throw new Exception("pin not managed", Codes::get("CONTROL_CODE_NOT_MANAGED"));
        }
        return true;
    }
}