<?php
App::uses("AttachmentBS", "modules/resources/business");
App::uses("FileUtility", "modules/coreutils/utility");
App::uses("Mimetype", "Model");

class AttachmentUtility {

    /**
     * Ritorna un allegato dal path di un file riempiendo i campi calcolati (size, ext, name, mimetypes, content ...)
     * @param string $path percorso del file fisico
     * @param boolena $avoidContent se true non riempie il content in base64
     * @return type allegato (@see Attachment)
     */
    static function getObjByPath($path, $avoidContent = false, $entityname = "Attachment") {
        $attachmentBS = new AttachmentBS();
        $attachment = $attachmentBS->instance();
        $attachment[$entityname]['name'] = FileUtility::getNameByPath($path);
        $attachment[$entityname]['size'] = FileUtility::getSizeByPath($path); //bytes
        $attachment[$entityname]['ext'] = FileUtility::getExtensionByPath($path);
        $attachment[$entityname]['mimetype'] = FileUtility::getMimeTypeByPath($path);
        $attachment[$entityname]['content'] = $avoidContent ? null : FileUtility::getBaseContentByPath($path);
        return $attachment;
    }

    /**
     * Ritorna un allegato dalla url remota di un file riempiendo i campi calcolati (size, ext, name, mimetypes, content ...)
     * @param string $url url del file remoto
     * @param boolena $avoidContent se true evita di fornire il content in base64
     * @param string $entityname è l'alias (Attachment o attachment_fk)
     * @return type allegato (@see Attachment)
     */
    static function getObjByUrl($url, $avoidContent = false, $entityname = "Attachment") {
        $attachmentBS = new AttachmentBS();
        $attachment = $attachmentBS->instance();
        $attachment[$entityname]['name'] = FileUtility::getNameByPath($url);
        $attachment[$entityname]['size'] = FileUtility::getSizeByUrl($url); //bytes
        $attachment[$entityname]['ext'] = FileUtility::getExtensionByPath($url);
        $attachment[$entityname]['mimetype'] = FileUtility::getMimeTypeByUrl($url);
        $attachment[$entityname]['content'] = $avoidContent ? null : FileUtility::getBaseContentByPath($url);
        return $attachment;
    }

    static function replaceAttachmentUtils(&$toReplace, $toEval, $entityname = "Attachment") {
        if (empty($toReplace[$entityname]['name'])) {
            $toReplace[$entityname]['name'] = $toEval[$entityname]['name'];
        }
        if (empty($toReplace[$entityname]['size'])) {
            $toReplace[$entityname]['size'] = $toEval[$entityname]['size'];
        }
        if (empty($toReplace[$entityname]['ext'])) {
            $toReplace[$entityname]['ext'] = $toEval[$entityname]['ext'];
        }
        if (empty($toReplace[$entityname]['mimetype'])) {
            $toReplace[$entityname]['mimetype'] = $toEval[$entityname]['mimetype'];
        }
        if (empty($toReplace[$entityname]['content'])) {
            $toReplace[$entityname]['content'] = $toEval[$entityname]['content'];
        }
    }

    /**
     * Risolve automaticamente i campi calcolati (size, ext, name, mimetypes, content ...)
     * di un insieme di oggetti Attachment, leggendo dal path o dalla url se valorizzate.
     * Inoltre calcola il mimetype qualora non fosse settato
     * @param string $entityname nome della entity (Attachment o attachment_fk)
     * @param array $data insieme di oggetti Attachment
     * @param boolean $avoidContent se true evita di fornire il content in base64
     */
    static function setAttachmentMapping($entityname, &$data, $avoidContent = false) {
        foreach ($data as &$obj) {
            if (array_key_exists($entityname, $obj)) {
                // PATH
                if (!empty($obj[$entityname]['path'])) {
                    $objPath = AttachmentUtility::getObjByPath(WWW_ROOT . $obj[$entityname]['path'], $avoidContent, $entityname);
                    AttachmentUtility::replaceAttachmentUtils($obj, $objPath, $entityname);
                }

                // URL
                if (!empty($obj[$entityname]['url'])) {
                    $objUrl = AttachmentUtility::getObjByUrl($obj[$entityname]['url'], $avoidContent, $entityname);
                    AttachmentUtility::replaceAttachmentUtils($obj, $objUrl, $entityname);
                }

                if (!empty($obj[$entityname]['content'])) {
                    $obj[$entityname]['content'] = AttachmentUtility::cleanContent($obj[$entityname]['content']);
                }

                // MIMETYPE
                if (!empty($obj[$entityname]['ext']) && (empty($obj[$entityname]['mimetype']) || empty($obj[$entityname]['type']))) {
                    $mimetype = new Mimetype();
                    $mimetypeModel = $mimetype->find('first', array(
                        'conditions' => array(
                            'ext' => $obj[$entityname]['ext'],
                        ),
                    ));
                    if (!empty($mimetypeModel)) {
                        if (empty($obj[$entityname]['mimetype'])) {
                            $obj[$entityname]['mimetype'] = $mimetypeModel['Mimetype']['value'];
                        }
                        if (empty($obj[$entityname]['type'])) {
                            $obj[$entityname]['type'] = $mimetypeModel['Mimetype']['type'];
                        }
                    }
                }
            }
        }
    }

    /**
     * Memorizza un file leggendo il content nella cartella uploads
     * @param string $content content del file
     * @param string $name nome del file inclusivo dell'estensione
     * @param string $folder ulteriore ramificazione in sottocartelle di uploads
     */
    static function storeContent($content, $name, $folder = null) {
        $path = WWW_ROOT . "uploads";
        if (!empty($folder)) {
            $path .= "/{$folder}";
        }
        $path .= "/" . $name;
        FileUtility::createFileByContent($path, $content);
    }

    static function cleanContent($content) {
        return !empty($content) ? str_replace(" ", "+", $content) : "";
    }
}
