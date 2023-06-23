<?php
App::uses("AppController", "Controller");
App::uses("CookiemanagerUI", "modules/cakeutils/delegate");

class CookiemanagerController extends AppController {

    public function beforeFilter() {
        $this->json = true;
        $this->delegate = new CookiemanagerUI();
        $this->delegate->json = $this->json;
        parent::beforeFilter();
    }

    /**
     * @OA\Get(
     *     path="/cookiemanager/update",
     *     summary="Aggiorna le preferenze dei cookie",
     *     description="Aggiorna i valori scelti per le tipologie di cookie",
     *     operationId="cookiemanager-update",
     *     tags={"cakeutils"},
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="cookie di preferenze",
     *         in="query",
     *         name="flgPreference",
     *         required=true,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Parameter(
     *         description="cookie di statistiche",
     *         in="query",
     *         name="flgStatistic",
     *         required=true,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Parameter(
     *         description="cookie di marketing",
     *         in="query",
     *         name="flgMarketing",
     *         required=true,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Parameter(
     *         description="cookie non classificati",
     *         in="query",
     *         name="flgNotClassified",
     *         required=true,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Parameter(
     *         description="cookie necessari",
     *         in="query",
     *         name="flgNecessary",
     *         required=true,
     *         @OA\Schema(type="boolean")
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
    public function update($flgPreference = null, $flgStatistic = null, $flgMarketing = null, $flgNotClassified = null, $flgNecessary = null) {
        parent::evalParamBool($flgPreference, 'flgPreference');
        parent::evalParamBool($flgStatistic, 'flgStatistic');
        parent::evalParamBool($flgMarketing, 'flgMarketing');
        parent::evalParamBool($flgNotClassified, 'flgNotClassified');
        parent::evalParamBool($flgNecessary, 'flgNecessary', true);
        $this->set('flag', $this->delegate->update($flgPreference, $flgStatistic, $flgMarketing, $flgNotClassified, $flgNecessary));
        $this->responseMessageStatus($this->delegate->status);
    }

    /**
     * @OA\Get(
     *     path="/cookiemanager/cookies",
     *     summary="Legge la lista dei cookie istanziati",
     *     description="Ritorna la lista dei cookie istanziati",
     *     operationId="cookiemanager-cookies",
     *     tags={"cakeutils"},
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="tipologia di cookie richiesti",
     *         in="query",
     *         name="type",
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
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/CookieDTO")
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
    public function cookies($type = null) {
        parent::evalParam($type, 'type');
        $this->set('data', $this->delegate->cookies($type));
        $this->responseMessageStatus($this->delegate->status);
    }

    /**
     * @OA\Get(
     *     path="/cookiemanager/cookie",
     *     summary="Legge un cookie istanziato",
     *     description="Ritorna un cookie istanziato dal nome",
     *     operationId="cookiemanager-cookie",
     *     tags={"cakeutils"},
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="nome o chiave del cookie richiesto",
     *         in="query",
     *         name="key",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         description="tipologia del cookie richiesto",
     *         in="query",
     *         name="type",
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
     *         @OA\JsonContent(ref="#/components/schemas/CookieDTO")
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
    public function cookie($key = null, $type = null) {
        parent::evalParam($key, 'key');
        parent::evalParam($type, 'type');
        $this->set('data', $this->delegate->cookie($key, $type));
        $this->responseMessageStatus($this->delegate->status);
    }

    /**
     * @OA\Get(
     *     path="/cookiemanager/status",
     *     summary="Legge lo stato di un cookie o di un tipo o di tutti i tipi di cookie istanziati",
     *     description="Ritorna lo stato di un cookie o di un tipo o di tutti i tipi di cookie istanziati in base ai parametri passati",
     *     operationId="cookiemanager-status",
     *     tags={"cakeutils"},
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="nome o chiave del cookie richiesto",
     *         in="query",
     *         name="key",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         description="tipologia del cookie richiesto",
     *         in="query",
     *         name="type",
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
     *         @OA\JsonContent(ref="#/components/schemas/CookieStatusDTO")
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
    public function status($key = null, $type = null) {
        parent::evalParam($key, 'key');
        parent::evalParam($type, 'type');
        $this->set('data', $this->delegate->status($key, $type));
        $this->responseMessageStatus($this->delegate->status);
    }

}