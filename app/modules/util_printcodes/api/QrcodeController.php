<?php
App::uses("AppController", "Controller");
App::uses("QrcodeUI", "modules/util_printcodes/delegate");
App::uses("TokenUtility", "modules/cakeutils/utility");

class QrcodeController extends AppController {

    public function beforeFilter() {
        $this->json = true;
        $this->delegate = new QrcodeUI();
        $this->delegate->json = $this->json;
        parent::beforeFilter();
        TokenUtility::checkInnerToken($this);
    }

    /**
     * @OA\Get(
     *     path="/qrcode/get",
     *     summary="Ritorna un allegato di tipo qrcode",
     *     description="Ritorna un allegato di tipo qrcode per uno specifico testo",
     *     operationId="qrcode-get",
     *     tags={"util_printcodes"},
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="testo da convertire in qrcode",
     *         in="query",
     *         name="text",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         description="nome del file",
     *         in="query",
     *         name="name",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         description="dimensione richiesta (default 3)",
     *         in="query",
     *         name="size",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         description="margine (default 4)",
     *         in="query",
     *         name="margin",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         description="level (default 0, fino a 4)",
     *         in="query",
     *         name="level",
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
     *         @OA\JsonContent(ref="#/components/schemas/Attachment")
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
    public function get($text = null, $name = null, $size = null, $margin = null, $level = null) {
        parent::evalParam($text, 'text');
        parent::evalParam($name, 'name');
        parent::evalParam($size, 'size', 3);
        parent::evalParam($margin, 'margin', 4);
        parent::evalParam($level, 'level', 0);
        $this->set('data', $this->delegate->getAttachment($text, $name, $size, $margin, $level));
        $this->responseMessageStatus($this->delegate->status);
    }
}