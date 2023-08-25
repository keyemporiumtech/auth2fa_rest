<?php

/**
 * @OA\Schema(required={"id", "1"}, type="object", @OA\Xml(name="Profile"))
 */
class Profile {

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
     * @OA\Property(example="A123D", description="Codice del profilo")
     * @var string
     */
    public $cod;
    /**
     * @OA\Property(example="P_1", description="Nome del profilo")
     * @var string
     */
    public $name;
    /**
     * @OA\Property(example="desc", description="Descrizione del profilo")
     * @var string
     */
    public $description;
}