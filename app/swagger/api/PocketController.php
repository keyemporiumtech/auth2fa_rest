<?php

class PocketController {


    /**
     * @OA\Get(
     *     path="/pocket/get",
     *     summary="Legge un pacchetto",
     *     description="Ritorna un pacchetto per uno specifico id o per codice",
     *     operationId="pocket-get",
     *     tags={"shop_warehouse"},
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="id del pacchetto",
     *         in="query",
     *         name="id_pocket",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         description="codice del pacchetto",
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
     *         @OA\JsonContent(ref="#/components/schemas/Pocket")
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
    public function get($id_pocket = null, $cod = null, $belongs = null, $virtualfields = null, $flags = null, $properties = null, $groups = null, $likegroups = null) {

	}
    /**
     * @OA\Get(
     *     path="/pocket/table",
     *     summary="Legge una lista paginata di pacchetti",
     *     description="Ritorna una lista di pacchetti filtrata, ordinata e paginata in base ai parametri di ricerca passati",
     *     operationId="pocket-table",
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
     *             @OA\Items(ref="#/components/schemas/Pocket")
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

	}
    /**
     * @OA\Post(
     *     path="/pocket/save",
     *     summary="Salva un pacchetto",
     *     description="Salva un pacchetto ne ritorna l'id",
     *     operationId="pocket-save",
     *     tags={"shop_warehouse"},
     *     deprecated=false,
     *     @OA\RequestBody(
     *         description="oggetto pacchetto",
     *         request="pocket",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Pocket")
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
    public function save($pocketJson = null, $groupssave = null) {

	}
    /**
     * @OA\Put(
     *     path="/pocket/edit",
     *     summary="Modifica un pacchetto",
     *     description="Modifica un pacchetto ne ritorna l'id",
     *     operationId="pocket-edit",
     *     tags={"shop_warehouse"},
     *     deprecated=false,
     *     @OA\RequestBody(
     *         description="oggetto pacchetto",
     *         request="pocket",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Pocket")
     *     ),
     *     @OA\Parameter(
     *         description="id del pacchetto da modificare",
     *         in="query",
     *         name="id_pocket",
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
    public function edit($id_pocket = null, $pocketJson = null, $groupssave = null, $groupsdel = null) {

	}
    /**
     * @OA\Delete(
     *     path="/pocket/delete",
     *     summary="Rimuove un pacchetto",
     *     description="Rimuove un pacchetto dato l'id",
     *     operationId="pocket-delete",
     *     tags={"shop_warehouse"},
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="id del pacchetto da eliminare",
     *         in="query",
     *         name="id_pocket",
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
    public function delete($id_pocket = null) {

	}
    // ----------------- RELATIONS
    /**
     * @OA\Post(
     *     path="/pocket/addProduct",
     *     summary="Aggiunge un prodotto ad un pacchetto",
     *     description="Aggiunge un prodotto ad un pacchetto e ne ricalcola gli importi se il pacchetto è related",
     *     operationId="pocket-addProduct",
     *     tags={"shop_warehouse"},
     *     deprecated=false,
     *     @OA\RequestBody(
     *         description="oggetto product",
     *         request="product",
     *         required=false,
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Parameter(
     *         description="id del prodotto da aggiungere se già esistente",
     *         in="query",
     *         name="id_product",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         description="oggetto price",
     *         request="price",
     *         required=false,
     *         @OA\JsonContent(ref="#/components/schemas/Price")
     *     ),
     *     @OA\Parameter(
     *         description="id del pacchetto a cui aggiungere il prodotto",
     *         in="query",
     *         name="id_pocket",
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
    public function addProduct($productJson = null, $id_product = null, $priceJson = null, $id_pocket = null) {

	}
    /**
     * @OA\Post(
     *     path="/pocket/addService",
     *     summary="Aggiunge un servizio ad un pacchetto",
     *     description="Aggiunge un servizio ad un pacchetto e ne ricalcola gli importi se il pacchetto è related",
     *     operationId="pocket-addService",
     *     tags={"shop_warehouse"},
     *     deprecated=false,
     *     @OA\RequestBody(
     *         description="oggetto service",
     *         request="service",
     *         required=false,
     *         @OA\JsonContent(ref="#/components/schemas/Service")
     *     ),
     *     @OA\Parameter(
     *         description="id del servizio da aggiungere se già esistente",
     *         in="query",
     *         name="id_service",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         description="oggetto price",
     *         request="price",
     *         required=false,
     *         @OA\JsonContent(ref="#/components/schemas/Price")
     *     ),
     *     @OA\Parameter(
     *         description="id del pacchetto a cui aggiungere il servizio",
     *         in="query",
     *         name="id_pocket",
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
    public function addService($serviceJson = null, $id_service = null, $priceJson = null, $id_pocket = null) {

	}
    // ----------------- PRICE
    /**
     * @OA\Post(
     *     path="/pocket/addPrice",
     *     summary="Aggiunge un prezzo ad un pacchetto",
     *     description="Aggiunge un prezzo ad pacchetto e ne ricalcola gli importi",
     *     operationId="pocket-addPrice",
     *     tags={"shop_warehouse"},
     *     deprecated=false,
     *     @OA\RequestBody(
     *         description="oggetto price",
     *         request="price",
     *         required=false,
     *         @OA\JsonContent(ref="#/components/schemas/Price")
     *     ),
     *     @OA\Parameter(
     *         description="id del pacchetto a cui aggiungere il price",
     *         in="query",
     *         name="id_pocket",
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
    public function addPrice($id_pocket = null, $priceJson = null, $groupssave = null, $groupsdel = null) {

	}
    /**
     * @OA\Put(
     *     path="/pocket/editPrice",
     *     summary="Modifica il prezzo ad un pacchetto",
     *     description="Modifica il prezzo ad pacchetto e ne ricalcola gli importi",
     *     operationId="pocket-editPrice",
     *     tags={"shop_warehouse"},
     *     deprecated=false,
     *     @OA\RequestBody(
     *         description="oggetto price",
     *         request="price",
     *         required=false,
     *         @OA\JsonContent(ref="#/components/schemas/Price")
     *     ),
     *     @OA\Parameter(
     *         description="id del pacchetto per cui modificare il price",
     *         in="query",
     *         name="id_pocket",
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
    public function editPrice($id_pocket = null, $priceJson = null, $groupssave = null, $groupsdel = null) {

	}
    // ----------------- DISCOUNT
    /**
     * @OA\Post(
     *     path="/pocket/addDiscount",
     *     summary="Aggiunge uno sconto per un pacchetto",
     *     description="Aggiunge uno sconto per un pacchetto e ne ricalcola gli importi",
     *     operationId="pocket-addDiscount",
     *     tags={"shop_warehouse"},
     *     deprecated=false,
     *     @OA\RequestBody(
     *         description="oggetto sconto",
     *         request="discount",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Discount")
     *     ),
     *     @OA\Parameter(
     *         description="id del pacchetto",
     *         in="query",
     *         name="id_pocket",
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
    public function addDiscount($id_pocket = null, $discountJson = null, $groupssave = null, $groupsdel = null) {

	}
    /**
     * @OA\Put(
     *     path="/pocket/editDiscount",
     *     summary="Modifica uno sconto per un pacchetto",
     *     description="Modifica uno sconto per un pacchetto e ne ricalcola gli importi",
     *     operationId="pocket-editDiscount",
     *     tags={"shop_warehouse"},
     *     deprecated=false,
     *     @OA\RequestBody(
     *         description="oggetto sconto",
     *         request="discount",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Discount")
     *     ),
     *     @OA\Parameter(
     *         description="id del pacchetto",
     *         in="query",
     *         name="id_pocket",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         description="id dello sconto",
     *         in="query",
     *         name="id_discount",
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
    public function editDiscount($id_pocket = null, $discountJson = null, $id_discount = null) {

	}
    /**
     * @OA\Delete(
     *     path="/pocket/deleteDiscount",
     *     summary="Elimina uno sconto per un pacchetto",
     *     description="Elimina uno sconto per un pacchetto e ne ricalcola gli importi",
     *     operationId="pocket-deleteDiscount",
     *     tags={"shop_warehouse"},
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="id del pacchetto",
     *         in="query",
     *         name="id_pocket",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         description="id dello sconto",
     *         in="query",
     *         name="id_discount",
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
    public function deleteDiscount($id_pocket = null, $id_discount = null) {

	}
    // ----------------- TAX
    /**
     * @OA\Post(
     *     path="/pocket/addTax",
     *     summary="Aggiunge una tassa per un pacchetto",
     *     description="Aggiunge una tassa per un pacchetto e ne ricalcola gli importi",
     *     operationId="pocket-addTax",
     *     tags={"shop_warehouse"},
     *     deprecated=false,
     *     @OA\RequestBody(
     *         description="oggetto tassa",
     *         request="tax",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Pockettax")
     *     ),
     *     @OA\Parameter(
     *         description="id del pacchetto",
     *         in="query",
     *         name="id_pocket",
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
    public function addTax($id_pocket = null, $taxJson = null, $groupssave = null, $groupsdel = null) {

	}
    /**
     * @OA\Put(
     *     path="/pocket/editTax",
     *     summary="Modifica una tassa per un pacchetto",
     *     description="Modifica una tassa per un pacchetto e ne ricalcola gli importi",
     *     operationId="pocket-editTax",
     *     tags={"shop_warehouse"},
     *     deprecated=false,
     *     @OA\RequestBody(
     *         description="oggetto tassa",
     *         request="tax",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Pockettax")
     *     ),
     *     @OA\Parameter(
     *         description="id del pacchetto",
     *         in="query",
     *         name="id_pocket",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         description="id della tassa",
     *         in="query",
     *         name="id_pockettax",
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
    public function editTax($id_pocket = null, $taxJson = null, $id_pockettax = null) {

	}
    /**
     * @OA\Delete(
     *     path="/pocket/deleteTax",
     *     summary="Elimina una tassa per un pacchetto",
     *     description="Elimina una tassa per un pacchetto e ne ricalcola gli importi",
     *     operationId="pocket-deleteTax",
     *     tags={"shop_warehouse"},
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="id del pacchetto",
     *         in="query",
     *         name="id_pocket",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         description="id della tassa",
     *         in="query",
     *         name="id_pockettax",
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
    public function deleteTax($id_pocket = null, $id_pockettax = null) {

	}}
