<?php
App::uses("ExcelUI", "modules/util_excel/classes");
App::uses("AttachmentBS", "modules/resources/business");

class ExcelUtility {

    static function getInstance($webroot = "", $name = null, $ext = null, $firstSheetTitle = null) {
        return new ExcelUI($webroot, $name, $ext, $firstSheetTitle);
    }

    static function getAttachmentByExcel(ExcelUI $excelUI) {
        $content = $excelUI->getContent();
        $attachmentBS = new AttachmentBS();
        $attachment = $attachmentBS->instance();
        $attachment['Attachment']['name'] = $excelUI->filename;
        $attachment['Attachment']['size'] = FileUtility::getSizeByContent($content, $excelUI->ext); //bytes
        $attachment['Attachment']['ext'] = $excelUI->ext;
        $attachment['Attachment']['mimetype'] = "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
        $attachment['Attachment']['content'] = $content;
        return $attachment;
    }

}
