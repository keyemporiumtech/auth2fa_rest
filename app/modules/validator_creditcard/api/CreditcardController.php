<?php
App::uses("AppController", "Controller");
App::uses("CreditcardUI", "modules/validator_creditcard/delegate");
App::uses("TokenUtility", "modules/cakeutils/utility");

class CreditcardController extends AppController {

    public function beforeFilter() {
        $this->json = true;
        $this->delegate = new CreditcardUI();
        $this->delegate->json = $this->json;
        parent::beforeFilter();
        TokenUtility::checkInnerToken($this);
    }

    /**
     * @OA\Get(
     *     path="/creditcard/validator",
     *     summary="Legge i parametri di una carta di credito e la formatta",
     *     description="Ritorna una carta di credit formattata correttamente per uno specifico numero, expired, cvc e type in input",
     *     operationId="creditcard-validator",
     *     tags={"validators"},
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="numero carta di credito da validare e formattare",
     *         in="query",
     *         name="num_cc",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         description="mese di scadenza della carta",
     *         in="query",
     *         name="mm",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         description="anno di scadenza",
     *         in="query",
     *         name="yy",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         description="codice di sicurezza della carta",
     *         in="query",
     *         name="cvc",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         description="tipo della carta di credito da validare",
     *         in="query",
     *         name="type",
     *         required=true,
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
     *         @OA\JsonContent(ref="#/components/schemas/CreditcardModel")
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
    public function validator($num_cc = null, $mm = null, $yy = null, $cvc = null, $type = null) {
        parent::evalParam($num_cc, 'num_cc');
        parent::evalParam($mm, 'mm');
        parent::evalParam($yy, 'yy');
        parent::evalParam($cvc, 'cvc');
        parent::evalParam($type, 'type');
        $this->set('data', $this->delegate->validate($num_cc, $mm, $yy, $cvc, $type));
        $this->responseMessageStatus($this->delegate->status);
    }

    // ---------------- TYPOLOGICAL
    /**
     * @OA\Get(
     *     path="/creditcard/tpcreditcard",
     *     summary="Legge una lista di tipi di carte di credito",
     *     description="Ritorna una lista di tipi di carta di credito",
     *     operationId="creditcard-tpcreditcard",
     *     tags={"typological"},
     *     deprecated=false,
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
    public function tpcreditcard() {
        $this->set('data', $this->delegate->tpcreditcard());
        $this->responseMessageStatus($this->delegate->status);
    }
}
