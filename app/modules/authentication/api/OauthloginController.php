<?php
App::uses("AppController", "Controller");
App::uses("OauthloginUI", "modules/authentication/delegate");
App::uses("AppclientUtility", "modules/authentication/utility");

class OauthloginController extends AppController {

    public function beforeFilter() {
        $this->json = true;
        $this->delegate = new OauthloginUI();
        $this->delegate->json = $this->json;
        parent::beforeFilter();
        AppclientUtility::checkTokenClient($this);
    }

    /**
     * @OA\Get(
     *     path="/oauthlogin/check",
     *     summary="Legge un utente dall'oauth esterno",
     *     description="Ritorna un utente che rappresenta i dati dell'oauth esterno",
     *     operationId="oauthlogin-check",
     *     tags={"authentication"},
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="oggetto oauth",
     *         in="query",
     *         name="socialuser",
     *         required=true,
     *         @OA\Schema(ref="#/components/schemas/SocialUser")
     *     ),
     *     @OA\Parameter(
     *         description="tipo di social login",
     *         in="query",
     *         name="tpsocialreference",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         description="id dell'utente se già esiste",
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
     *         @OA\JsonContent(ref="#/components/schemas/User")
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
    public function check($socialuser = null, $tpsocialreference = null, $id_user = null) {
        parent::evalParam($socialuser, 'socialuser');
        parent::evalParam($tpsocialreference, 'tpsocialreference');
        parent::evalParam($id_user, 'id_user');
        $this->set('data', $this->delegate->check($socialuser, $tpsocialreference, $id_user));
        $this->responseMessageStatus($this->delegate->status);
    }

    /**
     * @OA\Post(
     *     path="/oauthlogin/login",
     *     summary="Login utente per oauth",
     *     description="Ritorna un token utente dopo l'oauth login",
     *     operationId="oauthlogin-login",
     *     tags={"authentication"},
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="username dell'utente",
     *         in="query",
     *         name="username",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         description="id dell'oauth",
     *         in="query",
     *         name="oauthid",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         description="tipo di social",
     *         in="query",
     *         name="tpsocialreference",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         description="se true richiede di non applicare l'expiration al token",
     *         in="query",
     *         name="rememberme",
     *         required=false,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\RequestBody(
     *         description="se valorizzato indica di non memorizzare il token ma di attendere la conferma tramite email o sms",
     *         request="confirmoperation_request",
     *         required=false,
     *         @OA\JsonContent(ref="#/components/schemas/ConfirmoperationRequest")
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
     *         @OA\Schema(type="string")
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
    public function login($username = null, $oauthid = null, $tpsocialreference = null, $rememberme = null, $confirmoperationRequest = null) {
        parent::evalParam($username, "username");
        parent::evalParam($oauthid, 'oauthid');
        parent::evalParam($tpsocialreference, 'tpsocialreference');
        parent::evalParamBool($rememberme, "rememberme");
        parent::evalParam($confirmoperationRequest, "confirmoperation_request");
        $clientId = AppclientUtility::getToken($this->request);
        $this->set('value', $this->delegate->login($username, $rememberme, $clientId, $confirmoperationRequest));
        $this->responseMessageStatus($this->delegate->status);
    }

}
