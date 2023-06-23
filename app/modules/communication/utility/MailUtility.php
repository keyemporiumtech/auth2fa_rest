<?php
App::uses("Defaults", "Config/system");
App::uses("MailConfig", "modules/communication/classes");
App::uses("MailUser", "modules/communication/classes");
App::uses("MailEmbed", "modules/communication/classes");
App::uses("ArrayUtility", "modules/coreutils/utility");
App::uses("CryptingUtility", "modules/crypting/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("EnumTypeCrypt", "modules/crypting/config");
class MailUtility {

    // ------------------------ PLUGIN_INTERACTION
    static function sendPHPMailer(MailUser $sender, $subject, $destinators = array(), $cc = array(), $ccn = array(), $attachments = array(), $cids = array(), $html, MailConfig $mailConfig = null) {
        require_once ROOT . '/app/modules/communication/plugin/PHPMailer/class.phpmailer.php';
        require_once ROOT . '/app/modules/communication/plugin/PHPMailer/class.smtp.php';
        $config = !empty($mailConfig) ? $mailConfig : MailUtility::getMailConfig();

        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->CharSet = 'UTF-8';
        $mail->SMTPDebug = 0;
        $mail->SMTPAuth = 'true';
        if ($config->port == 587) {
            $mail->SMTPSecure = 'tls';
        } else {
            $mail->SMTPSecure = 'ssl';
        }
        $mail->Host = $config->host;
        $mail->Port = $config->port;
        $mail->Username = $config->user;
        $mail->Password = $config->password;
        $mail->SetFrom($sender->email, $sender->name);
        $mail->Subject = $subject;
        $mail->Body = $html;

        $logMessage = "[CONFIG] - host:" . $config->host . " port:" . $config->port . " username:" . $config->user . " password:" . $config->passwordCrypted . " protocol:" . $mail->SMTPSecure . "\n";
        $logMessage .= "[FROM] ( " . $sender->email . " > " . $sender->name . " )\n";

        if ($config->port == 587) {
            $mail->smtpConnect(array(
                "tls" => array(
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                    "allow_self_signed" => true,
                ),
            ));
        } else {
            $mail->smtpConnect(array(
                "ssl" => array(
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                    "allow_self_signed" => true,
                ),
            ));
        }

        if (!ArrayUtility::isEmpty($cids)) {
            // MailEmbed
            foreach ($cids as $cid) {
                $mail->addEmbeddedImage($cid->path, $cid->cid, $cid->name, $cid->encoding, $cid->mimetype);
            }
        }

        if (!ArrayUtility::isEmpty($attachments)) {
            // string
            foreach ($attachments as $url) {
                $mail->addAttachment($url);
            }
        }

        $mail->IsHTML(true);

        if (!ArrayUtility::isEmpty($destinators)) {
            $logMessage .= "[TO]";
            // MailUser
            foreach ($destinators as $destinator) {
                $logMessage .= " ( " . $destinator->email . " > " . $destinator->name . " )";
                $mail->AddAddress($destinator->email, $destinator->name);
            }
            $logMessage .= "\n";
        }

        if (!ArrayUtility::isEmpty($cc)) {
            $logMessage .= "[CC]";
            // MailUser
            foreach ($cc as $destinator) {
                $logMessage .= " ( " . $destinator->email . " > " . $destinator->name . " )";
                $mail->AddCC($destinator->email, $destinator->name);
            }
            $logMessage .= "\n";
        }

        if (!ArrayUtility::isEmpty($ccn)) {
            $logMessage .= "[CCN]";
            // MailUser
            foreach ($ccn as $destinator) {
                $logMessage .= " ( " . $destinator->email . " > " . $destinator->name . " )";
                $mail->AddBCC($destinator->email, $destinator->name);
            }
            $logMessage .= "\n";
        }

        $source = MessageUtility::logSource("MailUtility", "send");
        if ($mail->Send() == false) {
            MessageUtility::logMessage(Codes::get("MAIL_NOT_SENDED"), $logMessage, "log_mail", "mail", $source);
            return false;
        }
        MessageUtility::logMessage(Codes::get("MAIL_SENDED"), $logMessage, "log_mail", "mail", $source);
        return true;
    }

    static function getMailConfig($user = null, $password = null, $password_crypt_type = null) {
        $config = new MailConfig();
        $config->host = Defaults::get("mail_host");
        $config->port = Defaults::get("mail_port");
        $config->user = !empty($user) ? $user : Defaults::get("mail_user");
        if (!empty($password)) {
            $config->password = !empty($password_crypt_type) ? CryptingUtility::decryptByType($password, $password_crypt_type) : $password;
            $config->passwordCrypted = !empty($password_crypt_type) ? $password : CryptingUtility::encryptByType($password, Defaults::get("mail_password_crypt_type"));
        } else {
            // manage deode
            $password = base64_decode(Defaults::get("mail_password"));
            $password_crypt_type = CryptingUtility::decryptByType(base64_decode(Defaults::get("mail_password_crypt_type")), EnumTypeCrypt::SHA256);
            $config->passwordCrypted = CryptingUtility::decryptByType($password, $password_crypt_type);
            $config->password = CryptingUtility::decryptByType($config->passwordCrypted, $password_crypt_type);
        }
        return $config;
    }

    // ------------------------ DB_INTERACTION
    static function getMailConfigByMailer($mailer) {
        $config = new MailConfig();
        $config->host = $mailer['Mailer']['host'];
        $config->port = $mailer['Mailer']['port'];
        $config->user = $mailer['Mailer']['username'];
        $config->password = !empty($mailer['Mailer']['crypttype']) ? CryptingUtility::decryptByType($mailer['Mailer']['password'], $mailer['Mailer']['crypttype']) : $mailer['Mailer']['password'];
        $config->passwordCrypted = !empty($mailer['Mailer']['crypttype']) ? $mailer['Mailer']['password'] : CryptingUtility::encryptByType($mailer['Mailer']['password'], Defaults::get("mail_password_crypt_type"));
        return $config;
    }
}
