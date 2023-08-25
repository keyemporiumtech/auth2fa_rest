<?php

/**
 * @OA\Schema(required={"id", "1"}, type="object", @OA\Xml(name="SocialUser"))
 */
class SocialUser {
    /**
     * @OA\Property(example="1", description="identificativo del record")
     * @var string
     */
    public $id;
    /**
     * @OA\Property(example="", description="provider oauth")
     * @var string
     */
    public $provider;
    /**
     * @OA\Property(example="", description="url foto profilo oauth")
     * @var string
     */
    public $photoUrl;
    /**
     * @OA\Property(example="", description="token di autorizzazione oauth")
     * @var string
     */
    public $authToken;
    /**
     * @OA\Property(example="", description="id di autorizzazione oauth")
     * @var string
     */
    public $idToken;
    /**
     * @OA\Property(example="", description="codice di autorizzazione oauth")
     * @var string
     */
    public $authorizationCode;
    /**
     * @OA\Property(ref="#/components/schemas/User")
     */
    public $user;
    /**
     * @OA\Property(
     *      description="Indirizzi dell'utente",
     *      @OA\Schema(
     *          type="array",
     *          @OA\Items(ref="#/components/schemas/Address")
     *      )
     * )
     */
    public $addresses;
    /**
     * @OA\Property(
     *      description="Telefoni dell'utente",
     *      @OA\Schema(
     *          type="array",
     *          @OA\Items(ref="#/components/schemas/Contactreference")
     *      )
     * )
     */
    public $phones;
}