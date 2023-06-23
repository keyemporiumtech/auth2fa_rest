<?php
App::uses('EnumTicketTemplate', 'modules/shop_warehouse/plugin/ticketPdfMaker/enums');

class TicketGenericModel {
    public $template = EnumTicketTemplate::SIMPLE;
    public $font = "Arial";
    public $color = "#000";
    public $fillColor;
    public $space = 5;

    function templateUrl() {
        switch ($this->template) {
        case EnumTicketTemplate::SIMPLE:
            return 'ticket_template.png';
        default:
            return 'ticket_template.png';
        }
    }
}