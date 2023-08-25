<?php

/**
 * @OA\Schema(required={"id", "1"}, type="object", @OA\Xml(name="Clienttoken"))
 */
class Clienttoken {

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
     * @OA\Property(example="client1", description="Nome dell'applicazione")
     * @var string
     */
    public $appname;
    /**
     * @OA\Property(example="", description="token del client")
     * @var string
     */
    public $token;
}