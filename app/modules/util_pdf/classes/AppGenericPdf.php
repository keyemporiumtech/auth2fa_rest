<?php
App::uses('PdfUtility', 'modules/util_pdf/utility');

abstract class AppGenericPdf {
    public $TRANSLATOR_FILE = null;
    public $MAX_CHARS_LN = null;
    public $pdf = null;
    public $font = "Times";
    public $space = 5;
    public $border = false;
    public $clean = array(); // va riempito dei file generati e utilizzati in pdf (immagini)

    function __construct() {
        $this->TRANSLATOR_FILE = $this->getTranslatorFile();
        $this->MAX_CHARS_LN = $this->getMaxCharsLn();
    }

    // abstract
    abstract public function getTranslatorFile();
    abstract public function getMaxCharsLn();

    // operations
    function preview() {
        $this->pdf->preview();
        $this->cleanFiles();
    }
    function download() {
        $this->pdf->download();
        $this->cleanFiles();
    }
    function save($webroot = null, $name = null) {
        $this->pdf->save($webroot, $name);
        $this->cleanFiles();
    }
    function cleanFiles() {
        foreach ($this->clean as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }
    }

    // utils
    function addCleanFile($url) {
        if (!in_array($url, $this->clean)) {
            array_push($this->clean, $url);
        }
    }
    function translate($key, $params = null) {
        if (empty($params)) {
            return TranslatorUtility::__translate($key, $this->TRANSLATOR_FILE);
        }
        return TranslatorUtility::__translate_args($key, $params, $this->TRANSLATOR_FILE);
    }

    function write($txt, $top, $left, $w, $h, $align = "L", $border = false, $background = null, $maxLn = null) {
        if (empty($maxLn)) {
            $maxLn = $this->MAX_CHARS_LN;
        }
        if (strlen($txt) > $maxLn) {
            $arr = PdfUtility::splitText($txt, $maxLn);
            $topLine = $top;
            foreach ($arr as $line) {
                $this->pdf->write($line, $topLine, $left, $w, $h, $align, $border, $background);
                $topLine = $h;
            }
        } else {
            $this->pdf->write($txt, $top, $left, $w, $h, $align, $border, $background);
        }
    }

    /**
     * ritorna un array con le informazioni di un testo splittato su più righe.
     * In particolare ritorna
     * - il testo splittato in un array
     * - l'altezza ricalcolata della cella
     * - il numero di righe
     *
     * @param  string $txt testo da splittare
     * @param  int $h altezza di una singola riga
     * @param  int $maxLn numero massimo di caratteri contenuti in una riga
     * @return mixed|array array con le informazioni di un testo splittato su più righe.
     */
    function getDimensionsForCellRow($txt, $h, $maxLn = null) {
        if (empty($maxLn)) {
            $maxLn = $this->MAX_CHARS_LN;
        }
        $arr = array();
        if (strlen($txt) > $maxLn) {
            $arr = PdfUtility::splitText($txt, $maxLn);
        } else {
            $arr = array($txt);
        }
        return array(
            "text" => $arr,
            "h" => $h * count($arr),
            "numrows" => count($arr),
        );
    }

    function writeBorderCellForArray($arr = array(), $top, $left, $w, $h, $align = "L", $background = null) {
        for ($i = 0; $i < count($arr); $i++) {
            $txt = $arr[$i];
            $border = "";
            if ($i == 0) {
                $border = "TLR";
            } elseif ($i == (count($arr) - 1)) {
                $border = "BLR";
            } else {
                $border = "LR";
            }

            $this->pdf->write($txt, $i == 0 ? 0 : $top, $left, $w, $h, $align, $border, $background);
        }
    }
}
