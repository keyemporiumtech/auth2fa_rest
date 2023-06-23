<?php
App::uses("PdfUtility", "modules/util_pdf/utility");
App::uses('PdfStructureModel', 'modules/util_pdf/classes');
// plugin
App::uses('CvPdf', 'modules/work_cv/plugin/cvPdfMaker');
App::uses('CvGenericModel', 'modules/work_cv/plugin/cvPdfMaker/models');
App::uses('CvInfoModel', 'modules/work_cv/plugin/cvPdfMaker/models');

class CvPdfMaker {

    static function printCV($webroot = "", $name = null, PdfStructureModel $structure = null, CvGenericModel $generic = null, CvInfoModel $info = null) {
        $plugin = CvPdfMaker::makePlugin($webroot, $name, $structure, $generic, $info);
        $plugin->preview();
    }

    static function downloadCV($webroot = "", $name = null, PdfStructureModel $structure = null, CvGenericModel $generic = null, CvInfoModel $info = null) {
        $plugin = CvPdfMaker::makePlugin($webroot, $name, $structure, $generic, $info);
        $plugin->download();
    }

    static function makePlugin($webroot = "", $name = null, PdfStructureModel $structure = null, CvGenericModel $generic = null, CvInfoModel $info = null) {
        $plugin = new CvPdf($webroot, $name, $structure, $generic, $info);
        $plugin->makeCv($info);
        return $plugin;
    }

    static function example() {

    }
}
