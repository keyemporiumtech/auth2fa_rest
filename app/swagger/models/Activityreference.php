<?php

/**
 * @OA\Schema(required={"id", "1"}, type="object", @OA\Xml(name="Activityreference"))
 */
class Activityreference {
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
     * @OA\Property(example="1", description="Id del contatto")
     * @var string
     */
    public $contactreference;
    /**
     * @OA\Property(ref="#/components/schemas/Contactreference")
     */
    public $contactreference_fk;
    /**
     * @OA\Property(example="1", description="true se è il contatto principale per uno specifico tipo")
     * @var string
     */
    public $flgprincipal;
    /**
     * @OA\Property(example="1", description="Id della tipologia di contatto")
     * @var string
     */
    public $tpcontactreference;
    /**
     * @OA\Property(ref="#/components/schemas/Typological")
     */
    public $tpcontactreference_fk;
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