<?php
// delegate

class LocaleController {


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

	}}