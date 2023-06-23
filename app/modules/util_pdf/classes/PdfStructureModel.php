<?php
App::uses('EnumPdfFormat', 'modules/util_pdf/config');
App::uses('EnumPdfOrientation', 'modules/util_pdf/config');

class PdfStructureModel {
    public $orientation = EnumPdfOrientation::VERTICAL;
    public $unit = "mm";
    public $size = EnumPdfFormat::A4;
}