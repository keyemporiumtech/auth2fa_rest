<?php

/**
 * Description of PdfElement
 *
 * @author Giuseppe
 */
class PdfElement {
    public $orientation = "P"; //P or L
    public $unit = "mm";
    public $size = "a4"; // a3(297 × 420 mm) , a4(210 x 297 mm) , a5(148 × 210 mm)
    public $size_width = "210";
    public $size_height = "297";
    //Text
    public $font_family = null; //FONT-FAMILY: Courier, Helvetica, Arial, Times, Symbol, ZapfDingbats
    public $font_type = null; //FONT-TYPE : B (Bold), I (Italic), U (Underline)
    public $font_size = null;
    public $text_color = null;
    public $FPDF = null;
    //DOCUMENTATION TO : /vendors/fpdf/doc/index.htm

    //function __construct($orientation = "P", $unit = "mm", $size = "a4") {

    /**
     * costruisce un elemento PDF che contiene un oggetto FPDF
     *
     * @param  string $orientation P (VERTICALE) L (ORIZZONTALE)
     * @param  string $unit unità di misura (default mm)
     * @param  string $size dimensione del foglio (a3, a4 o a5)
     * @param  FPDF $fpdf oggetto fpdf
     */
    function __construct($orientation = null, $unit = null, $size = null, $fpdf = null) {
        if (!empty($orientation)) {
            $this->orientation = $orientation;
        }
        if (!empty($unit)) {
            $this->unit = $unit;
        }
        if (!empty($size)) {
            $this->size = $size;
        }
        $this->buildSizeUnit();
        $this->FPDF = $this->createPdf($fpdf);
    }

    public function buildSizeUnit() {
        switch ($this->size) {
        case "a3":
            if ($this->orientation == "P") {
                $this->size_width = $this->getDimension("297");
                $this->size_height = $this->getDimension("420");
            } else if ($this->orientation == "L") {
                $this->size_width = $this->getDimension("420");
                $this->size_height = $this->getDimension("297");
            }
            break;
        case "a4":
            if ($this->orientation == "P") {
                $this->size_width = $this->getDimension("210");
                $this->size_height = $this->getDimension("297");
            } else if ($this->orientation == "L") {
                $this->size_width = $this->getDimension("297");
                $this->size_height = $this->getDimension("210");
            }
            break;
        case "a5":
            if ($this->orientation == "P") {
                $this->size_width = $this->getDimension("148");
                $this->size_height = $this->getDimension("210");
            } else if ($this->orientation == "L") {
                $this->size_width = $this->getDimension("210");
                $this->size_height = $this->getDimension("148");
            }
            break;
        default:
            break;
        }
    }

    private function getDimension($dim) {
        if ($this->unit == "mm") {
            return $dim;
        } else {
            return null;
        }
    }

    public function createPdf($fpdf = null) {
        require_once ROOT . '/app/modules/util_pdf/plugin/fpdf/fpdf.php';
        if (empty($fpdf)) {
            $fpdf = new FPDF($this->orientation, $this->unit, $this->size);
        } else {
            $fpdf->orientation = $this->orientation;
            $fpdf->unit = $this->unit;
            $fpdf->size = $this->size;
        }
        $this->importFont($fpdf);
        $fpdf->AddPage();
        // $fpdf->AliasNbPages();
        return $fpdf;
    }

    /**
     * Importa i font aggiunti in vendors/fpdf/font
     * Questi font vengono generati da un tool in vendors/fpdf/_fontImport che, dato un file .tff ne genera 2 di tipo .php e .z
     * Bisogna quindi scaricare un tff per ogni tipologia (normal,bold,italic e bold+italic)
     * @param unknown $FPDF_OBJ
     */
    private function importFont($FPDF_OBJ) {
        $FPDF_OBJ->AddFont('Forte', '', 'Forte.php');
        //         $FPDF_OBJ->AddFont('Forte','B','Forte.php');
        //         $FPDF_OBJ->AddFont('Forte','BI','Forte.php');
        //         $FPDF_OBJ->AddFont('Forte','I','Forte.php');
        $FPDF_OBJ->AddFont('Tahoma', '', 'Tahoma.php');
        $FPDF_OBJ->AddFont('Tahoma', 'B', 'TahomaBold.php');
        $FPDF_OBJ->AddFont('Tahoma', 'BI', 'TahomaBoldItalic.php');
        $FPDF_OBJ->AddFont('Tahoma', 'I', 'TahomaItalic.php');
        $FPDF_OBJ->AddFont('Bernadette', '', 'bernadette_rough.php');
        $FPDF_OBJ->AddFont('Avenir', '', 'Avenir.php');
        $FPDF_OBJ->AddFont('Avenir', 'B', 'Avenirb.php');
        $FPDF_OBJ->AddFont('Avenir', 'BI', 'Avenirbi.php');
        $FPDF_OBJ->AddFont('Avenir', 'I', 'Aveniri.php');
    }
}
