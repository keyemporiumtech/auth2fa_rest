<?php

class Authentication2faController {


    /**
     * @OA\Get(
     *     path="/authentication2fa/generate",
     *     summary="Genera un codice di authenticazione",
     *     description="Ritorna l'ultimo codice di authenticazione valido oppure ne genera uno nuovo",
     *     operationId="authentication2fa-generate",
     *     tags={"authentication"},
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="chiave applicativa",
     *         in="query",
     *         name="key",
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
     *         @OA\JsonContent(ref="#/components/schemas/Authentication2fa")
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
    public function generate($key = null) {

	}
    /**
     * @OA\Get(
     *     path="/authentication2fa/check",
     *     summary="Controlla se un codice è valido",
     *     description="Controlla se un codice è ancora valido",
     *     operationId="authentication2fa-check",
     *     tags={"authentication"},
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="chiave applicativa",
     *         in="query",
     *         name="key",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         description="codice da validare",
     *         in="query",
     *         name="cod",
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
    public function check($key = null, $cod = null) {

	}
}
