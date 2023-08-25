<?php

/**
 * @OA\Schema(required={"id", "1"}, type="object", @OA\Xml(name="Userrelationpermission"))
 */
class Userrelationpermission {

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
     * @OA\Property(example="1", description="Id della relazione")
     * @var string
     */
    public $userrelation;
    /**
     * @OA\Property(ref="#/components/schemas/Userrelation")
     */
    public $userrelation_fk;
    /**
     * @OA\Property(example="1", description="Id del permesso sulla relazione")
     * @var string
     */
    public $permission;
    /**
     * @OA\Property(ref="#/components/schemas/Permission")
     */
    public $permission_fk;
    /**
     * @OA\Property(example="1", description="verso della relazione 1 = [1=>2] o 2 = [2=>1]")
     * @var string
     */
    public $direction;

}