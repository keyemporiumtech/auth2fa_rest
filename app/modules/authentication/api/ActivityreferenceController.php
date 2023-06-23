<?php
App::uses("AppController", "Controller");
App::uses("ActivityreferenceUI", "modules/authentication/delegate");
App::uses("AppclientUtility", "modules/authentication/utility");

class ActivityreferenceController extends AppController {

    public function beforeFilter() {
        $this->json = true;
        $this->delegate = new ActivityreferenceUI();
        $this->delegate->json = $this->json;
        parent::beforeFilter();
        AppclientUtility::checkTokenClient($this);
    }

    /**
     * @OA\Get(
     *     path="/activityreference/get",
     *     summary="Legge un contatto",
     *     description="Ritorna un contatto per uno specifico id o per codice",
     *     operationId="activityreference-get",
     *     tags={"authentication"},
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="id del contatto",
     *         in="query",
     *         name="id_activityreference",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         description="codice del contatto",
     *         in="query",
     *         name="cod",
     *         required=false,
     *         @OA\Schema(type="string")
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
     *         @OA\JsonContent(ref="#/components/schemas/Activityreference")
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
    public function get($id_activityreference = null, $cod = null, $belongs = null, $virtualfields = null, $flags = null, $properties = null, $groups = null, $likegroups = null) {
        parent::evalParam($id_activityreference, 'id_activityreference');
        parent::evalParam($cod, 'cod');
        parent::completeFkVf($this->delegate, $belongs, $virtualfields, $flags, $properties, $groups, $likegroups);
        $this->set('data', $this->delegate->get($id_activityreference, $cod));
        $this->responseMessageStatus($this->delegate->status);
    }

    /**
     * @OA\Get(
     *     path="/activityreference/table",
     *     summary="Legge una lista paginata di contatti",
     *     description="Ritorna una lista di contatti filtrata, ordinata e paginata in base ai parametri di ricerca passati",
     *     operationId="activityreference-table",
     *     tags={"authentication"},
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
     *             @OA\Items(ref="#/components/schemas/Activityreference")
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
     *     path="/activityreference/save",
     *     summary="Salva un contatto",
     *     description="Salva un contatto ne ritorna l'id",
     *     operationId="activityreference-save",
     *     tags={"authentication"},
     *     deprecated=false,
     *     @OA\RequestBody(
     *         description="oggetto contatto",
     *         request="activityreference",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Activityreference")
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
    public function save($activityreferenceJson = null, $groupssave = null) {
        parent::evalParam($activityreferenceJson, "activityreference");
        parent::completeFkVfSave($this->delegate, $groupssave);
        $this->set('value', $this->delegate->save($activityreferenceJson));
        $this->responseMessageStatus($this->delegate->status);
    }

    /**
     * @OA\Put(
     *     path="/activityreference/edit",
     *     summary="Modifica un contatto",
     *     description="Modifica un contatto ne ritorna l'id",
     *     operationId="activityreference-edit",
     *     tags={"authentication"},
     *     deprecated=false,
     *     @OA\RequestBody(
     *         description="oggetto contatto",
     *         request="activityreference",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Activityreference")
     *     ),
     *     @OA\Parameter(
     *         description="id del contatto da modificare",
     *         in="query",
     *         name="id_activityreference",
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
    public function edit($id_activityreference = null, $activityreferenceJson = null, $groupssave = null, $groupsdel = null) {
        parent::evalParam($id_activityreference, "id_activityreference");
        parent::evalParam($activityreferenceJson, "activityreference");
        parent::completeFkVfSave($this->delegate, $groupssave, $groupsdel);
        $this->set('value', $this->delegate->edit($id_activityreference, $activityreferenceJson));
        $this->responseMessageStatus($this->delegate->status);
    }

    /**
     * @OA\Delete(
     *     path="/activityreference/delete",
     *     summary="Rimuove un contatto",
     *     description="Rimuove un contatto dato l'id",
     *     operationId="activityreference-delete",
     *     tags={"authentication"},
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="id del contatto da eliminare",
     *         in="query",
     *         name="id_activityreference",
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
    public function delete($id_activityreference = null) {
        parent::evalParam($id_activityreference, "id_activityreference");
        $this->set('flag', $this->delegate->delete($id_activityreference));
        $this->responseMessageStatus($this->delegate->status);
    }

    // ------------- PRINCIPAL
    /**
     * @OA\Get(
     *     path="/activityreference/getPrincipal",
     *     summary="Legge il contatto principale di una azienda",
     *     description="Ritorna il contatto aziendale principale per uno specifico id utente o per piva utente e per tipo di contatto",
     *     operationId="activityreference-getPrincipal",
     *     tags={"authentication"},
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="id dell'azienda",
     *         in="query",
     *         name="id_activity",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         description="codice TVS o partita IVA dell'azienda",
     *         in="query",
     *         name="piva",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         description="tipo di contatto",
     *         in="query",
     *         name="type",
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
     *         @OA\JsonContent(ref="#/components/schemas/Activityreference")
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
    public function getPrincipal($id_activity = null, $piva = null, $type = null, $belongs = null, $virtualfields = null, $flags = null, $properties = null, $groups = null, $likegroups = null) {
        parent::evalParam($id_activity, 'id_activity');
        parent::evalParam($piva, 'piva');
        parent::evalParam($type, 'type');
        parent::completeFkVf($this->delegate, $belongs, $virtualfields, $flags, $properties, $groups, $likegroups);
        $this->set('data', $this->delegate->getPrincipal($id_activity, $piva, $type));
        $this->responseMessageStatus($this->delegate->status);
    }

    /**
     * @OA\Put(
     *     path="/activityreference/setPrincipal",
     *     summary="Imposta il contatto principale di una azienda",
     *     description="Imposta il contatto aziendale principale per uno specifico id utente o per piva utente e per id activityreference o codice e per tipo di contatto",
     *     operationId="activityreference-setPrincipal",
     *     tags={"authentication"},
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="id dell'azienda",
     *         in="query",
     *         name="id_activity",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         description="codice TVS o partita IVA dell'azienda",
     *         in="query",
     *         name="piva",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         description="id della relazione contatto-azienda",
     *         in="query",
     *         name="id_activityreference",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         description="codice del contatto aziendale",
     *         in="query",
     *         name="cod_activityreference",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         description="tipo di contatto",
     *         in="query",
     *         name="type",
     *         required=true,
     *         @OA\Schema(type="integer")
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
     *         @OA\JsonContent(ref="#/components/schemas/Activityreference")
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
    public function setPrincipal($id_activity = null, $piva = null, $id_activityreference = null, $cod_activityreference = null, $type = null, $groups = null, $likegroups = null) {
        parent::evalParam($id_activity, 'id_activity');
        parent::evalParam($piva, 'piva');
        parent::evalParam($id_activityreference, 'id_activityreference');
        parent::evalParam($cod_activityreference, 'cod_activityreference');
        parent::evalParam($type, 'type');
        parent::completeFkVfGroup($this->delegate, $groups, $likegroups);
        $this->set('flag', $this->delegate->setPrincipal($id_activity, $piva, $id_activityreference, $cod_activityreference, $type));
        $this->responseMessageStatus($this->delegate->status);
    }

    // ------------- RELATION
    /**
     * @OA\Post(
     *     path="/activityreference/saveRelation",
     *     summary="Salva un contatto attività",
     *     description="Salva un contatto attività ne ritorna l'id",
     *     operationId="activityreference-saveRelation",
     *     tags={"authentication"},
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="id dell'attività",
     *         in="query",
     *         name="id_activity",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         description="oggetto contatto attività",
     *         request="contactreference",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Contactreference")
     *     ),
     *     @OA\Parameter(
     *         description="tipologia di contatto",
     *         in="query",
     *         name="tpcontactreference",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         description="se true imposta come contatto principale",
     *         in="query",
     *         name="flgprincipal",
     *         required=false,
     *         @OA\Schema(type="boolean")
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
    public function saveRelation($id_activity = null, $contactreference = null, $tpcontactreference = null, $flgprincipal = null, $groupssave = null) {
        parent::evalParam($id_activity, "id_activity");
        parent::evalParam($contactreference, "contactreference");
        parent::evalParam($tpcontactreference, "tpcontactreference");
        parent::evalParamBool($flgprincipal, "flgprincipal", false);
        parent::completeFkVfSave($this->delegate, $groupssave);
        $this->set('value', $this->delegate->saveRelation($id_activity, $contactreference, $tpcontactreference, $flgprincipal));
        $this->responseMessageStatus($this->delegate->status);
    }
}
