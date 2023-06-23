<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("MailDto", "modules/communication/classes");
App::uses("MailBS", "modules/communication/business");
App::uses("MailreceiverBS", "modules/communication/business");
App::uses("MailattachmentBS", "modules/communication/business");
App::uses("MailcidBS", "modules/communication/business");
App::uses("MailConfig", "modules/communication/classes");
App::uses("MailUser", "modules/communication/classes");
App::uses("MailEmbed", "modules/communication/classes");
App::uses("AttachmentUI", "modules/resources/delegate");
App::uses("AttachmentBS", "modules/resources/business");
App::uses("AttachmentUtility", "modules/resources/utility");
App::uses("SystemUtility", "modules/coreutils/utility");
App::uses("MailUtility", "modules/communication/utility");

class MailUI extends AppGenericUI {

    function __construct() {
        parent::__construct("MailUI");
        $this->localefile = "mail";
        $this->obj = array(
            new ObjPropertyEntity("ipname", null, SystemUtility::getIPClient()),
            new ObjPropertyEntity("subject", null, ""),
            new ObjPropertyEntity("sendername", null, ""),
            new ObjPropertyEntity("senderemail", null, ""),
            new ObjPropertyEntity("message", null, ""),
            new ObjPropertyEntity("flgdeleted", null, ""),
            new ObjPropertyEntity("dtasend", null, date('Y-m-d H:i:s')),
        );
    }

    function get($id = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id)) {
                DelegateUtility::paramsNull($this, "ERROR_MAIL_NOT_FOUND");
                return "";
            }
            $mailBS = new MailBS();
            $mailBS->json = $this->json;
            parent::completeByJsonFkVf($mailBS);
            $this->ok();
            return $mailBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_MAIL_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $mailBS = !empty($bs) ? $bs : new MailBS();
            $mailBS->json = $this->json;
            parent::completeByJsonFkVf($mailBS);
            parent::evalConditions($mailBS, $conditions);
            parent::evalOrders($mailBS, $orders);
            $mails = $mailBS->table($conditions, $orders, $paginate);
            parent::evalPagination($mailBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($mails);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($mailIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $mail = DelegateUtility::getEntityToSave(new MailBS(), $mailIn, $this->obj);

            if (!empty($mail)) {

                $mailBS = new MailBS();
                $id_mail = $mailBS->save($mail);
                parent::saveInGroup($mailBS, $id_mail);

                parent::commitTransaction();
                if (!empty($id_mail)) {
                    $this->ok(TranslatorUtility::__translate("INFO_MAIL_SAVE", $this->localefile));
                    return $id_mail;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_MAIL_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_MAIL_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_MAIL_SAVE");
            return 0;
        }
    }

    function edit($id, $mailIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $mail = DelegateUtility::getEntityToEdit(new MailBS(), $mailIn, $this->obj, $id);

            if (!empty($mail)) {
                $mailBS = new MailBS();
                $id_mail = $mailBS->save($mail);
                parent::saveInGroup($mailBS, $id_mail);
                parent::delInGroup($mailBS, $id_mail);

                parent::commitTransaction();
                if (!empty($id_mail)) {
                    $this->ok(TranslatorUtility::__translate("INFO_MAIL_EDIT", $this->localefile));
                    return $id_mail;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_MAIL_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_MAIL_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_MAIL_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $mailBS = new MailBS();
                $mailBS->delete($id);
                parent::delInGroup($mailBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_MAIL_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_MAIL_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_MAIL_DELETE");
            return false;
        }
    }

    // ------------------------ PLUGIN INTERACTION
    function send($senderIn, $subject, $destinatorsIn, $ccIn, $ccnIn, $attachmentsIn, $cidsIn, $html, MailConfig $mailConfig = null, $flgSend = true) {
        $this->LOG_FUNCTION = "send";
        $savedFiles = array();
        try {
            parent::startTransaction();

            $objSender = DelegateUtility::getObj($this->json, $senderIn); // MailUser
            $listDestinators = DelegateUtility::getObjList($this->json, $destinatorsIn); // MailUser
            $listCc = DelegateUtility::getObjList($this->json, $ccIn); // MailUser
            $listCcn = DelegateUtility::getObjList($this->json, $ccnIn); // MailUser

            // manage required parameters
            if (empty($objSender)) {
                DelegateUtility::errorInternal($this, "PARAM_NULL", "ERROR_MAIL_SEND", null, "ERROR_MAIL_SEND_NOT_SENDER");
                return false;
            }

            if (ArrayUtility::isEmpty($listDestinators)) {
                DelegateUtility::errorInternal($this, "PARAM_NULL", "ERROR_MAIL_SEND", null, "ERROR_MAIL_SEND_NOT_DESTINATORS");
                return false;
            }

            if (empty($subject)) {
                DelegateUtility::errorInternal($this, "PARAM_NULL", "ERROR_MAIL_SEND", null, "ERROR_MAIL_SEND_NOT_SUBJECT");
                return false;
            }

            if (empty($html)) {
                DelegateUtility::errorInternal($this, "PARAM_NULL", "ERROR_MAIL_SEND", null, "ERROR_MAIL_SEND_NOT_BODY");
                return false;
            }

            // sender
            $sender = new MailUser();
            $sender->email = $objSender->email;
            $sender->name = $objSender->name;

            // destinators
            $destinators = array();
            $destinator = null;
            foreach ($listDestinators as $objDestinator) {
                $destinator = new MailUser();
                $destinator->email = $objDestinator->email;
                $destinator->name = $objDestinator->name;
                array_push($destinators, $destinator);
            }

            // cc
            $cc = array();
            if (!ArrayUtility::isEmpty($listCc)) {
                $destinator = null;
                foreach ($listCc as $objDestinator) {
                    $destinator = new MailUser();
                    $destinator->email = $objDestinator->email;
                    $destinator->name = $objDestinator->name;
                    array_push($cc, $destinator);
                }
            }

            // ccn
            $ccn = array();
            if (!ArrayUtility::isEmpty($listCcn)) {
                $destinator = null;
                foreach ($listCcn as $objDestinator) {
                    $destinator = new MailUser();
                    $destinator->email = $objDestinator->email;
                    $destinator->name = $objDestinator->name;
                    array_push($ccn, $destinator);
                }
            }

            // attachments
            $attachments = array();
            $attachmentsEntity = array();
            if (!ArrayUtility::isEmpty($attachmentsIn)) {
                $ui = new AttachmentUI();
                $entities = DelegateUtility::mapEntityListByJson(new AttachmentBS(), $attachmentsIn, $ui->obj);
                foreach ($entities as $entity) {
                    $this->manageStoreAttachmentIn($entity);
                    array_push($attachmentsEntity, $entity);
                    array_push($attachments, FileUtility::getWebrootFile($entity['Attachment']['path']));
                }
                $savedFiles = $attachments;
            }

            // cids
            $cids = array();
            $cidsEntity = array();
            if (!ArrayUtility::isEmpty($cidsIn)) {
                $ui = new AttachmentUI();
                $entities = DelegateUtility::mapEntityListByJson(new AttachmentBS(), $attachmentsIn, $ui->obj);
                $cid = null;
                foreach ($entities as $entity) {
                    $this->manageStoreAttachmentIn($entity, true);
                    array_push($cidsEntity, $entity);
                    $cid = new MailEmbed(FileUtility::getWebrootFile($entity['Attachment']['path']), $entity['Attachment']['cid'], $entity['Attachment']['mimetype']);
                    array_push($cids, $cid);
                }
            }

            // SEND EMAIL
            $flg_send = true;
            if (Enables::get("phpmailer") && $flgSend) {
                $flg_send = MailUtility::sendPHPMailer($sender, $subject, $destinators, $cc, $ccn, $attachments, $cids, $html, $mailConfig);
                if (!$flg_send) {
                    DelegateUtility::errorInternal($this, "PLUGIN_ERROR", "ERROR_MAIL_SEND", null, "ERROR_PHPMAIL_NOT_SENDED");
                    return false;
                }
            }

            // SAVE EMAIL
            $mailBS = new MailBS();
            $mail = $mailBS->instance();
            $mail['Mail']['ipname'] = SystemUtility::getIPClient();
            $mail['Mail']['subject'] = $subject;
            $mail['Mail']['sendername'] = $sender->name;
            $mail['Mail']['senderemail'] = $sender->email;
            $mail['Mail']['message'] = $html;
            $mail['Mail']['dtasend'] = date('Y-m-d H:i:s');
            $id_mail = $mailBS->save($mail);

            foreach ($destinators as $destinator) {
                $receiverBS = new MailreceiverBS();
                $receiver = $receiverBS->instance();
                $receiver['Mailreceiver']['mail'] = $id_mail;
                $receiver['Mailreceiver']['receivername'] = $destinator->name;
                $receiver['Mailreceiver']['receiveremail'] = $destinator->email;
                $receiver['Mailreceiver']['dtareceive'] = date('Y-m-d H:i:s');
                $id_receive = $receiverBS->save($receiver);
            }

            if (!ArrayUtility::isEmpty($cc)) {
                foreach ($cc as $destinator) {
                    $receiverBS = new MailreceiverBS();
                    $receiver = $receiverBS->instance();
                    $receiver['Mailreceiver']['mail'] = $id_mail;
                    $receiver['Mailreceiver']['receivername'] = $destinator->name;
                    $receiver['Mailreceiver']['receiveremail'] = $destinator->email;
                    $receiver['Mailreceiver']['dtareceive'] = date('Y-m-d H:i:s');
                    $receiver['Mailreceiver']['flgcc'] = 1;
                    $id_receive = $receiverBS->save($receiver);
                }
            }

            if (!ArrayUtility::isEmpty($ccn)) {
                foreach ($ccn as $destinator) {
                    $receiverBS = new MailreceiverBS();
                    $receiver = $receiverBS->instance();
                    $receiver['Mailreceiver']['mail'] = $id_mail;
                    $receiver['Mailreceiver']['receivername'] = $destinator->name;
                    $receiver['Mailreceiver']['receiveremail'] = $destinator->email;
                    $receiver['Mailreceiver']['dtareceive'] = date('Y-m-d H:i:s');
                    $receiver['Mailreceiver']['flgccn'] = 1;
                    $id_receive = $receiverBS->save($receiver);
                }
            }

            if (!ArrayUtility::isEmpty($attachmentsEntity)) {
                foreach ($attachmentsEntity as $attachment) {
                    $attachmentBS = new AttachmentBS();
                    $id_attachment = $attachmentBS->save($attachment);

                    $mailattachmentBS = new MailattachmentBS();
                    $mailattachment = $mailattachmentBS->instance();
                    $mailattachment['Mailattachment']['mail'] = $id_mail;
                    $mailattachment['Mailattachment']['attachment'] = $id_attachment;
                    $id_mailattachment = $mailattachmentBS->save($mailattachment);
                }
            }

            if (!ArrayUtility::isEmpty($cidsEntity)) {
                foreach ($cidsEntity as $attachment) {
                    $attachmentBS = new AttachmentBS();
                    $id_attachment = $attachmentBS->save($attachment);

                    $mailcidBS = new MailcidBS();
                    $mailcid = $mailcidBS->instance();
                    $mailcid['Mailcid']['mail'] = $id_mail;
                    $mailcid['Mailcid']['attachment'] = $id_attachment;
                    $mailcid['Mailcid']['cid'] = $attachment['Attachment']['cid'];
                    $id_mailcid = $mailcidBS->save($mailcid);
                }
            }

            parent::commitTransaction();

            $this->ok(TranslatorUtility::__translate("INFO_MAIL_SEND", $this->localefile));
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            if (!ArrayUtility::isEmpty($savedFiles)) {
                FileUtility::deleteList($savedFiles);
            }
            DelegateUtility::eccezione($e, $this, "ERROR_MAIL_SEND");
            return false;
        }
    }

    function getRead($id = null) {
        $this->LOG_FUNCTION = "getRead";
        try {
            if (empty($id)) {
                DelegateUtility::paramsNull($this, "ERROR_MAIL_NOT_FOUND");
                return "";
            }
            $dto = $this->getMailDto($id);

            $this->ok();
            return $this->json ? json_encode($dto) : $dto;
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_MAIL_NOT_FOUND");
            return "";
        }
    }

    function tableRead($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "tableRead";
        try {
            $mailBS = !empty($bs) ? $bs : new MailBS();
            parent::completeByJsonFkVf($mailBS);
            parent::evalConditions($mailBS, $conditions);
            parent::evalOrders($mailBS, $orders);
            $mails = $mailBS->table($conditions, $orders, $paginate);
            parent::evalPagination($mailBS, $paginate);

            $dtos = array();
            foreach ($mails as $mail) {
                array_push($dtos, $this->getMailDto($mail['Mail']['id']));
            }
            $this->ok();
            return parent::paginateForResponse(json_encode($dtos));
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    // -------------------------- INNER UTILITY
    private function manageStoreAttachmentIn(&$attachment, $flgcid = false) {
        if (empty($attachment['Attachment']['name'])) {
            throw new Exception("ATTACHMENT NAME IS NULL");
        }
        if ($flgcid && empty($attachment['Attachment']['cid'])) {
            throw new Exception("ATTACHMENT CID IS NULL");
        }
        if (empty($attachment['Attachment']['path']) && !empty($attachment['Attachment']['content'])) {
            AttachmentUtility::storeContent($attachment['Attachment']['content'], $attachment['Attachment']['name'], "mail");
            $attachment['Attachment']['path'] = "uploads/mail/" . $attachment['Attachment']['name'];
            $objPath = AttachmentUtility::getObjByPath(WWW_ROOT . $attachment['Attachment']['path'], true);
            AttachmentUtility::replaceAttachmentUtils($attachment, $objPath);
        } elseif (empty($attachment['Attachment']['path']) && !empty($attachment['Attachment']['url'])) {
            $content = FileUtility::getBaseContentByPath($attachment['Attachment']['url']);
            AttachmentUtility::storeContent($content, $attachment['Attachment']['name'], "mail");
            $attachment['Attachment']['path'] = "uploads/mail/" . $attachment['Attachment']['name'];
            $objUrl = AttachmentUtility::getObjByUrl($attachment['Attachment']['url'], true);
            AttachmentUtility::replaceAttachmentUtils($attachment, $objUrl);
        } elseif (!empty($attachment['Attachment']['path'])) {
            $objPath = AttachmentUtility::getObjByPath(WWW_ROOT . $attachment['Attachment']['path'], true);
            AttachmentUtility::replaceAttachmentUtils($attachment, $objPath);
        }
    }

    private function getMailDto($id) {
        try {

            $dto = new MailDto();

            $mailBS = new MailBS();
            $mail = $mailBS->unique($id);
            $dto->mail = $mail['Mail'];
            $dto->body = $mail['Mail']['message'];

            $receiverBS = new MailreceiverBS();
            $receiverBS->addCondition("mail", $mail['Mail']['id']);
            $receiverBS->addCondition("flgcc", 0);
            $receiverBS->addCondition("flgccn", 0);
            $receivers = $receiverBS->all();
            if (!ArrayUtility::isEmpty($receivers)) {
                $dto->destinators = array();
                foreach ($receivers as $receiver) {
                    array_push($dto->destinators, $receiver['Mailreceiver']);
                }
            }

            $receiverBS = new MailreceiverBS();
            $receiverBS->addCondition("mail", $mail['Mail']['id']);
            $receiverBS->addCondition("flgcc", 1);
            $receiverBS->addCondition("flgccn", 0);
            $receivers = $receiverBS->all();
            if (!ArrayUtility::isEmpty($receivers)) {
                $dto->cc = array();
                foreach ($receivers as $receiver) {
                    array_push($dto->cc, $receiver['Mailreceiver']);
                }
            }

            $receiverBS = new MailreceiverBS();
            $receiverBS->addCondition("mail", $mail['Mail']['id']);
            $receiverBS->addCondition("flgcc", 0);
            $receiverBS->addCondition("flgccn", 1);
            $receivers = $receiverBS->all();
            if (!ArrayUtility::isEmpty($receivers)) {
                $dto->ccn = array();
                foreach ($receivers as $receiver) {
                    array_push($dto->ccn, $receiver['Mailreceiver']);
                }
            }

            $mailattachmentBS = new MailattachmentBS();
            $mailattachmentBS->addCondition("mail", $mail['Mail']['id']);
            $mailattachmentBS->addBelongsTo("attachment_fk", $mailattachmentBS->dao);
            $attachments = $mailattachmentBS->all();
            if (!ArrayUtility::isEmpty($attachments)) {
                $dto->attachments = array();
                foreach ($attachments as $attachment) {
                    array_push($dto->attachments, $attachment['Mailattachment']['attachment_fk']);
                }
            }

            $mailcidBS = new MailcidBS();
            $mailcidBS->addCondition("mail", $mail['Mail']['id']);
            $mailcidBS->addBelongsTo("attachment_fk", $mailcidBS->dao);
            $cids = $mailcidBS->all();
            if (!ArrayUtility::isEmpty($cids)) {
                $dto->cids = array();
                foreach ($cids as $attachment) {
                    array_push($dto->cids, $attachment['Mailcid']['attachment_fk']);
                }
            }

            if (!empty($dto->body)) {
                $html = $dto->body;
                if (!ArrayUtility::isEmpty($cids)) {
                    foreach ($cids as $cid) {
                        $attachment = $cid['Mailcid']['attachment_fk'];
                        $html = str_replace("cid:" . $cid['Mailcid']['cid'], FileUtility::getWebrootFile($attachment['path']), $html);
                    }
                }
                $dto->html = $html;
            }
            return $dto;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
