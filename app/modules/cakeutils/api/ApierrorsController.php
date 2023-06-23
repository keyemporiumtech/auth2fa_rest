<?php
App::uses("AppController", "Controller");
// delegate
App::uses("ErrorUI", "modules/cakeutils/delegate");

class ApierrorsController extends AppController {

    public function beforeFilter() {
        $this->json = true;
        $this->delegate = new ErrorUI();
        $this->delegate->json = $this->json;
        parent::beforeFilter();
    }

    /**
     * @OA\Get(
     *     path="/apierrors/apierror",
     *     summary="Risponde con un errore",
     *     description="Risponde con un errore",
     *     operationId="system-test",
     *     tags={"cakeutils"},
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="Codice del messaggio",
     *         in="query",
     *         name="cod",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         description="Codice della response nel caso di response status a 200",
     *         in="query",
     *         name="responsecod",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         description="Messaggio pubblico",
     *         in="query",
     *         name="message",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         description="Messaggio proveniente da una eccezione generata",
     *         in="query",
     *         name="exception",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         description="Codice dell'eccezione generata",
     *         in="query",
     *         name="exceptioncod",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         description="Messaggio specifico di errore interno",
     *         in="query",
     *         name="internal",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         description="Codice del messaggio interno",
     *         in="query",
     *         name="internalcod",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="[statuscod=0]<br/>successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Apierror")
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
    public function apierror($cod = null, $responsecod = null, $message = null, $exception = null, $exceptioncod = null, $internal = null, $internalcod = null) {
        parent::evalParam($cod, 'cod');
        parent::evalParam($responsecod, 'responsecod');
        parent::evalParam($message, 'message');
        parent::evalParam($exception, 'exception');
        parent::evalParam($exceptioncod, 'exceptioncod');
        parent::evalParam($internal, 'internal');
        parent::evalParam($internalcod, 'internalcod');
        $this->set("error", $this->delegate->apierror($cod, $message, $exception, $exceptioncod, $internal, $internalcod, $responsecod));
        $this->responseMessageStatus($this->delegate->status);
    }
}