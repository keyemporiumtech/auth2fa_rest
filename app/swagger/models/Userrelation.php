<?php

/**
 * @OA\Schema(required={"id", "1"}, type="object", @OA\Xml(name="Userrelation"))
 */
class Userrelation {

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
     * @OA\Property(example="1", description="Id del primo utente in relazione")
     * @var string
     */
    public $user1;
    /**
     * @OA\Property(ref="#/components/schemas/User")
     */
    public $user1_fk;
    /**
     * @OA\Property(example="1", description="Id del secondo utente in relazione")
     * @var string
     */
    public $user2;
    /**
     * @OA\Property(ref="#/components/schemas/User")
     */
    public $user2_fk;
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
     * @OA\Property(example="1", description="Dettaglio relazione utente1 verso utente2")
     * @var string
     */
    public $inforelation1;
    /**
     * @OA\Property(example="1", description="Dettaglio relazione utente2 verso utente1")
     * @var string
     */
    public $inforelation2;
}