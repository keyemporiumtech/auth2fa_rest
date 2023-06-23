<?php
class TicketRequestInfoDto {
    public $title;
    public $description;
    public $price;
    public $iva;
    public $iva_percent;
    public $flg_ivainclude; // EnumIVAType
    public $currency; // symbol
    public $quantity; // number
}