<?php

/**
 * @OA\Schema(required={"id", "1"}, type="object", @OA\Xml(name="Userreport"))
 */
class Userreport {
    /**
     * @OA\Property(example="2020-01-01", description="data di creazione record")
     * @var string
     */
    public $created;
    /**
     * @OA\Property(example="1", description="identificativo del record")
     * @var string
     */
    public $id;
    /**
     * @OA\Property(example="2020-01-01", description="Data di ultima modifica del record")
     * @var string
     */
    public $modified;
    /**
     * @OA\Property(example="A123D", description="Codice operazione")
     * @var string
     */
    public $codoperation;
    /**
     * @OA\Property(example="Login utente", description="descrizione operazione")
     * @var string
     */
    public $description;
    /**
     * @OA\Property(example="XXXX", description="id della sessione utente")
     * @var string
     */
    public $sessionid;
    /**
     * @OA\Property(example="127.0.0.1", description="ip della sessione utente")
     * @var string
     */
    public $ip;
    /**
     * @OA\Property(example="Windows", description="sistema operativo della sessione utente")
     * @var string
     */
    public $os;
    /**
     * @OA\Property(example="", description="versione del browser della sessione utente")
     * @var string
     */
    public $browser;
    /**
     * @OA\Property(example="", description="nome del browser della sessione utente")
     * @var string
     */
    public $browser_name;
    /**
     * @OA\Property(example="1", description="Id dell'utente")
     * @var string
     */
    public $user;
    /**
     * @OA\Property(ref="#/components/schemas/User")
     */
    public $user_fk;
}