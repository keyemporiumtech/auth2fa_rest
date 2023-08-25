<?php

/**
 * @OA\Schema(required={"id", "1"}, type="object", @OA\Xml(name="Useroauthsocial"))
 */
class Useroauthsocial {
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
     * @OA\Property(example="A123D", description="Codice della relazione")
     * @var string
     */
    public $cod;
    /**
     * @OA\Property(example="A123D", description="Id di autenticazione sul social")
     * @var string
     */
    public $oauthid;
    /**
     * @OA\Property(example="1", description="Id del tipo di social")
     * @var string
     */
    public $tpsocialreference;
    /**
     * @OA\Property(ref="#/components/schemas/Typological")
     */
    public $tpsocialreference_fk;
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