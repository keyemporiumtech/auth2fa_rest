<?php

class PasswordController {


    /**
     * @OA\Get(
     *     path="/password/validator",
     *     summary="Legge una password e la valida",
     *     description="Ritorna una validazione per una password",
     *     operationId="password-validator",
     *     tags={"validators"},
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="password da validare",
     *         in="query",
     *         name="password",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         description="minimo numero di caratteri richiesto",
     *         in="query",
     *         name="min",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         description="massimo numero di caratteri richiesto",
     *         in="query",
     *         name="max",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         description="livello di sicurezza richiesto (max 4, default 3)",
     *         in="query",
     *         name="level",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         description="separatore per i messaggi di errore",
     *         in="query",
     *         name="separator",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),    *
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
     *         @OA\JsonContent(ref="#/components/schemas/PasswordModel")
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
    public function validator($password = null, $min = null, $max = null, $level = null, $separator = null) {

	}
}
