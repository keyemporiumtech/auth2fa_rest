<?php

/**
 * @OA\Schema(required={"id", "1"}, type="object", @OA\Xml(name="Activityaddress"))
 */
class Activityaddress {
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
     * @OA\Property(example="1", description="Id dell'indirizzo")
     * @var string
     */
    public $address;
    /**
     * @OA\Property(ref="#/components/schemas/Address")
     */
    public $address_fk;
    /**
     * @OA\Property(example="A123D", description="Codice della relazione")
     * @var string
     */
    public $cod;
    /**
     * @OA\Property(example="1", description="true se è l'indirizzo principale")
     * @var string
     */
    public $flgprincipal;
    /**
     * @OA\Property(example="1", description="Id dell'activity")
     * @var string
     */
    public $activity;
    /**
     * @OA\Property(ref="#/components/schemas/Activity")
     */
    public $activity_fk;
}