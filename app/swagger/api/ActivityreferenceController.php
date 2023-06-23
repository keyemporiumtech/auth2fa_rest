<?php

class ActivityreferenceController {


    /**
     * @OA\Get(
     *     path="/activityreference/get",
     *     summary="Legge un contatto",
     *     description="Ritorna un contatto per uno specifico id o per codice",
     *     operationId="activityreference-get",
     *     tags={"authentication"},
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="id del contatto",
     *         in="query",
     *         name="id_activityreference",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         description="codice del contatto",
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
     *         @OA\JsonContent(ref="#/components/schemas/Activityreference")
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
    public function get($id_activityreference = null, $cod = null, $belongs = null, $virtualfields = null, $flags = null, $properties = null, $groups = null, $likegroups = null) {

	}
    /**
     * @OA\Get(
     *     path="/activityreference/table",
     *     summary="Legge una lista paginata di contatti",
     *     description="Ritorna una lista di contatti filtrata, ordinata e paginata in base ai parametri di ricerca passati",
     *     operationId="activityreference-table",
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
     *             @OA\Items(ref="#/components/schemas/Activityreference")
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
     *     path="/activityreference/save",
     *     summary="Salva un contatto",
     *     description="Salva un contatto ne ritorna l'id",
     *     operationId="activityreference-save",
     *     tags={"authentication"},
     *     deprecated=false,
     *     @OA\RequestBody(
     *         description="oggetto contatto",
     *         request="activityreference",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Activityreference")
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
    public function save($activityreferenceJson = null, $groupssave = null) {

	}
    /**
     * @OA\Put(
     *     path="/activityreference/edit",
     *     summary="Modifica un contatto",
     *     description="Modifica un contatto ne ritorna l'id",
     *     operationId="activityreference-edit",
     *     tags={"authentication"},
     *     deprecated=false,
     *     @OA\RequestBody(
     *         description="oggetto contatto",
     *         request="activityreference",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Activityreference")
     *     ),
     *     @OA\Parameter(
     *         description="id del contatto da modificare",
     *         in="query",
     *         name="id_activityreference",
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
    public function edit($id_activityreference = null, $activityreferenceJson = null, $groupssave = null, $groupsdel = null) {

	}
    /**
     * @OA\Delete(
     *     path="/activityreference/delete",
     *     summary="Rimuove un contatto",
     *     description="Rimuove un contatto dato l'id",
     *     operationId="activityreference-delete",
     *     tags={"authentication"},
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="id del contatto da eliminare",
     *         in="query",
     *         name="id_activityreference",
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
    public function delete($id_activityreference = null) {

	}
    // ------------- PRINCIPAL
    /**
     * @OA\Get(
     *     path="/activityreference/getPrincipal",
     *     summary="Legge il contatto principale di una azienda",
     *     description="Ritorna il contatto aziendale principale per uno specifico id utente o per piva utente e per tipo di contatto",
     *     operationId="activityreference-getPrincipal",
     *     tags={"authentication"},
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="id dell'azienda",
     *         in="query",
     *         name="id_activity",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         description="codice TVS o partita IVA dell'azienda",
     *         in="query",
     *         name="piva",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         description="tipo di contatto",
     *         in="query",
     *         name="type",
     *         required=true,
     *         @OA\Schema(type="integer")
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
     *         @OA\JsonContent(ref="#/components/schemas/Activityreference")
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
    public function getPrincipal($id_activity = null, $piva = null, $type = null, $belongs = null, $virtualfields = null, $flags = null, $properties = null, $groups = null, $likegroups = null) {

	}
    /**
     * @OA\Put(
     *     path="/activityreference/setPrincipal",
     *     summary="Imposta il contatto principale di una azienda",
     *     description="Imposta il contatto aziendale principale per uno specifico id utente o per piva utente e per id activityreference o codice e per tipo di contatto",
     *     operationId="activityreference-setPrincipal",
     *     tags={"authentication"},
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="id dell'azienda",
     *         in="query",
     *         name="id_activity",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         description="codice TVS o partita IVA dell'azienda",
     *         in="query",
     *         name="piva",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         description="id della relazione contatto-azienda",
     *         in="query",
     *         name="id_activityreference",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         description="codice del contatto aziendale",
     *         in="query",
     *         name="cod_activityreference",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         description="tipo di contatto",
     *         in="query",
     *         name="type",
     *         required=true,
     *         @OA\Schema(type="integer")
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
     *         @OA\JsonContent(ref="#/components/schemas/Activityreference")
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
    public function setPrincipal($id_activity = null, $piva = null, $id_activityreference = null, $cod_activityreference = null, $type = null, $groups = null, $likegroups = null) {

	}
    // ------------- RELATION
    /**
     * @OA\Post(
     *     path="/activityreference/saveRelation",
     *     summary="Salva un contatto attività",
     *     description="Salva un contatto attività ne ritorna l'id",
     *     operationId="activityreference-saveRelation",
     *     tags={"authentication"},
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="id dell'attività",
     *         in="query",
     *         name="id_activity",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         description="oggetto contatto attività",
     *         request="contactreference",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Contactreference")
     *     ),
     *     @OA\Parameter(
     *         description="tipologia di contatto",
     *         in="query",
     *         name="tpcontactreference",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         description="se true imposta come contatto principale",
     *         in="query",
     *         name="flgprincipal",
     *         required=false,
     *         @OA\Schema(type="boolean")
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
    public function saveRelation($id_activity = null, $contactreference = null, $tpcontactreference = null, $flgprincipal = null, $groupssave = null) {

	}}
