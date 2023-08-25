<?php

/**
 * @OA\Schema(required={"id", "1"}, type="object", @OA\Xml(name="Confirmoperation"))
 */
class Confirmoperation {

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
     * @OA\Property(example="LOGIN", description="Codice del tipo di operazione")
     * @var string
     */
    public $codoperation;
    /**
     * @OA\Property(example="Login utente", description="Descrizione del tipo di operazione")
     * @var string
     */
    public $description;
    /**
     * @OA\Property(example="+393334455667", description="Numero di telefono a cui è stato inviato il codice di verifica")
     * @var string
     */
    public $phone;
    /**
     * @OA\Property(example="1234", description="codice di verifica inviato per sms")
     * @var string
     */
    public $codsms;
    /**
     * @OA\Property(example="user@email.it", description="Indirizzo email a cui è stato inviato il codice di verifica")
     * @var string
     */
    public $email;
    /**
     * @OA\Property(example="1234", description="codice di verifica inviato per email")
     * @var string
     */
    public $codemail;
    /**
     * @OA\Property(example="1", description="Id dell'utente")
     * @var string
     */
    public $user;
    /**
     * @OA\Property(ref="#/components/schemas/User")
     */
    public $user_fk;
    /**
     * @OA\Property(example="", description="token di login")
     * @var string
     */
    public $token;
    /**
     * @OA\Property(example="1", description="1 se è il codice è stato inviato")
     * @var string
     */
    public $flgaccepted;
    /**
     * @OA\Property(example="1", description="1 se è il codice è stato consumato, utilizzato o scaduto")
     * @var string
     */
    public $flgclosed;
}