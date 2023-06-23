<?php
App::uses('AppController', 'Controller');
App::uses("Defaults", "Config/system");
App::uses("MailUI", "modules/communication/delegate");
App::uses("FileUtility", "modules/coreutils/utility");
App::uses("MailUser", "modules/communication/classes");

class CommunicationController extends AppController {

    public function home() {
    }

    public function readMail($id = null) {
        parent::evalParam($id, "id");
        $ui = new MailUI();
        $mailDto = $ui->getRead($id);

        $this->set("mailDto", $mailDto);
    }

    public function sendMail($subject = null, $fromName = null, $fromEmail = null, $message = null) {
        parent::evalParam($subject, "subject");
        parent::evalParam($fromName, "fromName");
        parent::evalParam($fromEmail, "fromEmail");
        parent::evalParam($message, "message");
        $sender = new MailUser();
        $sender->name = $fromName;
        $sender->email = $fromEmail;
        $receiver = new MailUser();
        $receiver->name = Defaults::get("default_sender_name");
        $receiver->email = Defaults::get("default_sender_email");

        $ui = new MailUI();
        $ui->send($sender, $subject, array($receiver), null, null, null, null, $message);

        $this->set("status", $ui->status);
    }
}
