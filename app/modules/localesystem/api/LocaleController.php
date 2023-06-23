<?php
App::uses("AppController", "Controller");
// delegate
App::uses("LocaleUI", "modules/localesystem/delegate");

class LocaleController extends AppController {

    public function beforeFilter() {
        $this->json = true;
        $this->delegate = new LocaleUI();
        $this->delegate->json = $this->json;
        parent::beforeFilter();
    }

    /**
     * @OA\Get(
     *     path="/locale/timezoneValue",
     *     summary="Legge il timezone del server",
     *     description="Ritorna il timezone del server",
     *     operationId="locale-timezoneValue",
     *     tags={"localesystem"},
     *     deprecated=false,
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
    public function timezoneValue() {
        $this->set('timezone', date('P'));
    }

    /**
     * @OA\Get(
     *     path="/locale/timezoneName",
     *     summary="Legge il nome del timezone del server",
     *     description="Ritorna il nome del timezone del server",
     *     operationId="locale-timezoneName",
     *     tags={"localesystem"},
     *     deprecated=false,
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
    public function timezoneName() {
        $this->set('timezone', date_default_timezone_get());
    }

    /**
     * @OA\Get(
     *     path="/locale/timezone",
     *     summary="Ritorna le informazini del timezone del server",
     *     description="Ritorna le informazini del timezone del server in un dto",
     *     operationId="locale-timezone",
     *     tags={"localesystem"},
     *     deprecated=false,
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
     *         @OA\JsonContent(ref="#/components/schemas/Timezone")
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
    public function timezone() {
        $this->response->type('json');
        $this->set('timezone', $this->delegate->timezone());
    }
}