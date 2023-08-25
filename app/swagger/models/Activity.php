<?php

/**
 * @OA\Schema(required={"id", "1"}, type="object", @OA\Xml(name="Activity"))
 */
class Activity {
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
     * @OA\Property(example="1980-01-01", description="Data di creazione activity")
     * @var string
     */
    public $born;
    /**
     * @OA\Property(example="desc", description="Descrizione breve dell'activity")
     * @var string
     */
    public $description;
    /**
     * @OA\Property(example="2", description="orientamento left per alberatura di aziende")
     * @var string
     */
    public $lft;
    /**
     * @OA\Property(example="Azienda", description="Nome dell'activity")
     * @var string
     */
    public $name;
    /**
     * @OA\Property(example="Azienda SPA", description="Nome ufficiale, o ragione sociale, dell'activity")
     * @var string
     */
    public $namecod;
    /**
     * @OA\Property(example="1", description="Id dell'activity padre in caso di alberatura")
     * @var string
     */
    public $parent_id;
    /**
     * @OA\Property(example="1231243243", description="codice TVS o partita IVA")
     * @var string
     */
    public $piva;
    /**
     * @OA\Property(example="2", description="orientamento right per alberatura di aziende")
     * @var string
     */
    public $rght;
    /**
     * @OA\Property(example="5", description="tipo di activity")
     * @var string
     */
    public $tpactivity;
    /**
     * @OA\Property(ref="#/components/schemas/Typological")
     */
    public $tpactivity_fk;
    /**
     * @OA\Property(example="4", description="categoria di activity")
     * @var string
     */
    public $tpcat;
    /**
     * @OA\Property(ref="#/components/schemas/Typological")
     */
    public $tpcat_fk;
}