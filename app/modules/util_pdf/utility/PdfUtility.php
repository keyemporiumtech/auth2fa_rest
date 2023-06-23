<?php
App::uses("AttachmentBS", "modules/resources/business");
App::uses("FileUtility", "modules/coreutils/utility");
App::uses("SystemUtility", "modules/coreutils/utility");
App::uses("PdfUI", "modules/util_pdf/classes");
App::uses('PageNoModel', 'modules/util_pdf/classes');
App::uses('PdfStructureModel', 'modules/util_pdf/classes');

class PdfUtility {

    static function getInstance($webroot = "", $name = null, PdfStructureModel $structure = null, PageNoModel $page_number = null, $fpdf = null) {
        return new PdfUI($webroot, $name, $structure, $page_number, $fpdf);
    }

    static function getAttachmentByPdf(PdfUI $pdfUI) {
        $content = $pdfUI->getContent();
        $attachmentBS = new AttachmentBS();
        $attachment = $attachmentBS->instance();
        $attachment['Attachment']['name'] = $pdfUI->filename;
        $attachment['Attachment']['size'] = FileUtility::getSizeByContent($content, "pdf"); //bytes
        $attachment['Attachment']['ext'] = "pdf";
        $attachment['Attachment']['mimetype'] = "application/pdf";
        $attachment['Attachment']['content'] = $content;
        return $attachment;
    }

    static function writeQrcode($pdfUI, $x, $y, $w, $h, $text, $path = null, $name = null, $flgtxt = false, $rotate = 0) {
        if (!SystemUtility::checkModule("util_printcodes")) {
            throw new Exception("Il modulo util_printcodes non è integrato nell'applicazione. Impossibile stampare il qrcode");
        } else {
            App::uses("QrcodeUtility", "modules/util_printcodes/utility");
        }
        if (!empty($text)) {
            // TEMPLATE
            $qrcode = QrcodeUtility::getQrcodeImage($text, $path, $name);
            $pdfUI->pdf->Image($qrcode, $x, $y, $w, $h, 'png');
            //put text
            if ($flgtxt) {
                $pdfUI->pdf->Ln($h);
                $pdfUI->pdf->SetXY($x, ($y + $h));
                $pdfUI->pdf->SetFont('Times', '', 8);
                $pdfUI->pdf->Cell($w, 5, $text, 0, 0, 'C');
            }
        }
    }

    static function writeBarcode($pdfUI, $x, $y, $w, $h, $text, $path = null, $name = null, $flgtxt = false, $rotate = 0) {
        if (!SystemUtility::checkModule("util_printcodes")) {
            throw new Exception("Il modulo util_printcodes non è integrato nell'applicazione. Impossibile stampare il qrcode");
        } else {
            App::uses("BarcodeUtility", "modules/util_printcodes/utility");
        }
        if (!empty($text)) {
            $barcode = BarcodeUtility::getBarcodeImage($text, $path, $name);
            if (!empty($rotate)) {
                $pdfUI->pdf->RotatedImage($rotate, $barcode, $x, $y, $w, $h, 'png');
            } else {
                $pdfUI->pdf->Image($barcode, $x, $y, $w, $h, 'png');
            }

            //put text
            if ($flgtxt) {
                $pdfUI->pdf->Ln($h);
                $pdfUI->pdf->SetXY($x, ($y + $h));
                $pdfUI->pdf->SetFont('Times', '', 8);
                if (!empty($rotate)) {
                    $pdfUI->pdf->SetXY($x + $h, $y);
                    $new_x = $pdfUI->pdf->GetX();
                    $new_y = $pdfUI->pdf->GetY();
                    $pdfUI->pdf->RotatedCell($rotate, $new_x, $new_y, $w, 5, $text, 0, 0, 'C');
                } else {
                    $pdfUI->pdf->Cell($w, 5, $text, 0, 0, 'C');
                }
            }
        }
    }

    static function splitText($text, $length) {
        $arr = array();
        $elements = StringUtility::multiexplode($text, array("<br>", "\n", "<br/>"));
        foreach ($elements as $el) {
            if (strlen($el) <= $length) {
                array_push($arr, $el);
            } else {
                $tmp = StringUtility::splitTextWithWordWrap($el, $length);
                array_push($arr, ...$tmp);
            }
        }
        return $arr;
    }

}