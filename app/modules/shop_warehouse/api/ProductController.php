<?php
App::uses("AppController", "Controller");
App::uses("ProductUI", "modules/shop_warehouse/delegate");
App::uses("AppclientUtility", "modules/authentication/utility");

class ProductController extends AppController {

    public function beforeFilter() {
        $this->json = true;
        $this->delegate = new ProductUI();
        $this->delegate->json = $this->json;
        parent::beforeFilter();
        AppclientUtility::checkTokenClient($this);
    }

    /**
     * @OA\Get(
     *     path="/product/get",
     *     summary="Legge un prodotto",
     *     description="Ritorna un prodotto per uno specifico id o per codice",
     *     operationId="product-get",
     *     tags={"shop_warehouse"},
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="id del prodotto",
     *         in="query",
     *         name="id_product",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         description="codice del prodotto",
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
     *         @OA\JsonContent(ref="#/components/schemas/Product")
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
    public function get($id_product = null, $cod = null, $belongs = null, $virtualfields = null, $flags = null, $properties = null, $groups = null, $likegroups = null) {
        parent::evalParam($id_product, 'id_product');
        parent::evalParam($cod, 'cod');
        parent::completeFkVf($this->delegate, $belongs, $virtualfields, $flags, $properties, $groups, $likegroups);
        $this->set('data', $this->delegate->get($id_product, $cod));
        $this->responseMessageStatus($this->delegate->status);
    }

    /**
     * @OA\Get(
     *     path="/product/table",
     *     summary="Legge una lista paginata di prodotti",
     *     description="Ritorna una lista di prodotti filtrata, ordinata e paginata in base ai parametri di ricerca passati",
     *     operationId="product-table",
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
     *             @OA\Items(ref="#/components/schemas/Product")
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
     *     path="/product/save",
     *     summary="Salva un prodotto",
     *     description="Salva un prodotto ne ritorna l'id",
     *     operationId="product-save",
     *     tags={"shop_warehouse"},
     *     deprecated=false,
     *     @OA\RequestBody(
     *         description="oggetto prodotto",
     *         request="product",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Product")
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
    public function save($productJson = null, $groupssave = null) {
        parent::evalParam($productJson, "product");
        parent::completeFkVfSave($this->delegate, $groupssave);
        $this->set('value', $this->delegate->save($productJson));
        $this->responseMessageStatus($this->delegate->status);
    }

    /**
     * @OA\Put(
     *     path="/product/edit",
     *     summary="Modifica un prodotto",
     *     description="Modifica un prodotto ne ritorna l'id",
     *     operationId="product-edit",
     *     tags={"shop_warehouse"},
     *     deprecated=false,
     *     @OA\RequestBody(
     *         description="oggetto prodotto",
     *         request="product",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Parameter(
     *         description="id del prodotto da modificare",
     *         in="query",
     *         name="id_product",
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
    public function edit($id_product = null, $productJson = null, $groupssave = null, $groupsdel = null) {
        parent::evalParam($id_product, "id_product");
        parent::evalParam($productJson, "product");
        parent::completeFkVfSave($this->delegate, $groupssave, $groupsdel);
        $this->set('value', $this->delegate->edit($id_product, $productJson));
        $this->responseMessageStatus($this->delegate->status);
    }

    /**
     * @OA\Delete(
     *     path="/product/delete",
     *     summary="Rimuove un prodotto",
     *     description="Rimuove un prodotto dato l'id",
     *     operationId="product-delete",
     *     tags={"shop_warehouse"},
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="id del prodotto da eliminare",
     *         in="query",
     *         name="id_product",
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
    public function delete($id_product = null) {
        parent::evalParam($id_product, "id_product");
        $this->set('flag', $this->delegate->delete($id_product));
        $this->responseMessageStatus($this->delegate->status);
    }

    // ----------------- PRICE
    /**
     * @OA\Post(
     *     path="/product/addPrice",
     *     summary="Aggiunge un prezzo ad un prodotto",
     *     description="Aggiunge un prezzo ad prodotto e ne ricalcola gli importi",
     *     operationId="product-addPrice",
     *     tags={"shop_warehouse"},
     *     deprecated=false,
     *     @OA\RequestBody(
     *         description="oggetto price",
     *         request="price",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Price")
     *     ),
     *     @OA\Parameter(
     *         description="id del prodotto a cui aggiungere il price",
     *         in="query",
     *         name="id_product",
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
    public function addPrice($id_product = null, $priceJson = null, $groupssave = null, $groupsdel = null) {
        parent::evalParam($id_product, "id_product");
        parent::evalParam($priceJson, "price");
        $this->set('flag', $this->delegate->addPrice($priceJson, $id_product));
        $this->responseMessageStatus($this->delegate->status);
    }

    /**
     * @OA\Put(
     *     path="/product/editPrice",
     *     summary="Modifica il prezzo ad un prodotto",
     *     description="Modifica il prezzo ad prodotto e ne ricalcola gli importi",
     *     operationId="product-editPrice",
     *     tags={"shop_warehouse"},
     *     deprecated=false,
     *     @OA\RequestBody(
     *         description="oggetto price",
     *         request="price",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Price")
     *     ),
     *     @OA\Parameter(
     *         description="id del prodotto per cui modificare il price",
     *         in="query",
     *         name="id_product",
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
    public function editPrice($id_product = null, $priceJson = null, $groupssave = null, $groupsdel = null) {
        parent::evalParam($id_product, "id_product");
        parent::evalParam($priceJson, "price");
        $this->set('flag', $this->delegate->editPrice($priceJson, $id_product));
        $this->responseMessageStatus($this->delegate->status);
    }

    // ----------------- DISCOUNT
    /**
     * @OA\Post(
     *     path="/product/addDiscount",
     *     summary="Aggiunge uno sconto per un prodotto",
     *     description="Aggiunge uno sconto per un prodotto e ne ricalcola gli importi",
     *     operationId="product-addDiscount",
     *     tags={"shop_warehouse"},
     *     deprecated=false,
     *     @OA\RequestBody(
     *         description="oggetto sconto",
     *         request="discount",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Discount")
     *     ),
     *     @OA\Parameter(
     *         description="id del prodotto",
     *         in="query",
     *         name="id_product",
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
    public function addDiscount($id_product = null, $discountJson = null, $groupssave = null, $groupsdel = null) {
        parent::evalParam($id_product, "id_product");
        parent::evalParam($discountJson, "discount");
        $this->set('flag', $this->delegate->addDiscount($discountJson, $id_product));
        $this->responseMessageStatus($this->delegate->status);
    }

    /**
     * @OA\Put(
     *     path="/product/editDiscount",
     *     summary="Modifica uno sconto per un prodotto",
     *     description="Modifica uno sconto per un prodotto e ne ricalcola gli importi",
     *     operationId="product-editDiscount",
     *     tags={"shop_warehouse"},
     *     deprecated=false,
     *     @OA\RequestBody(
     *         description="oggetto sconto",
     *         request="discount",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Discount")
     *     ),
     *     @OA\Parameter(
     *         description="id del prodotto",
     *         in="query",
     *         name="id_product",
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
    public function editDiscount($id_product = null, $discountJson = null, $id_discount = null) {
        parent::evalParam($id_product, "id_product");
        parent::evalParam($discountJson, "discount");
        parent::evalParam($id_discount, "id_discount");
        $this->set('flag', $this->delegate->editDiscount($discountJson, $id_discount, $id_product));
        $this->responseMessageStatus($this->delegate->status);
    }

    /**
     * @OA\Delete(
     *     path="/product/deleteDiscount",
     *     summary="Elimina uno sconto per un prodotto",
     *     description="Elimina uno sconto per un prodotto e ne ricalcola gli importi",
     *     operationId="product-deleteDiscount",
     *     tags={"shop_warehouse"},
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="id del prodotto",
     *         in="query",
     *         name="id_product",
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
    public function deleteDiscount($id_product = null, $id_discount = null) {
        parent::evalParam($id_product, "id_product");
        parent::evalParam($id_discount, "id_discount");
        $this->set('flag', $this->delegate->deleteDiscount($id_discount, $id_product));
        $this->responseMessageStatus($this->delegate->status);
    }

    // ----------------- TAX
    /**
     * @OA\Post(
     *     path="/product/addTax",
     *     summary="Aggiunge una tassa per un prodotto",
     *     description="Aggiunge una tassa per un prodotto e ne ricalcola gli importi",
     *     operationId="product-addTax",
     *     tags={"shop_warehouse"},
     *     deprecated=false,
     *     @OA\RequestBody(
     *         description="oggetto tassa",
     *         request="tax",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Producttax")
     *     ),
     *     @OA\Parameter(
     *         description="id del prodotto",
     *         in="query",
     *         name="id_product",
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
    public function addTax($id_product = null, $taxJson = null, $groupssave = null, $groupsdel = null) {
        parent::evalParam($id_product, "id_product");
        parent::evalParam($taxJson, "tax");
        $this->set('flag', $this->delegate->addTax($taxJson, $id_product));
        $this->responseMessageStatus($this->delegate->status);
    }

    /**
     * @OA\Put(
     *     path="/product/editTax",
     *     summary="Modifica una tassa per un prodotto",
     *     description="Modifica una tassa per un prodotto e ne ricalcola gli importi",
     *     operationId="product-editTax",
     *     tags={"shop_warehouse"},
     *     deprecated=false,
     *     @OA\RequestBody(
     *         description="oggetto tassa",
     *         request="tax",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Producttax")
     *     ),
     *     @OA\Parameter(
     *         description="id del prodotto",
     *         in="query",
     *         name="id_product",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         description="id della tassa",
     *         in="query",
     *         name="id_producttax",
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
    public function editTax($id_product = null, $taxJson = null, $id_producttax = null) {
        parent::evalParam($id_product, "id_product");
        parent::evalParam($taxJson, "tax");
        parent::evalParam($id_producttax, "id_producttax");
        $this->set('flag', $this->delegate->editTax($taxJson, $id_producttax, $id_product));
        $this->responseMessageStatus($this->delegate->status);
    }

    /**
     * @OA\Delete(
     *     path="/product/deleteTax",
     *     summary="Elimina una tassa per un prodotto",
     *     description="Elimina una tassa per un prodotto e ne ricalcola gli importi",
     *     operationId="product-deleteTax",
     *     tags={"shop_warehouse"},
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="id del prodotto",
     *         in="query",
     *         name="id_product",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         description="id della tassa",
     *         in="query",
     *         name="id_producttax",
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
    public function deleteTax($id_product = null, $id_producttax = null) {
        parent::evalParam($id_product, "id_product");
        parent::evalParam($id_producttax, "id_producttax");
        $this->set('flag', $this->delegate->deleteTax($id_producttax, $id_product));
        $this->responseMessageStatus($this->delegate->status);
    }
}
