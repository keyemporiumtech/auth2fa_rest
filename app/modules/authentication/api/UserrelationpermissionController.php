<?php
App::uses("AppController", "Controller");
App::uses("UserrelationpermissionUI", "modules/authentication/delegate");
App::uses("AppclientUtility", "modules/authentication/utility");

class UserrelationpermissionController extends AppController {

    public function beforeFilter() {
        $this->json = true;
        $this->delegate = new UserrelationpermissionUI();
        $this->delegate->json = $this->json;
        parent::beforeFilter();
        AppclientUtility::checkTokenClient($this);
    }

    /**
     * @SWG\Get(
     *     path="/userrelationpermission/get",
     *     summary="Legge un permesso relazione utente",
     *     description="Ritorna un permesso relazione utente per uno specifico id o per codice",
     *     operationId="userrelationpermission-get",
     *     tags={"authentication"},
     *     consumes={"application/x-www-form-urlencoded","application/form-data","application/raw","application/binary"},
     *     produces={"application/json; charset=UTF-8"},
     *       @SWG\Parameter(
     *         description="id del permesso relazione utente",
     *         in="query",
     *         name="id_userrelationpermission",
     *         required=false,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="codice del permesso relazione utente",
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
     *         @SWG\Schema(ref="#/components/schemas/Userrelationpermission")
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
    public function get($id_userrelationpermission = null, $cod = null, $belongs = null, $virtualfields = null, $flags = null, $properties = null, $groups = null, $likegroups = null) {
        parent::evalParam($id_userrelationpermission, 'id_userrelationpermission');
        parent::evalParam($cod, 'cod');
        parent::completeFkVf($this->delegate, $belongs, $virtualfields, $flags, $properties, $groups, $likegroups);
        $this->set('data', $this->delegate->get($id_userrelationpermission, $cod));
        $this->responseMessageStatus($this->delegate->status);
    }

    /**
     * @SWG\Get(
     *     path="/userrelationpermission/table",
     *     summary="Legge una lista paginata di permesso relazione utenti",
     *     description="Ritorna una lista di permesso relazione utenti filtrata, ordinata e paginata in base ai parametri di ricerca passati",
     *     operationId="userrelationpermission-table",
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
     *             @SWG\Items(ref="#/components/schemas/Userrelationpermission")
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
     *     path="/userrelationpermission/save",
     *     summary="Salva un permesso relazione utente",
     *     description="Salva un permesso relazione utente ne ritorna l'id",
     *     operationId="userrelationpermission-save",
     *     tags={"authentication"},
     *     consumes={"application/x-www-form-urlencoded","application/form-data","application/raw","application/binary"},
     *     produces={"application/json; charset=UTF-8"},
     *       @SWG\Parameter(
     *         description="oggetto permesso relazione utente",
     *         in="body",
     *         name="userrelationpermission",
     *         required=true,
     *         @SWG\Schema(ref="#/components/schemas/Userrelationpermission")
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
    public function save($userrelationpermissionJson = null, $groupssave = null) {
        parent::evalParam($userrelationpermissionJson, "userrelationpermission");
        parent::completeFkVf($this->delegate, $groupssave);
        $this->set('value', $this->delegate->save($userrelationpermissionJson));
        $this->responseMessageStatus($this->delegate->status);
    }

    /**
     * @SWG\Put(
     *     path="/userrelationpermission/edit",
     *     summary="Modifica un permesso relazione utente",
     *     description="Modifica un permesso relazione utente ne ritorna l'id",
     *     operationId="userrelationpermission-edit",
     *     tags={"authentication"},
     *     consumes={"application/x-www-form-urlencoded","application/form-data","application/raw","application/binary"},
     *     produces={"application/json; charset=UTF-8"},
     *       @SWG\Parameter(
     *         description="oggetto permesso relazione utente",
     *         in="body",
     *         name="userrelationpermission",
     *         required=true,
     *         @SWG\Schema(ref="#/components/schemas/Userrelationpermission")
     *     ),
     *     @SWG\Parameter(
     *         description="id del permesso relazione utente da modificare",
     *         in="query",
     *         name="id_userrelationpermission",
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
    public function edit($id_userrelationpermission = null, $userrelationpermissionJson = null, $groupssave = null, $groupsdel = null) {
        parent::evalParam($id_userrelationpermission, "id_userrelationpermission");
        parent::evalParam($userrelationpermissionJson, "userrelationpermission");
        parent::completeFkVf($this->delegate, $groupssave, $groupsdel);
        $this->set('value', $this->delegate->edit($id_userrelationpermission, $userrelationpermissionJson));
        $this->responseMessageStatus($this->delegate->status);
    }

    /**
     * @SWG\Delete(
     *     path="/userrelationpermission/delete",
     *     summary="Rimuove un permesso relazione utente",
     *     description="Rimuove un permesso relazione utente dato l'id",
     *     operationId="userrelationpermission-delete",
     *     tags={"authentication"},
     *     consumes={"application/x-www-form-urlencoded","application/form-data","application/raw","application/binary"},
     *     produces={"application/json; charset=UTF-8"},
     *     @SWG\Parameter(
     *         description="id del permesso relazione utente da eliminare",
     *         in="query",
     *         name="id_userrelationpermission",
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
    public function delete($id_userrelationpermission = null) {
        parent::evalParam($id_userrelationpermission, "id_userrelationpermission");
        $this->set('flag', $this->delegate->delete($id_userrelationpermission));
        $this->responseMessageStatus($this->delegate->status);
    }
}
