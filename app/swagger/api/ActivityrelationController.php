<?php

class ActivityrelationController {


    /**
     * @SWG\Get(
     *     path="/activityrelation/get",
     *     summary="Legge una relazione utente-azienda",
     *     description="Ritorna una relazione utente-azienda per uno specifico id o per codice",
     *     operationId="activityrelation-get",
     *     tags={"authentication"},
     *     consumes={"application/x-www-form-urlencoded","application/form-data","application/raw","application/binary"},
     *     produces={"application/json; charset=UTF-8"},
     *       @SWG\Parameter(
     *         description="id della relazione utente-azienda",
     *         in="query",
     *         name="id_activityrelation",
     *         required=false,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="codice della relazione utente-azienda",
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
     *         @SWG\Schema(ref="#/components/schemas/Activityrelation")
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
    public function get($id_activityrelation = null, $cod = null, $belongs = null, $virtualfields = null, $flags = null, $properties = null, $groups = null, $likegroups = null) {

	}
    /**
     * @SWG\Get(
     *     path="/activityrelation/table",
     *     summary="Legge una lista paginata di relazione utente-aziende",
     *     description="Ritorna una lista di relazione utente-aziende filtrata, ordinata e paginata in base ai parametri di ricerca passati",
     *     operationId="activityrelation-table",
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
     *             @SWG\Items(ref="#/components/schemas/Activityrelation")
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

	}
    /**
     * @SWG\Post(
     *     path="/activityrelation/save",
     *     summary="Salva una relazione utente-azienda",
     *     description="Salva una relazione utente-azienda ne ritorna l'id",
     *     operationId="activityrelation-save",
     *     tags={"authentication"},
     *     consumes={"application/x-www-form-urlencoded","application/form-data","application/raw","application/binary"},
     *     produces={"application/json; charset=UTF-8"},
     *       @SWG\Parameter(
     *         description="oggetto relazione utente-azienda",
     *         in="body",
     *         name="activityrelation",
     *         required=true,
     *         @SWG\Schema(ref="#/components/schemas/Activityrelation")
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
    public function save($activityrelationJson = null, $groupssave = null) {

	}
    /**
     * @SWG\Put(
     *     path="/activityrelation/edit",
     *     summary="Modifica una relazione utente-azienda",
     *     description="Modifica una relazione utente-azienda ne ritorna l'id",
     *     operationId="activityrelation-edit",
     *     tags={"authentication"},
     *     consumes={"application/x-www-form-urlencoded","application/form-data","application/raw","application/binary"},
     *     produces={"application/json; charset=UTF-8"},
     *       @SWG\Parameter(
     *         description="oggetto relazione utente-azienda",
     *         in="body",
     *         name="activityrelation",
     *         required=true,
     *         @SWG\Schema(ref="#/components/schemas/Activityrelation")
     *     ),
     *     @SWG\Parameter(
     *         description="id della relazione utente-azienda da modificare",
     *         in="query",
     *         name="id_activityrelation",
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
    public function edit($id_activityrelation = null, $activityrelationJson = null, $groupssave = null, $groupsdel = null) {

	}
    /**
     * @SWG\Delete(
     *     path="/activityrelation/delete",
     *     summary="Rimuove una relazione utente-azienda",
     *     description="Rimuove una relazione utente-azienda dato l'id",
     *     operationId="activityrelation-delete",
     *     tags={"authentication"},
     *     consumes={"application/x-www-form-urlencoded","application/form-data","application/raw","application/binary"},
     *     produces={"application/json; charset=UTF-8"},
     *     @SWG\Parameter(
     *         description="id della relazione utente-azienda da eliminare",
     *         in="query",
     *         name="id_activityrelation",
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
    public function delete($id_activityrelation = null) {

	}
    // ---------------- TYPOLOGICAL
    /**
     * @OA\Get(
     *     path="/activityrelation/tpactivityrelation",
     *     summary="Legge una lista paginata di tipi di relazioni aziendali",
     *     description="Ritorna una lista di tipi di relazioni aziendali filtrata, ordinata e paginata in base ai parametri di ricerca passati",
     *     operationId="activityrelation-tpactivityrelation",
     *     tags={"typological"},
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
    public function tpactivityrelation($jsonFilters = null, $jsonOrders = null, $jsonPaginate = null, $belongs = null, $virtualfields = null, $flags = null, $properties = null, $groups = null, $likegroups = null) {

	}
}
