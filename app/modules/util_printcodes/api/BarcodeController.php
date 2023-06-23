<?php
App::uses("AppController", "Controller");
App::uses("BarcodeUI", "modules/util_printcodes/delegate");
App::uses("TokenUtility", "modules/cakeutils/utility");

class BarcodeController extends AppController {

    public function beforeFilter() {
        $this->json = true;
        $this->delegate = new BarcodeUI();
        $this->delegate->json = $this->json;
        parent::beforeFilter();
        TokenUtility::checkInnerToken($this);
    }

    /**
     * @OA\Get(
     *     path="/barcode/get",
     *     summary="Ritorna un allegato di tipo barcode",
     *     description="Ritorna un allegato di tipo barcode per uno specifico testo",
     *     operationId="barcode-get",
     *     tags={"util_printcodes"},
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="testo da convertire in barcode",
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
     *         description="estensione richiesta (valori ammessi png, svg e jpg)",
     *         in="query",
     *         name="ext",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         description="dimensione della riga barcode in pixel (default 2)",
     *         in="query",
     *         name="width",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         description="altezza delle righe barcode in pixel (default 30)",
     *         in="query",
     *         name="height",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         description="colore delle linee barcode (default #000)",
     *         in="query",
     *         name="color",
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
    public function get($text = null, $name = null, $ext = null, $width = null, $height = null, $color = null) {
        parent::evalParam($text, 'text');
        parent::evalParam($name, 'name');
        parent::evalParam($ext, 'ext', "png");
        parent::evalParam($width, 'width', 2);
        parent::evalParam($height, 'height', 30);
        parent::evalParam($color, 'color', "#000");
        $this->set('data', $this->delegate->getAttachment($text, $name, $ext, 'C128', $width, $height, $color));
        $this->responseMessageStatus($this->delegate->status);
    }
}