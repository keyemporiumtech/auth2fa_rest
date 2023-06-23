<?php
App::uses("AppController", "Controller");
App::uses("PocketproductUI", "modules/shop_warehouse/delegate");
App::uses("AppclientUtility", "modules/authentication/utility");

class PocketproductController extends AppController {

    public function beforeFilter() {
        $this->json = true;
        $this->delegate = new PocketproductUI();
        $this->delegate->json = $this->json;
        parent::beforeFilter();
        AppclientUtility::checkTokenClient($this);
    }

    /**
     * @OA\Get(
     *     path="/pocketproduct/get",
     *     summary="Legge un prodotto del pacchetto",
     *     description="Ritorna un prodotto del pacchetto per uno specifico id o per codice",
     *     operationId="pocketproduct-get",
     *     tags={"shop_warehouse"},
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="id del prodotto del pacchetto",
     *         in="query",
     *         name="id_pocketproduct",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         description="codice del prodotto del pacchetto",
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
     *         @OA\JsonContent(ref="#/components/schemas/Pocketproduct")
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
    public function get($id_pocketproduct = null, $cod = null, $belongs = null, $virtualfields = null, $flags = null, $properties = null, $groups = null, $likegroups = null) {
        parent::evalParam($id_pocketproduct, 'id_pocketproduct');
        parent::evalParam($cod, 'cod');
        parent::completeFkVf($this->delegate, $belongs, $virtualfields, $flags, $properties, $groups, $likegroups);
        $this->set('data', $this->delegate->get($id_pocketproduct, $cod));
        $this->responseMessageStatus($this->delegate->status);
    }

    /**
     * @OA\Get(
     *     path="/pocketproduct/table",
     *     summary="Legge una lista paginata di prodotto del pacchetti",
     *     description="Ritorna una lista di prodotto del pacchetti filtrata, ordinata e paginata in base ai parametri di ricerca passati",
     *     operationId="pocketproduct-table",
     *     tags={"shop_warehouse"},
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
     *             @OA\Items(ref="#/components/schemas/Pocketproduct")
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
     *     path="/pocketproduct/save",
     *     summary="Salva un prodotto del pacchetto",
     *     description="Salva un prodotto del pacchetto ne ritorna l'id",
     *     operationId="pocketproduct-save",
     *     tags={"shop_warehouse"},
     *     deprecated=false,
     *     @OA\RequestBody(
     *         description="oggetto prodotto del pacchetto",
     *         request="pocketproduct",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Pocketproduct")
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
    public function save($pocketproductJson = null, $groupssave = null) {
        parent::evalParam($pocketproductJson, "pocketproduct");
        parent::completeFkVfSave($this->delegate, $groupssave);
        $this->set('value', $this->delegate->save($pocketproductJson));
        $this->responseMessageStatus($this->delegate->status);
    }

    /**
     * @OA\Put(
     *     path="/pocketproduct/edit",
     *     summary="Modifica un prodotto del pacchetto",
     *     description="Modifica un prodotto del pacchetto ne ritorna l'id",
     *     operationId="pocketproduct-edit",
     *     tags={"shop_warehouse"},
     *     deprecated=false,
     *     @OA\RequestBody(
     *         description="oggetto prodotto del pacchetto",
     *         request="pocketproduct",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Pocketproduct")
     *     ),
     *     @OA\Parameter(
     *         description="id del prodotto del pacchetto da modificare",
     *         in="query",
     *         name="id_pocketproduct",
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
    public function edit($id_pocketproduct = null, $pocketproductJson = null, $groupssave = null, $groupsdel = null) {
        parent::evalParam($id_pocketproduct, "id_pocketproduct");
        parent::evalParam($pocketproductJson, "pocketproduct");
        parent::completeFkVfSave($this->delegate, $groupssave, $groupsdel);
        $this->set('value', $this->delegate->edit($id_pocketproduct, $pocketproductJson));
        $this->responseMessageStatus($this->delegate->status);
    }

    /**
     * @OA\Delete(
     *     path="/pocketproduct/delete",
     *     summary="Rimuove un prodotto del pacchetto",
     *     description="Rimuove un prodotto del pacchetto dato l'id",
     *     operationId="pocketproduct-delete",
     *     tags={"shop_warehouse"},
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="id del prodotto del pacchetto da eliminare",
     *         in="query",
     *         name="id_pocketproduct",
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
    public function delete($id_pocketproduct = null) {
        parent::evalParam($id_pocketproduct, "id_pocketproduct");
        $this->set('flag', $this->delegate->delete($id_pocketproduct));
        $this->responseMessageStatus($this->delegate->status);
    }
}
