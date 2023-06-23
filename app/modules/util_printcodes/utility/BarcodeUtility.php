<?php
App::uses('BarcodeGenerator', 'modules/util_printcodes/plugin/Barcode');
App::uses('BarcodeGeneratorPNG', 'modules/util_printcodes/plugin/Barcode');
App::uses('BarcodeGeneratorSVG', 'modules/util_printcodes/plugin/Barcode');
App::uses('BarcodeGeneratorJPG', 'modules/util_printcodes/plugin/Barcode');
App::uses('BarcodeGeneratorHTML', 'modules/util_printcodes/plugin/Barcode');
App::uses('EnumBarcodeType', 'modules/util_printcodes/plugin/Barcode/Enum');
App::uses("FileUtility", "modules/coreutils/utility");
App::uses("StringUtility", "modules/coreutils/utility");
App::uses("AttachmentBS", "modules/resources/business");
App::uses("EnumAttachmentType", "modules/resources/config");
App::uses("MimetypeBS", "modules/resources/business");
App::uses("HtmlUtility", "modules/coreutils/utility");

class BarcodeUtility {

    /**
     * Converte un testo in immagine barcode png e la salva sul filesystem
     *
     * @param string $text testo da convertire in barcode
     * @param string $path path in cui salvare il barcode (se è vuoto salva in tmp)
     * @param string $name nome del file barcode (se vuoto è generato)
     * @param  string $ext estensione richiesta (ammesse png, svg e jpg - di default png)
     * @param  string $type tipo di barcode (default C128)
     * @param  int $widthLine dimensione massima in pixel di una riga barcode (default 2)
     * @param  int $heightBar altezza massima in pixel di una riga barcode (default 30)
     * @param  string $color colore del barcode (default #000)
     * @return string Ritorna il path dell'immagine barcode
     */
    static function getBarcodeImage($text, $path = null, $name = null, $ext = "png", $type = 'C128', $widthLine = 2, $heightBar = 30, $color = "#000") {
        $barcode = null;
        switch ($ext) {
        case "png":
            $barcode = new BarcodeGeneratorPNG();
            $rgb = HtmlUtility::getColorRGBByHex($color);
            $color = array($rgb['r'], $rgb['g'], $rgb['b']);
            break;
        case "svg":
            $barcode = new BarcodeGeneratorSVG();
            break;
        case "jpg":
            $barcode = new BarcodeGeneratorJPG();
            $rgb = HtmlUtility::getColorRGBByHex($color);
            $color = array($rgb['r'], $rgb['g'], $rgb['b']);
            break;
        default:
            $ext = "png";
            $barcode = new BarcodeGeneratorPNG();
            $rgb = HtmlUtility::getColorRGBByHex($color);
            $color = array($rgb['r'], $rgb['g'], $rgb['b']);
            break;
        }
        $tmp_name = (!empty($name) ? $name : FileUtility::uuid_medium());
        $filename = (StringUtility::contains($tmp_name, ".{$ext}") ? $tmp_name : $tmp_name . ".{$ext}");
        if (empty($path)) {
            $path = WWW_ROOT . "tmp/";
        }
        $path .= $filename;
        file_put_contents($path, $barcode->getBarcode($text, $type, $widthLine, $heightBar, $color));
        return $path;
    }

    /**
     * Ritorna il content di un barcode
     *
     * @param string $text testo da convertire in barcode
     * @param  string $ext estensione richiesta (ammesse png, svg e jpg - di default png)
     * @param  string $type tipo di barcode (default C128)
     * @param  int $widthLine dimensione massima in pixel di una riga barcode (default 2)
     * @param  int $heightBar altezza massima in pixel di una riga barcode (default 30)
     * @param  string $color colore del barcode (default #000)
     * @return string Ritorna il contenuto in base64 del barcode
     */
    static function getBarcodeContent($text, $ext = "png", $type = 'C128', $widthLine = 2, $heightBar = 30, $color = "#000") {
        $path = BarcodeUtility::getBarcodeImage($text, null, null, $ext, $type, $widthLine, $heightBar, $color);
        $content = FileUtility::getBaseContentByPath($path);
        unlink($path);
        return $content;
    }

    /**
     * Ritorna l'html di un barcode
     *
     * @param string $text testo da convertire in barcode
     * @param  string $type tipo di barcode (default C128)
     * @param  int $widthLine dimensione massima in pixel di una riga barcode (default 2)
     * @param  int $heightBar altezza massima in pixel di una riga barcode (default 30)
     * @param  string $color colore del barcode (default #000)
     * @return string Ritorna il codice html del barcode generato
     */
    static function getBarcodeHtml($text, $type = 'C128', $widthLine = 2, $heightBar = 30, $color = "#000") {
        $barcode = new BarcodeGeneratorHTML();
        return $barcode->getBarcode($text, $type, $widthLine, $heightBar, $color);
    }

    /**
     * Ritorna una entity Attachment con le informazioni del barcode generato
     *
     * @param string $text testo da convertire in barcode
     * @param string $name nome del file barcode (se vuoto è generato)
     * @param  string $ext estensione richiesta (ammesse png, svg e jpg - di default png)
     * @param  string $type tipo di barcode (default C128)
     * @param  int $widthLine dimensione massima in pixel di una riga barcode (default 2)
     * @param  int $heightBar altezza massima in pixel di una riga barcode (default 30)
     * @param  string $color colore del barcode (default #000)
     * @return mixed Ritorna una entity di tipo Attachment con le informazioni del barcode
     */
    static function getAttachmentByBarcode($text, $name, $ext = "png", $type = 'C128', $widthLine = 2, $heightBar = 30, $color = "#000") {
        $content = BarcodeUtility::getBarcodeContent($text, $ext, $type, $widthLine, $heightBar, $color);
        $attachmentBS = new AttachmentBS();
        $attachment = $attachmentBS->instance();
        $attachment['Attachment']['name'] = (!empty($name) ? $name : FileUtility::uuid_medium());
        $attachment['Attachment']['size'] = FileUtility::getSizeByContent($content, $ext); //bytes
        $attachment['Attachment']['ext'] = $ext;

        $mimetypeBS = new MimetypeBS();
        $mimetypeBS->acceptNull;
        $mimetypeBS->addCondition("ext", $ext);
        $mimetype = $mimetypeBS->unique();

        $attachment['Attachment']['mimetype'] = !empty($mimetype) ? $mimetype['Mimetype']['value'] : null;
        $attachment['Attachment']['content'] = $content;
        $attachment['Attachment']['tpattachment'] = EnumAttachmentType::IMAGE;
        return $attachment;
    }
}