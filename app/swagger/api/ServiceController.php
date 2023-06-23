<?php

class ServiceController {


    /**
     * @OA\Get(
     *     path="/service/get",
     *     summary="Legge un servizio",
     *     description="Ritorna un servizio per uno specifico id o per codice",
     *     operationId="service-get",
     *     tags={"shop_warehouse"},
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="id del servizio",
     *         in="query",
     *         name="id_service",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         description="codice del servizio",
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
     *         @OA\JsonContent(ref="#/components/schemas/Service")
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
    public function get($id_service = null, $cod = null, $belongs = null, $virtualfields = null, $flags = null, $properties = null, $groups = null, $likegroups = null) {

	}
    /**
     * @OA\Get(
     *     path="/service/table",
     *     summary="Legge una lista paginata di servizii",
     *     description="Ritorna una lista di servizii filtrata, ordinata e paginata in base ai parametri di ricerca passati",
     *     operationId="service-table",
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
     *             @OA\Items(ref="#/components/schemas/Service")
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
     *     path="/service/save",
     *     summary="Salva un servizio",
     *     description="Salva un servizio ne ritorna l'id",
     *     operationId="service-save",
     *     tags={"shop_warehouse"},
     *     deprecated=false,
     *     @OA\RequestBody(
     *         description="oggetto servizio",
     *         request="service",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Service")
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
    public function save($serviceJson = null, $groupssave = null) {

	}
    /**
     * @OA\Put(
     *     path="/service/edit",
     *     summary="Modifica un servizio",
     *     description="Modifica un servizio ne ritorna l'id",
     *     operationId="service-edit",
     *     tags={"shop_warehouse"},
     *     deprecated=false,
     *     @OA\RequestBody(
     *         description="oggetto servizio",
     *         request="service",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Service")
     *     ),
     *     @OA\Parameter(
     *         description="id del servizio da modificare",
     *         in="query",
     *         name="id_service",
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
    public function edit($id_service = null, $serviceJson = null, $groupssave = null, $groupsdel = null) {

	}
    /**
     * @OA\Delete(
     *     path="/service/delete",
     *     summary="Rimuove un servizio",
     *     description="Rimuove un servizio dato l'id",
     *     operationId="service-delete",
     *     tags={"shop_warehouse"},
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="id del servizio da eliminare",
     *         in="query",
     *         name="id_service",
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
    public function delete($id_service = null) {

	}
    // ----------------- PRICE
    /**
     * @OA\Post(
     *     path="/service/addPrice",
     *     summary="Aggiunge un prezzo ad un servizio",
     *     description="Aggiunge un prezzo ad servizio e ne ricalcola gli importi",
     *     operationId="service-addPrice",
     *     tags={"shop_warehouse"},
     *     deprecated=false,
     *     @OA\RequestBody(
     *         description="oggetto price",
     *         request="price",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Price")
     *     ),
     *     @OA\Parameter(
     *         description="id del servizio a cui aggiungere il price",
     *         in="query",
     *         name="id_service",
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
    public function addPrice($id_service = null, $priceJson = null, $groupssave = null, $groupsdel = null) {

	}
    /**
     * @OA\Put(
     *     path="/service/editPrice",
     *     summary="Modifica il prezzo ad un servizio",
     *     description="Modifica il prezzo ad servizio e ne ricalcola gli importi",
     *     operationId="service-editPrice",
     *     tags={"shop_warehouse"},
     *     deprecated=false,
     *     @OA\RequestBody(
     *         description="oggetto price",
     *         request="price",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Price")
     *     ),
     *     @OA\Parameter(
     *         description="id del servizio per cui modificare il price",
     *         in="query",
     *         name="id_service",
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
    public function editPrice($id_service = null, $priceJson = null, $groupssave = null, $groupsdel = null) {

	}
    // ----------------- DISCOUNT
    /**
     * @OA\Post(
     *     path="/service/addDiscount",
     *     summary="Aggiunge uno sconto per un servizio",
     *     description="Aggiunge uno sconto per un servizio e ne ricalcola gli importi",
     *     operationId="service-addDiscount",
     *     tags={"shop_warehouse"},
     *     deprecated=false,
     *     @OA\RequestBody(
     *         description="oggetto sconto",
     *         request="discount",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Discount")
     *     ),
     *     @OA\Parameter(
     *         description="id del servizio",
     *         in="query",
     *         name="id_service",
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
    public function addDiscount($id_service = null, $discountJson = null, $groupssave = null, $groupsdel = null) {

	}
    /**
     * @OA\Put(
     *     path="/service/editDiscount",
     *     summary="Modifica uno sconto per un servizio",
     *     description="Modifica uno sconto per un servizio e ne ricalcola gli importi",
     *     operationId="service-editDiscount",
     *     tags={"shop_warehouse"},
     *     deprecated=false,
     *     @OA\RequestBody(
     *         description="oggetto sconto",
     *         request="discount",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Discount")
     *     ),
     *     @OA\Parameter(
     *         description="id del servizio",
     *         in="query",
     *         name="id_service",
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
    public function editDiscount($id_service = null, $discountJson = null, $id_discount = null) {

	}
    /**
     * @OA\Delete(
     *     path="/service/deleteDiscount",
     *     summary="Elimina uno sconto per un servizio",
     *     description="Elimina uno sconto per un servizio e ne ricalcola gli importi",
     *     operationId="service-deleteDiscount",
     *     tags={"shop_warehouse"},
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="id del servizio",
     *         in="query",
     *         name="id_service",
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
    public function deleteDiscount($id_service = null, $id_discount = null) {

	}
    // ----------------- TAX
    /**
     * @OA\Post(
     *     path="/service/addTax",
     *     summary="Aggiunge una tassa per un servizio",
     *     description="Aggiunge una tassa per un servizio e ne ricalcola gli importi",
     *     operationId="service-addTax",
     *     tags={"shop_warehouse"},
     *     deprecated=false,
     *     @OA\RequestBody(
     *         description="oggetto tassa",
     *         request="tax",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Servicetax")
     *     ),
     *     @OA\Parameter(
     *         description="id del servizio",
     *         in="query",
     *         name="id_service",
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
    public function addTax($id_service = null, $taxJson = null, $groupssave = null, $groupsdel = null) {

	}
    /**
     * @OA\Put(
     *     path="/service/editTax",
     *     summary="Modifica una tassa per un servizio",
     *     description="Modifica una tassa per un servizio e ne ricalcola gli importi",
     *     operationId="service-editTax",
     *     tags={"shop_warehouse"},
     *     deprecated=false,
     *     @OA\RequestBody(
     *         description="oggetto tassa",
     *         request="tax",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Servicetax")
     *     ),
     *     @OA\Parameter(
     *         description="id del servizio",
     *         in="query",
     *         name="id_service",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         description="id della tassa",
     *         in="query",
     *         name="id_servicetax",
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
    public function editTax($id_service = null, $taxJson = null, $id_servicetax = null) {

	}
    /**
     * @OA\Delete(
     *     path="/service/deleteTax",
     *     summary="Elimina una tassa per un servizio",
     *     description="Elimina una tassa per un servizio e ne ricalcola gli importi",
     *     operationId="service-deleteTax",
     *     tags={"shop_warehouse"},
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="id del servizio",
     *         in="query",
     *         name="id_service",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         description="id della tassa",
     *         in="query",
     *         name="id_servicetax",
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
    public function deleteTax($id_service = null, $id_servicetax = null) {

	}}
