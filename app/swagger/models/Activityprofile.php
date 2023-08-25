<?php

/**
 * @OA\Schema(required={"id", "1"}, type="object", @OA\Xml(name="Activityprofile"))
 */
class Activityprofile {
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
     * @OA\Property(example="1", description="Id dell'activity")
     * @var string
     */
    public $activity;
    /**
     * @OA\Property(ref="#/components/schemas/Activity")
     */
    public $activity_fk;

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
     * @OA\Property(example="1", description="1 se è definito come profilo principale")
     * @var string
     */
    public $flgdefault;
}