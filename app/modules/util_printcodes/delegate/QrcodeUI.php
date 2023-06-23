<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("ConnectionManager", "Model");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("QrcodeUtility", "modules/util_printcodes/utility");
App::uses("FileUtility", "modules/coreutils/utility");

class QrcodeUI extends AppGenericUI {

    function __construct() {
        parent::__construct("QrcodeUI");
        $this->localefile = "qrcode";
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

    function getAttachment($text = null, $name = null, $size = 3, $margin = 4, $level = 0) {
        $this->LOG_FUNCTION = "getAttachment";
        try {
            if (empty($text)) {
                DelegateUtility::paramsNull($this, "ERROR_QRCODE_NOT_TEXT");
                return "";
            }
            $qrcode = QrcodeUtility::getAttachmentByQrcode($text, $name, $size, $margin, $level);
            $this->ok();
            return $this->json ? json_encode($qrcode['Attachment']) : $qrcode;
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_QRCODE_NOT_CONVERTED");
            return "";
        }
    }
}