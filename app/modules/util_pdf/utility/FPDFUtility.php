<?php
App::uses("HtmlUtility", "modules/coreutils/utility");
App::uses("StringUtility", "modules/coreutils/utility");
App::uses("MisureUtility", "modules/coreutils/utility");
App::uses("MathUtility", "modules/coreutils/utility");
App::uses("TranslatorUtility", "modules/cakeutils/utility");
App::uses('PageNoModel', 'modules/util_pdf/classes');

class FPDFUtility {

    static function setPageNo(PageNoModel $page_no, $fpdf) {
        $color_array = HtmlUtility::getColorRGBByHex($page_no->text_color);
        $fpdf->SetTextColor($color_array['r'], $color_array['g'], $color_array['b']);
        $fpdf->SetFont($page_no->text_font, $page_no->text_type, $page_no->text_size);
        if ($page_no->position == "bottom") {
            $page_no->height = 21;
            $fpdf->SetY($page_no->size_height - $page_no->height);
            // $this->pdf->SetY(-21);
        }
        if ($page_no->position == "top") {
            $fpdf->SetY($page_no->height);
        }
        if ($page_no->page_tot) {
            $fpdf->Cell(0, 0, TranslatorUtility::__translate("PDF_PAGE", "pdf") . ' ' . $fpdf->PageNo() . '/{nb}', 0, 0, $page_no->align);
        } else {
            $fpdf->Cell(0, 0, TranslatorUtility::__translate("PDF_PAGE", "pdf") . ' ' . $fpdf->PageNo(), 0, 0, $page_no->align);
        }
    }

    static function getTextPageNo($fpdf, $flgTot = false, $separator = "/", $flgPrefix = true, $prefix = "") {
        if ($flgTot) {
            return ($flgPrefix ? ($prefix ? $prefix : TranslatorUtility::__translate("PDF_PAGE", "pdf")) . " " : "") . $fpdf->PageNo() . $separator . '{nb}';
        } else {
            return ($flgPrefix ? ($prefix ? $prefix : TranslatorUtility::__translate("PDF_PAGE", "pdf")) . " " : "") . $fpdf->PageNo();
        }
    }

    static function calcImageDim($url, &$w, &$h, $flgpixel) {
        $emptyW = MathUtility::isEmptyDecimal($w) || MathUtility::isEmptyNumber($w);
        $emptyH = MathUtility::isEmptyDecimal($h) || MathUtility::isEmptyNumber($h);
        if ($flgpixel && $emptyW && !$emptyH) {
            $w = MisureUtility::convertImageSizeByHeight($url, $h);
        } elseif ($flgpixel && !$emptyW && $emptyH) {
            $h = MisureUtility::convertImageSizeByWidth($url, $w);
        } elseif (!$flgpixel && $emptyW && !$emptyH) {
            $hPx = MisureUtility::convertToPixel(EnumMisureArray::$misure['millimeter'], $h);
            $wPx = MisureUtility::convertImageSizeByHeight($url, $hPx);
            $w = MisureUtility::convertPixelToMisure(EnumMisureArray::$misure['millimeter'], $wPx);
        } elseif (!$flgpixel && !$emptyW && $emptyH) {
            $wPx = MisureUtility::convertToPixel(EnumMisureArray::$misure['millimeter'], $w);
            $hPx = MisureUtility::convertImageSizeByWidth($url, $wPx);
            $h = MisureUtility::convertPixelToMisure(EnumMisureArray::$misure['millimeter'], $hPx);
        }
    }

    static function position($fpdf, $x = null, $y = null) {
        if (!empty($x)) {
            $fpdf->SetX($x);
        }
        if (!empty($y)) {
            $fpdf->SetY($y);
        }
    }

    static function writeMultipleLines($fpdf, $text, $initX, $w, $h, $maxLenght, $border = 0, $ln = 0, $align = 'L') {
        if (strlen($text) <= $maxLenght) {
            $fpdf->Cell($w, $h, $text, $border, $ln, $align);
        } else {
            $array_row = StringUtility::splitTextWithWordWrap($text, $maxLenght);
            $cnt = 0;
            foreach ($array_row as $row) {
                if ($cnt > 0) {
                    $fpdf->Ln($h);
                }
                $fpdf->setX($initX);
                $fpdf->Cell($w, $h, $row, $border, $ln, $align);
                $cnt++;
            }
        }
    }

    static function writeImage($fpdf, $webrootUrl, $x, $y, $w, $h, $flgpixel = false) {
        FPDFUtility::calcImageDim(WWW_ROOT . $webrootUrl, $w, $h, $flgpixel);
        if ($flgpixel) {
            /*
             * CONVERSION EXAMPLE FROM WIDTH 2147px TO 792px
             * image (2147 * 823)
             * 792 : 2147 = h : 823
             * h=(792*823)/2147 = 303,5938518863531
             */
            $w = MisureUtility::convertPixelToMisure(EnumMisureArray::$misure['millimeter'], $w); // 792
            $h = MisureUtility::convertPixelToMisure(EnumMisureArray::$misure['millimeter'], $h); // 303.6
        }
        $fpdf->Image(WWW_ROOT . $webrootUrl, $x, $y, $w, $h);
    }

    static function line($fpdf, $top, $background = null, $h = 0.2, $w = 190) {
        $fpdf->Ln($top);
        if (!empty($background)) {
            $color_array = HtmlUtility::getColorRGBByHex($background);
            $fpdf->SetFillColor($color_array['r'], $color_array['g'], $color_array['b']);
        }
        $fpdf->Cell($w, $h, "", 1, 0, "C", ((!empty($background) && !empty($color_array)) ? true : false));
    }

    static function text($fpdf, $text_color = "#000", $text_size = "12", $text_font = "Arial", $text_type = "") {
        $color_array = HtmlUtility::getColorRGBByHex($text_color);
        $fpdf->SetTextColor($color_array['r'], $color_array['g'], $color_array['b']);
        $fpdf->SetFont($text_font, $text_type, $text_size);
    }

    static function writeBarred($fpdf, $txt, $top, $left, $w, $h, $align = "L", $border = false, $background = null) {
        $x1 = $fpdf->GetX();
        $y1 = $fpdf->GetY() + ($h / 2);
        FPDFUtility::write($fpdf, $txt, $top, $left, $w, $h, $align, $border, $background);
        $x2 = $fpdf->GetX();
        $fpdf->Line($x1, $y1, $x2, $y1);
    }

    static function write($fpdf, $txt, $top, $left, $w, $h, $align = "L", $border = false, $background = null) {
        $fpdf->Ln($top);
        $fpdf->SetX($left);
        $color_array = null;
        if (!empty($background)) {
            $color_array = HtmlUtility::getColorRGBByHex($background);
            $fpdf->SetFillColor($color_array['r'], $color_array['g'], $color_array['b']);
        }
        $fpdf->Cell($w, $h, $txt, (is_string($border) ? $border : ($border ? 1 : 0)), 0, $align, ((!empty($background) && !empty($color_array)) ? true : false));
    }
}