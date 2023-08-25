<?php

/**
 * @OA\Schema(type="object", @OA\Xml(name="Authentication2fa"))
 */
class Authentication2fa {
    /**
     * @OA\Property(example="202001", description="ultimo codice valido")
     * @var string
     */
    public $lastCod;
    /**
     * @OA\Property(example="2020-01-01 12:30:22", description="ultima data di memorizzazione")
     * @var string
     */
    public $lastTime;
    /**
     * @OA\Property(example="MOD_WEB", description="chiave applicazione")
     * @var string
     */
    public $key;
    /**
     * @OA\Property(example="60", description="durata del codice in secondi")
     * @var int
     */
    public $timeWait;
}