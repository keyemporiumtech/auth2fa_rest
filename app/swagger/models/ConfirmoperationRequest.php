<?php

/**
 * @OA\Schema(required={"id", "1"}, type="object", @OA\Xml(name="ConfirmoperationRequest"))
 */
class ConfirmoperationRequest {

    /**
     * @OA\Property(example="1", description="Indica che bisogna inviare un codice di controllo per sms")
     * @var string
     */
    public $flgsms;
    /**
     * @OA\Property(example="3281111111", description="numero di telefono a cui inviare l'sms")
     * @var string
     */
    public $phone;
    /**
     * @OA\Property(example="1", description="Indica che bisogna inviare un codice di controllo per email")
     * @var string
     */
    public $flgemail;
    /**
     * @OA\Property(example="prova@test.t", description="indirizzo email a cui inviare il codice")
     * @var string
     */
    public $email;
    /**
     * @OA\Property(ref="#/components/schemas/Mailer")
     */
    public $mailer;
}