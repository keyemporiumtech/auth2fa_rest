<?php

/**
 * @OA\Schema(required={"id", "1"}, type="object", @OA\Xml(name="Activityrelation"))
 */
class Activityrelation {

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
     * @OA\Property(example="1", description="Id dell'utente in relazione")
     * @var string
     */
    public $user;
    /**
     * @OA\Property(ref="#/components/schemas/User")
     */
    public $user_fk;
    /**
     * @OA\Property(example="1", description="Id dell'activity in relazione")
     * @var string
     */
    public $activity;
    /**
     * @OA\Property(ref="#/components/schemas/Activity")
     */
    public $activity_fk;
    /**
     * @OA\Property(example="1", description="Id della tipologia di relazione")
     * @var string
     */
    public $tprelation;
    /**
     * @OA\Property(ref="#/components/schemas/Typological")
     */
    public $tprelation_fk;
    /**
     * @OA\Property(example="1", description="Dettaglio relazione utente verso activity")
     * @var string
     */
    public $inforelationuser;
    /**
     * @OA\Property(example="1", description="Dettaglio relazione activity verso utente")
     * @var string
     */
    public $inforelationactivity;
}