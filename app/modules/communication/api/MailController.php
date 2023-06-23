<?php
App::uses("AppController", "Controller");
App::uses("MailUI", "modules/communication/delegate");
App::uses("MailerUI", "modules/communication/delegate");
App::uses("MailerBS", "modules/communication/business");
App::uses("MailUtility", "modules/communication/utility");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("TokenUtility", "modules/cakeutils/utility");

class MailController extends AppController {

    public function beforeFilter() {
        $this->json = true;
        $this->delegate = new MailUI();
        $this->delegate->json = $this->json;
        parent::beforeFilter();
        TokenUtility::checkInnerToken($this);
    }

    /**
     * @OA\Get(
     *     path="/mail/get",
     *     summary="Legge una mail",
     *     description="Ritorna una mail per uno specifico id",
     *     operationId="mail-get",
     *     tags={"communication"},
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="id della mail",
     *         in="query",
     *         name="id_mail",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         description="Lista delle foreign keys richieste",
     *         in="query",
     *         name="belongs",
     *         required=false,
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(type="string")
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Lista dei virtual fields richiesti",
     *         in="query",
     *         name="virtualfields",
     *         required=false,
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(type="string")
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Lista dei flags da abilitare",
     *         in="query",
     *         name="flags",
     *         required=false,
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(type="string")
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Lista delle properties da valutare",
     *         in="query",
     *         name="properties",
     *         required=false,
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(type="string")
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Lista dei gruppi da includere",
     *         in="query",
     *         name="groups",
     *         required=false,
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(type="string")
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Congiunto al parametro groups indica una querylike",
     *         in="query",
     *         name="likegroups",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         description="Token di autorizzazione",
     *         in="header",
     *         name="token",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="[statuscod=0]<br/>successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Mail")
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="[statuscod=-1] ERROR - [statuscod=1] OK",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *     )
     * )
     */
    public function get($id_mail = null, $belongs = null, $virtualfields = null, $flags = null, $properties = null, $groups = null, $likegroups = null) {
        parent::evalParam($id_mail, 'id_mail');
        parent::completeFkVf($this->delegate, $belongs, $virtualfields, $flags, $properties, $groups, $likegroups);
        $this->set('data', $this->delegate->get($id_mail));
        $this->responseMessageStatus($this->delegate->status);
    }

    /**
     * @OA\Get(
     *     path="/mail/table",
     *     summary="Legge una lista paginata di mail",
     *     description="Ritorna una lista di mail filtrata, ordinata e paginata in base ai parametri di ricerca passati",
     *     operationId="mail-table",
     *     tags={"communication"},
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="Lista delle condizioni di filtro",
     *         in="query",
     *         name="filters",
     *         required=false,
     *         @OA\Schema(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/DBCondition")
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Lista delle condizioni di ordinamento",
     *         in="query",
     *         name="orders",
     *         required=false,
     *         @OA\Schema(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/DBOrder")
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Tipo di paginazione",
     *         in="query",
     *         name="paginate",
     *         required=false,
     *         @OA\Schema(ref="#/components/schemas/DBPaginate"),
     *     ),
     *     @OA\Parameter(
     *         description="Lista delle foreign keys richieste",
     *         in="query",
     *         name="belongs",
     *         required=false,
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(type="string")
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Lista dei virtual fields richiesti",
     *         in="query",
     *         name="virtualfields",
     *         required=false,
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(type="string")
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Lista dei flags da abilitare",
     *         in="query",
     *         name="flags",
     *         required=false,
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(type="string")
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Lista delle properties da valutare",
     *         in="query",
     *         name="properties",
     *         required=false,
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(type="string")
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Lista dei gruppi da includere",
     *         in="query",
     *         name="groups",
     *         required=false,
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(type="string")
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Congiunto al parametro groups indica una querylike",
     *         in="query",
     *         name="likegroups",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="[statuscod=0]<br/>successful operation",
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Mail")
     *         )
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="[statuscod=-1] ERROR - [statuscod=1] OK",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *     )
     * )
     */
    public function table($jsonFilters = null, $jsonOrders = null, $jsonPaginate = null, $belongs = null, $virtualfields = null, $flags = null, $properties = null, $groups = null, $likegroups = null) {
        parent::evalParam($jsonFilters, 'filters');
        parent::evalParam($jsonOrders, 'orders');
        parent::evalParam($jsonPaginate, 'paginate');
        parent::completeFkVf($this->delegate, $belongs, $virtualfields, $flags, $properties, $groups, $likegroups);
        $this->set('data', $this->delegate->table($jsonFilters, $jsonOrders, $jsonPaginate));
        $this->responseMessageStatus($this->delegate->status);
    }

    /**
     * @OA\Post(
     *     path="/mail/save",
     *     summary="Salva una mail",
     *     description="Salva una mail ne ritorna l'id",
     *     operationId="mail-save",
     *     tags={"communication"},
     *     deprecated=false,
     *     @OA\RequestBody(
     *         description="oggetto mail",
     *         request="mail",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Mail")
     *     ),
     *     @OA\Parameter(
     *         description="Lista dei gruppi a cui associare l'entity",
     *         in="query",
     *         name="groupssave",
     *         required=false,
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(type="string")
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Token di autorizzazione",
     *         in="header",
     *         name="token",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="[statuscod=0]<br/>successful operation",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="[statuscod=-1] ERROR - [statuscod=1] OK",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *     )
     * )
     */
    public function save($mailJson = null, $groupssave = null) {
        parent::evalParam($mailJson, "mail");
        parent::completeFkVfSave($this->delegate, $groupssave);
        $this->set('value', $this->delegate->save($mailJson));
        $this->responseMessageStatus($this->delegate->status);
    }

    /**
     * @OA\Put(
     *     path="/mail/edit",
     *     summary="Modifica una mail",
     *     description="Modifica una mail ne ritorna l'id",
     *     operationId="mail-edit",
     *     tags={"communication"},
     *     deprecated=false,
     *     @OA\RequestBody(
     *         description="oggetto mail",
     *         request="mail",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Mail")
     *     ),
     *     @OA\Parameter(
     *         description="id client da modificare",
     *         in="query",
     *         name="id_mail",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         description="Lista dei gruppi a cui associare l'entity",
     *         in="query",
     *         name="groupssave",
     *         required=false,
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(type="string")
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Lista dei gruppi da cui rimuovere l'entity",
     *         in="query",
     *         name="groupsdel",
     *         required=false,
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(type="string")
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Token di autorizzazione",
     *         in="header",
     *         name="token",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="[statuscod=0]<br/>successful operation",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="[statuscod=-1] ERROR - [statuscod=1] OK",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *     )
     * )
     */
    public function edit($id_mail = null, $mailJson = null, $groupssave = null, $groupsdel = null) {
        parent::evalParam($id_mail, "id_mail");
        parent::evalParam($mailJson, "mail");
        parent::completeFkVfSave($this->delegate, $groupssave, $groupsdel);
        $this->set('value', $this->delegate->edit($id_mail, $mailJson));
        $this->responseMessageStatus($this->delegate->status);
    }

    /**
     * @OA\Delete(
     *     path="/mail/delete",
     *     summary="Rimuove una mail",
     *     description="Rimuove una mail dato l'id",
     *     operationId="mail-delete",
     *     tags={"communication"},
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="id della mail da eliminare",
     *         in="query",
     *         name="id_mail",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         description="Token di autorizzazione",
     *         in="header",
     *         name="token",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="[statuscod=0]<br/>successful operation",
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="[statuscod=-1] ERROR - [statuscod=1] OK",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *     )
     * )
     */
    public function delete($id_mail = null) {
        parent::evalParam($id_mail, "id_mail");
        $this->set('flag', $this->delegate->delete($id_mail));
        $this->responseMessageStatus($this->delegate->status);
    }

    // ------------------------ PLUGIN INTERACTION
    /**
     * @OA\Post(
     *     path="/mail/send",
     *     summary="Invia una mail",
     *     description="Invia una mail ne ritorna l'esito",
     *     operationId="mail-send",
     *     tags={"communication"},
     *     deprecated=false,
     *     @OA\RequestBody(
     *         description="mittente della mail",
     *         request="sender",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/MailUser")
     *     ),
     *     @OA\Parameter(
     *         description="oggetto della mail",
     *         in="query",
     *         name="subject",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         description="Lista dei destinatari della mail",
     *         in="query",
     *         name="destinators",
     *         required=true,
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/MailUser")
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Lista dei destinatari in copia della mail",
     *         in="query",
     *         name="cc",
     *         required=false,
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/MailUser")
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Lista dei destinatari in copia nascosta della mail",
     *         in="query",
     *         name="ccn",
     *         required=false,
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/MailUser")
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Lista degli allegati alla mail",
     *         in="query",
     *         name="attachments",
     *         required=false,
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Attachment")
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Lista dei cid allegati al body o messaggio della mail",
     *         in="query",
     *         name="cids",
     *         required=false,
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Attachment")
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="body o messaggio della mail - formato anche html",
     *         in="query",
     *         name="message",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         description="provider di configurazione mittente",
     *         request="mailer",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Mailer")
     *     ),
     *     @OA\Parameter(
     *         description="Token di autorizzazione",
     *         in="header",
     *         name="token",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="[statuscod=0]<br/>successful operation",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="[statuscod=-1] ERROR - [statuscod=1] OK",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *     )
     * )
     */
    public function send($sender = null, $subject = null, $destinators = null, $cc = null, $ccn = null, $attachments = null, $cids = null, $body = null, $mailer = null) {
        parent::evalParam($sender, "sender");
        parent::evalParam($subject, "subject");
        parent::evalParam($destinators, "destinators");
        parent::evalParam($cc, "cc");
        parent::evalParam($ccn, "ccn");
        parent::evalParam($attachments, "attachments");
        parent::evalParam($cids, "cids");
        parent::evalParam($body, "message");
        parent::evalParam($mailer, "mailer");
        $mailConfig = null;
        if (!empty($mailer)) {
            $mailerEntity = DelegateUtility::mapEntityJsonByDelegate(new MailerUI(), new MailerBS(), $mailer);
            if (!empty($mailerEntity) && array_key_exists("Mailer", $mailerEntity)) {
                $mailConfig = MailUtility::getMailConfigByMailer($mailerEntity);
            }
        }
        $this->set('flag', $this->delegate->send($sender, $subject, $destinators, $cc, $ccn, $attachments, $cids, $body, $mailConfig));
        $this->responseMessageStatus($this->delegate->status);
    }

    /**
     * @OA\Get(
     *     path="/mail/getRead",
     *     summary="Legge una mail con i dettagli",
     *     description="Ritorna una mail per uno specifico id e i tutti gli elementi associati alla mail (allegati, cid, destinatari)",
     *     operationId="mail-getRead",
     *     tags={"communication"},
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="id della mail",
     *         in="query",
     *         name="id_mail",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         description="Token di autorizzazione",
     *         in="header",
     *         name="token",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="[statuscod=0]<br/>successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/MailDto")
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="[statuscod=-1] ERROR - [statuscod=1] OK",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *     )
     * )
     */
    public function getRead($id_mail = null) {
        parent::evalParam($id_mail, 'id_mail');
        $this->set('data', $this->delegate->getRead($id_mail));
        $this->responseMessageStatus($this->delegate->status);
    }

    /**
     * @OA\Get(
     *     path="/mail/tableRead",
     *     summary="Legge una lista paginata di mail con dettaglio",
     *     description="Ritorna una lista di mail dettagliate (con allegati,cid e destinatari) filtrata, ordinata e paginata in base ai parametri di ricerca passati",
     *     operationId="mail-tableRead",
     *     tags={"communication"},
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="Lista delle condizioni di filtro",
     *         in="query",
     *         name="filters",
     *         required=false,
     *         @OA\Schema(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/DBCondition")
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Lista delle condizioni di ordinamento",
     *         in="query",
     *         name="orders",
     *         required=false,
     *         @OA\Schema(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/DBOrder")
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Tipo di paginazione",
     *         in="query",
     *         name="paginate",
     *         required=false,
     *         @OA\Schema(ref="#/components/schemas/DBPaginate"),
     *     ),
     *     @OA\Parameter(
     *         description="Lista delle foreign keys richieste",
     *         in="query",
     *         name="belongs",
     *         required=false,
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(type="string")
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Lista dei virtual fields richiesti",
     *         in="query",
     *         name="virtualfields",
     *         required=false,
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(type="string")
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Lista dei flags da abilitare",
     *         in="query",
     *         name="flags",
     *         required=false,
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(type="string")
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Lista delle properties da valutare",
     *         in="query",
     *         name="properties",
     *         required=false,
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(type="string")
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Lista dei gruppi da includere",
     *         in="query",
     *         name="groups",
     *         required=false,
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(type="string")
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Congiunto al parametro groups indica una querylike",
     *         in="query",
     *         name="likegroups",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="[statuscod=0]<br/>successful operation",
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/MailDto")
     *         )
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="[statuscod=-1] ERROR - [statuscod=1] OK",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *     )
     * )
     */
    public function tableRead($jsonFilters = null, $jsonOrders = null, $jsonPaginate = null, $belongs = null, $virtualfields = null, $flags = null, $properties = null, $groups = null, $likegroups = null) {
        parent::evalParam($jsonFilters, 'filters');
        parent::evalParam($jsonOrders, 'orders');
        parent::evalParam($jsonPaginate, 'paginate');
        parent::completeFkVf($this->delegate, $belongs, $virtualfields, $flags, $properties, $groups, $likegroups);
        $this->set('data', $this->delegate->tableRead($jsonFilters, $jsonOrders, $jsonPaginate));
        $this->responseMessageStatus($this->delegate->status);
    }
}