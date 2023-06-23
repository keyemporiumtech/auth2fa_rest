<?php
App::uses('PdfUtility', 'modules/util_pdf/utility');

class CvRoleModel {
    public $name;
    public $description;
    public $descriptions = array();

    function __construct($name, $description = null, $length = 0) {
        $this->name = $name;
        $this->description = $description;
        if (!empty($description)) {
            $this->descriptions = PdfUtility::splitText($description, $length);
        }
    }
}