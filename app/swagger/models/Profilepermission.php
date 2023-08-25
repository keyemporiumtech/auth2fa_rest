<?php

/**
 * @OA\Schema(required={"id", "1"}, type="object", @OA\Xml(name="Profilepermission"))
 */
class Profilepermission {

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
     * @OA\Property(example="1", description="Id del profilo")
     * @var string
     */
    public $profile;
    /**
     * @OA\Property(ref="#/components/schemas/Profile")
     */
    public $profile_fk;
    /**
     * @OA\Property(example="1", description="Id del permesso")
     * @var string
     */
    public $permission;
    /**
     * @OA\Property(ref="#/components/schemas/Permission")
     */
    public $permission_fk;
}