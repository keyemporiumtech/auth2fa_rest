<?php
App::uses("AppController", "Controller");
App::uses("ActivityprofileUI", "modules/authentication/delegate");
App::uses("AppclientUtility", "modules/authentication/utility");

class ActivityprofileController extends AppController {

    public function beforeFilter() {
        $this->json = true;
        $this->delegate = new ActivityprofileUI();
        $this->delegate->json = $this->json;
        parent::beforeFilter();
        AppclientUtility::checkTokenClient($this);
    }

    /**
     * @OA\Get(
     *     path="/activityprofile/get",
     *     summary="Legge un profilo aziendale",
     *     description="Ritorna un profilo aziendale per uno specifico id o per codice",
     *     operationId="activityprofile-get",
     *     tags={"authentication"},
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="id del profilo aziendale",
     *         in="query",
     *         name="id_activityprofile",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         description="codice del profilo aziendale",
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
     *         @OA\JsonContent(ref="#/components/schemas/Activityprofile")
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
    public function get($id_activityprofile = null, $cod = null, $belongs = null, $virtualfields = null, $flags = null, $properties = null, $groups = null, $likegroups = null) {
        parent::evalParam($id_activityprofile, 'id_activityprofile');
        parent::evalParam($cod, 'cod');
        parent::completeFkVf($this->delegate, $belongs, $virtualfields, $flags, $properties, $groups, $likegroups);
        $this->set('data', $this->delegate->get($id_activityprofile, $cod));
        $this->responseMessageStatus($this->delegate->status);
    }

    /**
     * @OA\Get(
     *     path="/activityprofile/table",
     *     summary="Legge una lista paginata di profilo aziendali",
     *     description="Ritorna una lista di profilo aziendali filtrata, ordinata e paginata in base ai parametri di ricerca passati",
     *     operationId="activityprofile-table",
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
     *             @OA\Items(ref="#/components/schemas/Activityprofile")
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
     *     path="/activityprofile/save",
     *     summary="Salva un profilo aziendale",
     *     description="Salva un profilo aziendale ne ritorna l'id",
     *     operationId="activityprofile-save",
     *     tags={"authentication"},
     *     deprecated=false,
     *     @OA\RequestBody(
     *         description="oggetto profilo aziendale",
     *         request="activityprofile",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Activityprofile")
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
    public function save($activityprofileJson = null, $groupssave = null) {
        parent::evalParam($activityprofileJson, "activityprofile");
        parent::completeFkVfSave($this->delegate, $groupssave);
        $this->set('value', $this->delegate->save($activityprofileJson));
        $this->responseMessageStatus($this->delegate->status);
    }

    /**
     * @OA\Put(
     *     path="/activityprofile/edit",
     *     summary="Modifica un profilo aziendale",
     *     description="Modifica un profilo aziendale ne ritorna l'id",
     *     operationId="activityprofile-edit",
     *     tags={"authentication"},
     *     deprecated=false,
     *     @OA\RequestBody(
     *         description="oggetto profilo aziendale",
     *         request="activityprofile",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Activityprofile")
     *     ),
     *     @OA\Parameter(
     *         description="id del profilo aziendale da modificare",
     *         in="query",
     *         name="id_activityprofile",
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
    public function edit($id_activityprofile = null, $activityprofileJson = null, $groupssave = null, $groupsdel = null) {
        parent::evalParam($id_activityprofile, "id_activityprofile");
        parent::evalParam($activityprofileJson, "activityprofile");
        parent::completeFkVfSave($this->delegate, $groupssave, $groupsdel);
        $this->set('value', $this->delegate->edit($id_activityprofile, $activityprofileJson));
        $this->responseMessageStatus($this->delegate->status);
    }

    /**
     * @OA\Delete(
     *     path="/activityprofile/delete",
     *     summary="Rimuove un profilo aziendale",
     *     description="Rimuove un profilo aziendale dato l'id",
     *     operationId="activityprofile-delete",
     *     tags={"authentication"},
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="id del profilo aziendale da eliminare",
     *         in="query",
     *         name="id_activityprofile",
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
    public function delete($id_activityprofile = null) {
        parent::evalParam($id_activityprofile, "id_activityprofile");
        $this->set('flag', $this->delegate->delete($id_activityprofile));
        $this->responseMessageStatus($this->delegate->status);
    }

    // -------------- profile

    /**
     * @OA\Post(
     *     path="/activityprofile/createprofile",
     *     summary="Salva un profilo aziendale e lo associa",
     *     description="Salva un profilo aziendale, lo associa ad un'azienda ed eventualmente un utente e ad una lista di permessi e ne ritorna l'id",
     *     operationId="activityprofile-createprofile",
     *     tags={"authentication"},
     *     deprecated=false,
     *     @OA\RequestBody(
     *         description="oggetto profilo utente",
     *         request="profile",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Profile")
     *     ),
     *     @OA\Parameter(
     *         description="id activity da associare",
     *         in="query",
     *         name="id_activity",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         description="Lista dei permissions da associare al profilo",
     *         in="query",
     *         name="permissions",
     *         required=true,
     *         @OA\Schema(
     *            type="array",
     *            @OA\Items(ref="#/components/schemas/Permission")
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="id utente da modificare",
     *         in="query",
     *         name="id_user",
     *         required=false,
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
    public function createprofile($profileJson = null, $id_activity = null, $permissions = null, $id_user = null) {
        parent::evalParam($profileJson, "profile");
        parent::evalParam($id_activity, "id_activity");
        parent::evalParam($permissions, "permissions");
        parent::evalParam($id_user, "id_user");
        $this->set('value', $this->delegate->createProfile($profileJson, $id_activity, $permissions, $id_user));
        $this->responseMessageStatus($this->delegate->status);
    }

    /**
     * @OA\Post(
     *     path="/activityprofile/removeprofile",
     *     summary="Rimuove un profilo aziendale",
     *     description="Rimuove un profilo aziendale per una specifica azienda se restrict è false, per tutti in caso contrario, e per un utente o per tutti se non viene passato un id utente",
     *     operationId="activityprofile-removeprofile",
     *     tags={"authentication"},
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="id profilo da eliminare",
     *         in="query",
     *         name="id_profile",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         description="id attività a cui è associato il profilo da eliminare",
     *         in="query",
     *         name="id_activity",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         description="se true rimuove anche il profilo, oltre le associazioni ad esso",
     *         in="query",
     *         name="restrict",
     *         required=false,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Parameter(
     *         description="id utente associato al profilo",
     *         in="query",
     *         name="id_user",
     *         required=false,
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
    public function removeprofile($id_profile = null, $id_activity = null, $restrict = null, $id_user = null) {
        parent::evalParam($id_profile, "id_profile");
        parent::evalParam($id_activity, "id_activity");
        parent::evalParamBool($restrict, "restrict", false);
        parent::evalParam($id_user, "id_user");
        $this->set('flag', $this->delegate->removeProfile($id_profile, $id_activity, $restrict, $id_user));
        $this->responseMessageStatus($this->delegate->status);
    }
}
