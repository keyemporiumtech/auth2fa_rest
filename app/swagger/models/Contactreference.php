<?php

/**
 * @OA\Schema(required={"id", "1"}, type="object", @OA\Xml(name="Contactreference"))
 */
class Contactreference {
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
     * @OA\Property(example="A123D", description="Codice del contatto")
     * @var string
     */
    public $cod;
    /**
     * @OA\Property(example="desc", description="Descrizione del contatto")
     * @var string
     */
    public $description;
    /**
     * @OA\Property(example="1", description="true se usato")
     * @var string
     */
    public $flgused;
    /**
     * @OA\Property(example="XXXXX", description="content della nazione in caso di prefisso telefonico")
     * @var string
     */
    public $nationimage;
    /**
     * @OA\Property(example="+39", description="prefisso telefonico")
     * @var string
     */
    public $prefix;
    /**
     * @OA\Property(example="XXXXX", description="content della tipologia di contatto")
     * @var string
     */
    public $refernceimage;
    /**
     * @OA\Property(example="XXXXX", description="content della tipologia di social per contatti di tipo link a pagine social")
     * @var string
     */
    public $socialimage;
    /**
     * @OA\Property(example="5", description="tipo di contatto")
     * @var string
     */
    public $tpcontactreference;
    /**
     * @OA\Property(ref="#/components/schemas/Typological")
     */
    public $tpcontactreference_fk;
    /**
     * @OA\Property(example="4", description="tipo di social per contatti di tipo link a pagine social")
     * @var string
     */
    public $tpsocialreference;
    /**
     * @OA\Property(ref="#/components/schemas/Typological")
     */
    public $tpsocialreference_fk;
    /**
     * @OA\Property(example="3214325435 o prova@prova.it", description="valore del contatto in base al tipo")
     * @var string
     */
    public $val;
}