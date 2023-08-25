<?php

/**
 * @OA\Schema(required={"id", "1"}, type="object", @OA\Xml(name="User"))
 */
class User {

    /**
     * @OA\Property(example="2020-01-01", description="Data di nascita")
     * @var string
     */
    public $born;
    /**
     * @OA\Property(example="SSSPPP88E54E765D", description="Codice fiscale o identificativo")
     * @var string
     */
    public $cf;
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
     * @OA\Property(example="Giovanni", description="Nome alla nascita")
     * @var string
     */
    public $name;
    /**
     * @OA\Property(example="**********", description="Password cryptata")
     * @var string
     */
    public $passclean;
    /**
     * @OA\Property(example="**********", description="Password cryptata")
     * @var string
     */
    public $password;
    /**
     * @OA\Property(example="M", description="Sesso")
     * @var string
     */
    public $sex;
    /**
     * @OA\Property(example="Pascoli", description="Cognome alla nascita")
     * @var string
     */
    public $surname;
    /**
     * @OA\Property(example="utente@email.com", description="Nome utente")
     * @var string
     */
    public $username;
}