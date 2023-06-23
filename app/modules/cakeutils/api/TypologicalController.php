<?php
App::uses("AppController", "Controller");
App::uses("TypologicalUI", "modules/cakeutils/delegate");

class TypologicalController extends AppController {

    public function beforeFilter() {
        $this->json = true;
        parent::beforeFilter();
    }

    /**
     * @OA\Get(
     *     path="/typological/get",
     *     summary="Legge un tipo",
     *     description="Ritorna un tipo per uno specifico id o per codice o per simbolo",
     *     operationId="typological-get",
     *     tags={"cakeutils"},
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="nome dell'entity tipologica",
     *         in="query",
     *         name="entity_name",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         description="modulo in cui risiede l'entity tipologica",
     *         in="query",
     *         name="entity_module",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         description="id del tipo",
     *         in="query",
     *         name="id_typological",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         description="codice del tipo",
     *         in="query",
     *         name="cod",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         description="simbolo del tipo",
     *         in="query",
     *         name="symbol",
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
     *         @OA\JsonContent(ref="#/components/schemas/Typological")
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
    public function get($entity = null, $module = null, $id_typological = null, $cod = null, $symbol = null, $belongs = null, $virtualfields = null, $flags = null, $properties = null, $groups = null, $likegroups = null) {
        parent::evalParam($entity, 'entity_name');
        parent::evalParam($module, 'entity_module');
        $this->delegate = new TypologicalUI($entity, $module);
        $this->delegate->json = $this->json;
        parent::evalParam($id_typological, 'id_typological');
        parent::evalParam($cod, 'cod');
        parent::evalParam($symbol, 'symbol');
        parent::completeFkVf($this->delegate, $belongs, $virtualfields, $flags, $properties, $groups, $likegroups);
        $this->set('data', $this->delegate->get($id_typological, $cod, $symbol));
        $this->responseMessageStatus($this->delegate->status);
    }

    /**
     * @OA\Get(
     *     path="/typological/table",
     *     summary="Legge una lista paginata di tipi",
     *     description="Ritorna una lista di tipi filtrata, ordinata e paginata in base ai parametri di ricerca passati",
     *     operationId="typological-table",
     *     tags={"cakeutils"},
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="nome dell'entity tipologica",
     *         in="query",
     *         name="entity_name",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         description="modulo in cui risiede l'entity tipologica",
     *         in="query",
     *         name="entity_module",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
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
     *             @OA\Items(ref="#/components/schemas/Typological")
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
    public function table($entity = null, $module = null, $jsonFilters = null, $jsonOrders = null, $jsonPaginate = null, $belongs = null, $virtualfields = null, $flags = null, $properties = null, $groups = null, $likegroups = null) {
        parent::evalParam($entity, 'entity_name');
        parent::evalParam($module, 'entity_module');
        $this->delegate = new TypologicalUI($entity, $module);
        $this->delegate->json = $this->json;
        parent::evalParam($jsonFilters, 'filters');
        parent::evalParam($jsonOrders, 'orders');
        parent::evalParam($jsonPaginate, 'paginate');
        parent::completeFkVf($this->delegate, $belongs, $virtualfields, $flags, $properties, $groups, $likegroups);
        $this->set('data', $this->delegate->table($jsonFilters, $jsonOrders, $jsonPaginate));
        $this->responseMessageStatus($this->delegate->status);
    }

    /**
     * @OA\Post(
     *     path="/typological/save",
     *     summary="Salva un client",
     *     description="Salva un client ne ritorna l'id",
     *     operationId="typological-save",
     *     tags={"cakeutils"},
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="nome dell'entity tipologica",
     *         in="query",
     *         name="entity_name",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         description="modulo in cui risiede l'entity tipologica",
     *         in="query",
     *         name="entity_module",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         description="oggetto tipologica",
     *         request="typological",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Typological")
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
    public function save($entity = null, $module = null, $typologicalJson = null, $groupssave = null) {
        parent::evalParam($entity, 'entity_name');
        parent::evalParam($module, 'entity_module');
        $this->delegate = new TypologicalUI($entity, $module);
        $this->delegate->json = $this->json;
        parent::evalParam($typologicalJson, "typological");
        parent::completeFkVfSave($this->delegate, $groupssave);
        $this->set('value', $this->delegate->save($typologicalJson));
        $this->responseMessageStatus($this->delegate->status);
    }

    /**
     * @OA\Put(
     *     path="/typological/edit",
     *     summary="Modifica un tipo",
     *     description="Modifica un tipo ne ritorna l'id",
     *     operationId="typological-edit",
     *     tags={"cakeutils"},
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="nome dell'entity tipologica",
     *         in="query",
     *         name="entity_name",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         description="modulo in cui risiede l'entity tipologica",
     *         in="query",
     *         name="entity_module",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         description="oggetto tipologica",
     *         request="typological",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Typological")
     *     ),
     *     @OA\Parameter(
     *         description="id tipo da modificare",
     *         in="query",
     *         name="id_typological",
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
    public function edit($entity = null, $module = null, $id_typological = null, $typologicalJson = null, $groupssave = null, $groupsdel = null) {
        parent::evalParam($entity, 'entity_name');
        parent::evalParam($module, 'entity_module');
        $this->delegate = new TypologicalUI($entity, $module);
        $this->delegate->json = $this->json;
        parent::evalParam($id_typological, "id_typological");
        parent::evalParam($typologicalJson, "typological");
        parent::completeFkVfSave($this->delegate, $groupssave, $groupsdel);
        $this->set('value', $this->delegate->edit($id_typological, $typologicalJson));
        $this->responseMessageStatus($this->delegate->status);
    }

    /**
     * @OA\Delete(
     *     path="/typological/delete",
     *     summary="Rimuove un tipo",
     *     description="Rimuove un tipo dato l'id",
     *     operationId="typological-delete",
     *     tags={"cakeutils"},
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="nome dell'entity tipologica",
     *         in="query",
     *         name="entity_name",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         description="modulo in cui risiede l'entity tipologica",
     *         in="query",
     *         name="entity_module",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         description="id del tipo da eliminare",
     *         in="query",
     *         name="id_typological",
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
    public function delete($entity = null, $module = null, $id_typological = null) {
        parent::evalParam($entity, 'entity_name');
        parent::evalParam($module, 'entity_module');
        $this->delegate = new TypologicalUI($entity, $module);
        $this->delegate->json = $this->json;
        parent::evalParam($id_typological, "id_typological");
        $this->set('flag', $this->delegate->delete($id_typological));
        $this->responseMessageStatus($this->delegate->status);
    }
}