<?php

/**
 * @OA\Schema(required={"id", "1"}, type="object", @OA\Xml(name="Activityattachment"))
 */
class Activityattachment {
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
     * @OA\Property(example="1", description="Id dell'allegato")
     * @var string
     */
    public $attachment;
    /**
     * @OA\Property(ref="#/components/schemas/Attachment")
     */
    public $attachment_fk;
    /**
     * @OA\Property(example="A123D", description="Codice della relazione")
     * @var string
     */
    public $cod;
    /**
     * @OA\Property(example="1", description="true se è l'allegato principale per una specifica tipologia")
     * @var string
     */
    public $flgprincipal;
    /**
     * @OA\Property(example="1", description="Id del tipo di allegato")
     * @var string
     */
    public $tpattachment;
    /**
     * @OA\Property(ref="#/components/schemas/Typological")
     */
    public $tpattachment_fk;
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