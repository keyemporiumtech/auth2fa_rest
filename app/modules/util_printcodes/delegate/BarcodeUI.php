<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("ConnectionManager", "Model");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("BarcodeUtility", "modules/util_printcodes/utility");
App::uses("FileUtility", "modules/coreutils/utility");

class BarcodeUI extends AppGenericUI {

    function __construct() {
        parent::__construct("BarcodeUI");
        $this->localefile = "barcode";
        $this->obj = array(
            new ObjPropertyEntity("url", null, ""),
            new ObjPropertyEntity("path", null, ""),
            new ObjPropertyEntity("name", null, ""),
            new ObjPropertyEntity("cod", null, FileUtility::uuid_short()),
            new ObjPropertyEntity("description", null, ""),
            new ObjPropertyEntity("size", null, 0),
            new ObjPropertyEntity("ext", null, ""),
            new ObjPropertyEntity("mimetype", null, ""),
            new ObjPropertyEntity("type", null, ""),
            new ObjPropertyEntity("flgpre", null, 0),
            new ObjPropertyEntity("flgpost", null, 0),
            new ObjPropertyEntity("prehtml", null, ""),
            new ObjPropertyEntity("posthtml", null, ""),
            new ObjPropertyEntity("tpattachment", null, 0),
        );
    }

    function getAttachment($text = null, $name = null, $ext = "png", $type = 'C128', $widthLine = 2, $heightBar = 30, $color = "#000") {
        $this->LOG_FUNCTION = "getAttachment";
        try {
            if (empty($text)) {
                DelegateUtility::paramsNull($this, "ERROR_BARCODE_NOT_TEXT");
                return "";
            }
            $barcode = BarcodeUtility::getAttachmentByBarcode($text, $name, $ext, $type, $widthLine, $heightBar, $color);
            $this->ok();
            return $this->json ? json_encode($barcode['Attachment']) : $barcode;
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_BARCODE_NOT_CONVERTED");
            return "";
        }
    }
}