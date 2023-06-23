<?php
App::uses("FileUtility", "modules/coreutils/utility");
App::uses("StringUtility", "modules/coreutils/utility");
App::uses("AttachmentBS", "modules/resources/business");
App::uses("EnumAttachmentType", "modules/resources/config");

class QrcodeUtility {

    /**
     * Converte un testo in immagine qrcode png e la salva sul filesystem
     * @param string $text testo da convertire in qrcode
     * @param string $path path in cui salvare il qrcode (se è vuoto salva in tmp)
     * @param string $name nome del file qrcode (se vuoto è generato)
     * @param int $size dimensione (default 3)
     * @param int $margin margine (default 4)
     * @param int $level 0,1,2,3 valori ammessi (default 0)
     * @param string $saveandprint se true salva e stampa (non usato)
     * @return string Ritorna il path dell'immagine qrcode
     */
    static function getQrcodeImage($text, $path = null, $name = null, $size = 3, $margin = 4, $level = 0, $saveandprint = false) {
        require_once ROOT . '/app/modules/util_printcodes/plugin/Qrcode/qrlib.php';
        $tmp_name = (!empty($name) ? $name : FileUtility::uuid_medium());
        $filename = (StringUtility::contains($tmp_name, ".png") ? $tmp_name : $tmp_name . ".png");
        if (empty($path)) {
            $path = WWW_ROOT . "tmp/";
        }
        $path .= $filename;
        $qrcode = QRcode::png($text, $path, $level, $size, $margin, $saveandprint);
        return $path;
    }

    /**
     * Ritorna in contenuto in base64 del qrcode generato
     *
     * @param string $text testo da convertire in qrcode
     * @param int $size dimensione (default 3)
     * @param int $margin margine (default 4)
     * @param int $level 0,1,2,3 valori ammessi (default 0)
     * @param string $saveandprint se true salva e stampa (non usato)
     * @return string Ritorna il contenuto in base64 del qrcode
     */
    static function getQrcodeContent($text, $size = 3, $margin = 4, $level = 0, $saveandprint = false) {
        $path = QrcodeUtility::getQrcodeImage($text, null, null, $size, $margin, $level, $saveandprint);
        $content = FileUtility::getBaseContentByPath($path);
        unlink($path);
        return $content;
    }

    /**
     * Ritorna una entity Attachment con le informazioni del qrcode generato
     *
     * @param string $text testo da convertire in qrcode
     * @param string $name nome del file qrcode (se vuoto è generato)
     * @param int $size dimensione (default 3)
     * @param int $margin margine (default 4)
     * @param int $level 0,1,2,3 valori ammessi (default 0)
     * @param string $saveandprint se true salva e stampa (non usato)
     * @return void
     */
    static function getAttachmentByQrcode($text, $name, $size = 3, $margin = 4, $level = 0, $saveandprint = false) {
        $content = QrcodeUtility::getQrcodeContent($text, $size, $margin, $level, $saveandprint);
        $attachmentBS = new AttachmentBS();
        $attachment = $attachmentBS->instance();
        $attachment['Attachment']['name'] = (!empty($name) ? $name : FileUtility::uuid_medium());
        $attachment['Attachment']['size'] = FileUtility::getSizeByContent($content, "png"); //bytes
        $attachment['Attachment']['ext'] = "png";
        $attachment['Attachment']['mimetype'] = "image/png";
        $attachment['Attachment']['content'] = $content;
        $attachment['Attachment']['tpattachment'] = EnumAttachmentType::IMAGE;
        return $attachment;
    }
}