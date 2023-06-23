<?php
App::uses('EnumCvTemplate', 'modules/work_cv/plugin/cvPdfMaker/enums');

class CvGenericModel {
    public $template = EnumCvTemplate::SIMPLE;
    public $font = "Arial";
    public $color = "#000";
    public $fillColor;
    public $space = 5;
}