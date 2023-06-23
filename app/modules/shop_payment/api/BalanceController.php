<?php
App::uses("AppController", "Controller");
App::uses("BalanceUI", "modules/shop_payment/delegate");
App::uses("AppclientUtility", "modules/authentication/utility");

class BalanceController extends AppController {

    public function beforeFilter() {
        $this->json = true;
        $this->delegate = new BalanceUI();
        $this->delegate->json = $this->json;
        parent::beforeFilter();
        AppclientUtility::checkTokenClient($this);
    }

    /**
     * @OA\Get(
     *     path="/balance/get",
     *     summary="Legge un bilancio",
     *     description="Ritorna un bilancio per uno specifico id o per codice",
     *     operationId="balance-get",
     *     tags={"shop_payment"},
     *     deprecated=false,
     *       @OA\Parameter(
     *         description="id del bilancio",
     *         in="query",
     *         name="id_balance",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         description="codice del bilancio",
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
     *         @OA\JsonContent(ref="#/components/schemas/Balance")
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
    public function get($id_balance = null, $cod = null, $belongs = null, $virtualfields = null, $flags = null, $properties = null, $groups = null, $likegroups = null) {
        parent::evalParam($id_balance, 'id_balance');
        parent::evalParam($cod, 'cod');
        parent::completeFkVf($this->delegate, $belongs, $virtualfields, $flags, $properties, $groups, $likegroups);
        $this->set('data', $this->delegate->get($id_balance, $cod));
        $this->responseMessageStatus($this->delegate->status);
    }

    /**
     * @OA\Get(
     *     path="/balance/table",
     *     summary="Legge una lista paginata di bilanci",
     *     description="Ritorna una lista di bilanci filtrata, ordinata e paginata in base ai parametri di ricerca passati",
     *     operationId="balance-table",
     *     tags={"shop_payment"},
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
     *             @OA\Items(ref="#/components/schemas/Balance")
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
     *     path="/balance/save",
     *     summary="Salva un bilancio",
     *     description="Salva un bilancio ne ritorna l'id",
     *     operationId="balance-save",
     *     tags={"shop_payment"},
     *     deprecated=false,
     *     @OA\RequestBody(
     *         description="oggetto bilancio",
     *         request="balance",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Balance")
     *     ),
     *     @OA\Parameter(
     *         description="Lista dei gruppi da includere",
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
    public function save($balanceJson = null, $groupssave = null) {
        parent::evalParam($balanceJson, "balance");
        parent::completeFkVfSave($this->delegate, $groupssave);
        $this->set('value', $this->delegate->save($balanceJson));
        $this->responseMessageStatus($this->delegate->status);
    }

    /**
     * @OA\Put(
     *     path="/balance/edit",
     *     summary="Modifica un bilancio",
     *     description="Modifica un bilancio ne ritorna l'id",
     *     operationId="balance-edit",
     *     tags={"shop_payment"},
     *     deprecated=false,
     *     @OA\RequestBody(
     *         description="oggetto bilancio",
     *         request="balance",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Balance")
     *     ),
     *     @OA\Parameter(
     *         description="id del bilancio da modificare",
     *         in="query",
     *         name="id_balance",
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
    public function edit($id_balance = null, $balanceJson = null, $groupssave = null, $groupsdel = null) {
        parent::evalParam($id_balance, "id_balance");
        parent::evalParam($balanceJson, "balance");
        parent::completeFkVfSave($this->delegate, $groupssave, $groupsdel);
        $this->set('value', $this->delegate->edit($id_balance, $balanceJson));
        $this->responseMessageStatus($this->delegate->status);
    }

    /**
     * @OA\Delete(
     *     path="/balance/delete",
     *     summary="Rimuove un bilancio",
     *     description="Rimuove un bilancio dato l'id",
     *     operationId="balance-delete",
     *     tags={"shop_payment"},
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="id del bilancio da eliminare",
     *         in="query",
     *         name="id_balance",
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
    public function delete($id_balance = null) {
        parent::evalParam($id_balance, "id_balance");
        $this->set('flag', $this->delegate->delete($id_balance));
        $this->responseMessageStatus($this->delegate->status);
    }

    // ------------------------- PAYMENTS
    /**
     * @OA\Post(
     *     path="/balance/addPayment",
     *     summary="Aggiunge un pagamento in bilancio",
     *     description="Salva un pagamento e lo associa al bilancio",
     *     operationId="balance-addPayment",
     *     tags={"shop_payment"},
     *     deprecated=false,
     *     @OA\RequestBody(
     *         description="oggetto pagamento",
     *         request="payment",
     *         required=false,
     *         @OA\JsonContent(ref="#/components/schemas/Payment")
     *     ),
     *     @OA\Parameter(
     *         description="id del pagamento se si sta aggiungendo un pagamento esistente",
     *         in="query",
     *         name="id_payment",
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
     *         description="id del bilancio a cui aggiungere il pagamento",
     *         in="query",
     *         name="id_balance",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         description="Lista dei gruppi da includere",
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
    public function addPayment($paymentJson = null, $id_payment = null, $priceJson = null, $id_balance = null, $groupssave = null) {
        parent::evalParam($paymentJson, "payment");
        parent::evalParam($id_payment, "id_payment");
        parent::evalParam($priceJson, "price");
        parent::evalParam($id_balance, "id_balance");
        parent::completeFkVfSave($this->delegate, $groupssave);
        $this->set('flag', $this->delegate->addPayment($paymentJson, $id_payment, $priceJson, $id_balance));
        $this->responseMessageStatus($this->delegate->status);
    }

    /**
     * @OA\Get(
     *     path="/balance/payments",
     *     summary="Legge una lista di pagamenti di bilancio",
     *     description="Ritorna una lista di pagamenti di bilancio o bilanci",
     *     operationId="balance-payments",
     *     tags={"shop_payment"},
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="id del bilancio",
     *         in="query",
     *         name="id_balance",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         description="codice del bilancio",
     *         in="query",
     *         name="cod",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         description="lista id di bilanci",
     *         in="query",
     *         name="id_balances",
     *         required=false,
     *         @OA\Schema(
     *              type="array",
     *              @OA\Items(type="string")
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="lista codici di bilanci",
     *         in="query",
     *         name="cods",
     *         required=false,
     *         @OA\Schema(
     *              type="array",
     *              @OA\Items(type="string")
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="id dell'utente",
     *         in="query",
     *         name="id_user",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         description="username",
     *         in="query",
     *         name="username",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         description="id della azienda",
     *         in="query",
     *         name="id_activity",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         description="codice TVS o partita IVA della azienda",
     *         in="query",
     *         name="piva",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         description="oggetto filtro",
     *         in="query",
     *         name="filter",
     *         required=true,
     *         @OA\Schema(ref="#/components/schemas/BalanceFlowFilterDTO")
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
     *         @OA\JsonContent(ref="#/components/schemas/BalanceFlowDTO")
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
    public function payments($id_balance = null, $cod = null, $id_balances = null, $cods = null, $id_user = null, $username = null, $id_activity = null, $piva = null, $filters = null) {
        parent::evalParam($id_balance, 'id_balance');
        parent::evalParam($cod, 'cod');
        parent::evalParamArray($id_balances, 'id_balances');
        parent::evalParamArray($cods, 'cods');
        parent::evalParam($id_user, 'id_user');
        parent::evalParam($username, 'username');
        parent::evalParam($id_activity, 'id_activity');
        parent::evalParam($piva, 'piva');
        parent::evalParam($filters, 'filters');
        $this->set('data', $this->delegate->payments($id_balance, $cod, $id_balances, $cods, $id_user, $username, $id_activity, $piva, $filters));
        $this->responseMessageStatus($this->delegate->status);
    }
}
