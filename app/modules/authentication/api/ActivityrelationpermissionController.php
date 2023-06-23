<?php
App::uses("AppController", "Controller");
App::uses("ActivityrelationpermissionUI", "modules/authentication/delegate");
App::uses("AppclientUtility", "modules/authentication/utility");

class ActivityrelationpermissionController extends AppController {

    public function beforeFilter() {
        $this->json = true;
        $this->delegate = new ActivityrelationpermissionUI();
        $this->delegate->json = $this->json;
        parent::beforeFilter();
        AppclientUtility::checkTokenClient($this);
    }

    /**
     * @SWG\Get(
     *     path="/activityrelationpermission/get",
     *     summary="Legge un permesso relazione utente-azienda",
     *     description="Ritorna un permesso relazione utente-azienda per uno specifico id o per codice",
     *     operationId="activityrelationpermission-get",
     *     tags={"authentication"},
     *     consumes={"application/x-www-form-urlencoded","application/form-data","application/raw","application/binary"},
     *     produces={"application/json; charset=UTF-8"},
     *       @SWG\Parameter(
     *         description="id del permesso relazione utente-azienda",
     *         in="query",
     *         name="id_activityrelationpermission",
     *         required=false,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="codice del permesso relazione utente-azienda",
     *         in="query",
     *         name="cod",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Lista delle foreign keys richieste",
     *         in="body",
     *         name="belongs",
     *         required=false,
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(type="string")
     *         )
     *     ),
     *     @SWG\Parameter(
     *         description="Lista dei virtual fields richiesti",
     *         in="body",
     *         name="virtualfields",
     *         required=false,
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(type="string")
     *         )
     *     ),
     *     @SWG\Parameter(
     *         description="Lista dei flags da abilitare",
     *         in="body",
     *         name="flags",
     *         required=false,
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(type="string")
     *         )
     *     ),
     *     @SWG\Parameter(
     *         description="Lista delle properties da valutare",
     *         in="body",
     *         name="properties",
     *         required=false,
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(type="string")
     *         )
     *     ),
     *     @SWG\Parameter(
     *         description="Lista dei gruppi da includere",
     *         in="body",
     *         name="groups",
     *         required=false,
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(type="string")
     *         )
     *     ),
     *     @SWG\Parameter(
     *         description="Congiunto al parametro groups indica una querylike",
     *         in="body",
     *         name="likegroups",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Token di autorizzazione",
     *         in="query",
     *         name="token",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="[statuscod=0]<br/>successful operation",
     *         @SWG\Schema(ref="#/components/schemas/Activityrelationpermission")
     *     ),
     *     @SWG\Response(
     *         response="default",
     *         description="
     * [statuscod=-1] ERROR
     * [statuscod=1] OK",
     *         @SWG\Schema(type="string")
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Not Found",
     *     )
     * )
     */
    public function get($id_activityrelationpermission = null, $cod = null, $belongs = null, $virtualfields = null, $flags = null, $properties = null, $groups = null, $likegroups = null) {
        parent::evalParam($id_activityrelationpermission, 'id_activityrelationpermission');
        parent::evalParam($cod, 'cod');
        parent::completeFkVf($this->delegate, $belongs, $virtualfields, $flags, $properties, $groups, $likegroups);
        $this->set('data', $this->delegate->get($id_activityrelationpermission, $cod));
        $this->responseMessageStatus($this->delegate->status);
    }

    /**
     * @SWG\Get(
     *     path="/activityrelationpermission/table",
     *     summary="Legge una lista paginata di permesso relazione utente-aziendi",
     *     description="Ritorna una lista di permesso relazione utente-aziendi filtrata, ordinata e paginata in base ai parametri di ricerca passati",
     *     operationId="activityrelationpermission-table",
     *     tags={"authentication"},
     *     consumes={"application/x-www-form-urlencoded","application/form-data","application/raw","application/binary"},
     *     produces={"application/json; charset=UTF-8"},
     *     @SWG\Parameter(
     *         description="Lista delle condizioni di filtro",
     *         in="body",
     *         name="filters",
     *         required=false,
     *         @SWG\Schema(
     *                 type="array",
     *                   @SWG\Items(ref="#/components/schemas/DBCondition")
     *           )
     *     ),
     *     @SWG\Parameter(
     *         description="Lista delle condizioni di ordinamento",
     *         in="body",
     *         name="orders",
     *         required=false,
     *         @SWG\Schema(
     *                 type="array",
     *                   @SWG\Items(ref="#/components/schemas/DBOrder")
     *           )
     *     ),
     *     @SWG\Parameter(
     *         description="Tipo di paginazione",
     *         in="body",
     *         name="paginate",
     *         required=false,
     *         @SWG\Schema(ref="#/components/schemas/DBPaginate")
     *     ),
     *     @SWG\Parameter(
     *         description="Lista delle foreign keys richieste",
     *         in="body",
     *         name="belongs",
     *         required=false,
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(type="string")
     *         )
     *     ),
     *     @SWG\Parameter(
     *         description="Lista dei virtual fields richiesti",
     *         in="body",
     *         name="virtualfields",
     *         required=false,
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(type="string")
     *         )
     *     ),
     *     @SWG\Parameter(
     *         description="Lista dei flags da abilitare",
     *         in="body",
     *         name="flags",
     *         required=false,
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(type="string")
     *         )
     *     ),
     *     @SWG\Parameter(
     *         description="Lista delle properties da valutare",
     *         in="body",
     *         name="properties",
     *         required=false,
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(type="string")
     *         )
     *     ),
     *     @SWG\Parameter(
     *         description="Lista dei gruppi da includere",
     *         in="body",
     *         name="groups",
     *         required=false,
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(type="string")
     *         )
     *     ),
     *     @SWG\Parameter(
     *         description="Congiunto al parametro groups indica una querylike",
     *         in="body",
     *         name="likegroups",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="[statuscod=0]<br/>successful operation",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/components/schemas/Activityrelationpermission")
     *         )
     *     ),
     *     @SWG\Response(
     *         response="default",
     *         description="
     * [statuscod=-1] ERROR
     * [statuscod=1] OK",
     *         @SWG\Schema(type="string")
     *     ),
     *     @SWG\Response(
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
     * @SWG\Post(
     *     path="/activityrelationpermission/save",
     *     summary="Salva un permesso relazione utente-azienda",
     *     description="Salva un permesso relazione utente-azienda ne ritorna l'id",
     *     operationId="activityrelationpermission-save",
     *     tags={"authentication"},
     *     consumes={"application/x-www-form-urlencoded","application/form-data","application/raw","application/binary"},
     *     produces={"application/json; charset=UTF-8"},
     *       @SWG\Parameter(
     *         description="oggetto permesso relazione utente-azienda",
     *         in="body",
     *         name="activityrelationpermission",
     *         required=true,
     *         @SWG\Schema(ref="#/components/schemas/Activityrelationpermission")
     *     ),
     *     @SWG\Parameter(
     *         description="Lista dei gruppi da includere",
     *         in="body",
     *         name="groupssave",
     *         required=false,
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(type="string")
     *         )
     *     ),
     *     @SWG\Parameter(
     *         description="Token di autorizzazione",
     *         in="query",
     *         name="token",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="[statuscod=0]<br/>successful operation",
     *         @SWG\Schema(type="integer")
     *     ),
     *     @SWG\Response(
     *         response="default",
     *         description="
     * [statuscod=-1] ERROR
     * [statuscod=1] OK",
     *         @SWG\Schema(type="string")
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Not Found",
     *     )
     * )
     */
    public function save($activityrelationpermissionJson = null, $groupssave = null) {
        parent::evalParam($activityrelationpermissionJson, "activityrelationpermission");
        parent::completeFkVf($this->delegate, $groupssave);
        $this->set('value', $this->delegate->save($activityrelationpermissionJson));
        $this->responseMessageStatus($this->delegate->status);
    }

    /**
     * @SWG\Put(
     *     path="/activityrelationpermission/edit",
     *     summary="Modifica un permesso relazione utente-azienda",
     *     description="Modifica un permesso relazione utente-azienda ne ritorna l'id",
     *     operationId="activityrelationpermission-edit",
     *     tags={"authentication"},
     *     consumes={"application/x-www-form-urlencoded","application/form-data","application/raw","application/binary"},
     *     produces={"application/json; charset=UTF-8"},
     *       @SWG\Parameter(
     *         description="oggetto permesso relazione utente-azienda",
     *         in="body",
     *         name="activityrelationpermission",
     *         required=true,
     *         @SWG\Schema(ref="#/components/schemas/Activityrelationpermission")
     *     ),
     *     @SWG\Parameter(
     *         description="id del permesso relazione utente-azienda da modificare",
     *         in="query",
     *         name="id_activityrelationpermission",
     *         required=false,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="Lista dei gruppi a cui associare l'entity",
     *         in="body",
     *         name="groupssave",
     *         required=false,
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(type="string")
     *         )
     *     ),
     *     @SWG\Parameter(
     *         description="Lista dei gruppi da cui rimuovere l'entity",
     *         in="body",
     *         name="groupsdel",
     *         required=false,
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(type="string")
     *         )
     *     ),
     *     @SWG\Parameter(
     *         description="Token di autorizzazione",
     *         in="query",
     *         name="token",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="[statuscod=0]<br/>successful operation",
     *         @SWG\Schema(type="integer")
     *     ),
     *     @SWG\Response(
     *         response="default",
     *         description="
     * [statuscod=-1] ERROR
     * [statuscod=1] OK",
     *         @SWG\Schema(type="string")
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Not Found",
     *     )
     * )
     */
    public function edit($id_activityrelationpermission = null, $activityrelationpermissionJson = null, $groupssave = null, $groupsdel = null) {
        parent::evalParam($id_activityrelationpermission, "id_activityrelationpermission");
        parent::evalParam($activityrelationpermissionJson, "activityrelationpermission");
        parent::completeFkVf($this->delegate, $groupssave, $groupsdel);
        $this->set('value', $this->delegate->edit($id_activityrelationpermission, $activityrelationpermissionJson));
        $this->responseMessageStatus($this->delegate->status);
    }

    /**
     * @SWG\Delete(
     *     path="/activityrelationpermission/delete",
     *     summary="Rimuove un permesso relazione utente-azienda",
     *     description="Rimuove un permesso relazione utente-azienda dato l'id",
     *     operationId="activityrelationpermission-delete",
     *     tags={"authentication"},
     *     consumes={"application/x-www-form-urlencoded","application/form-data","application/raw","application/binary"},
     *     produces={"application/json; charset=UTF-8"},
     *     @SWG\Parameter(
     *         description="id del permesso relazione utente-azienda da eliminare",
     *         in="query",
     *         name="id_activityrelationpermission",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="Token di autorizzazione",
     *         in="query",
     *         name="token",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="[statuscod=0]<br/>successful operation",
     *         @SWG\Schema(type="boolean")
     *     ),
     *     @SWG\Response(
     *         response="default",
     *         description="
     * [statuscod=-1] ERROR
     * [statuscod=1] OK",
     *         @SWG\Schema(type="string")
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Not Found",
     *     )
     * )
     */
    public function delete($id_activityrelationpermission = null) {
        parent::evalParam($id_activityrelationpermission, "id_activityrelationpermission");
        $this->set('flag', $this->delegate->delete($id_activityrelationpermission));
        $this->responseMessageStatus($this->delegate->status);
    }
}
