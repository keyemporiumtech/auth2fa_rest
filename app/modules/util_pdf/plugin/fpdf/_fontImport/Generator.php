<?php
require(getcwd().'/../../vendors/fpdf/makefont/makefont.php');
if(empty($PATH_TO_SAVE)){
	$PATH_TO_SAVE = getcwd().'/../../vendors/fpdf/_fontImport/';
}
if(empty($EXT)){
	$EXT=".ttf";
}
MakeFontPath(getcwd().'/../../vendors/fpdf/_fontImport/'.$NOME_FONT.".ttf",'cp1250',$PATH_TO_SAVE);
/*
 * ENCODING 
cp1250 (Central Europe)
cp1251 (Cyrillic)
cp1252 (Western Europe)
cp1253 (Greek)
cp1254 (Turkish)
cp1255 (Hebrew)
cp1257 (Baltic)
cp1258 (Vietnamese)
cp874 (Thai)
ISO-8859-1 (Western Europe)
ISO-8859-2 (Central Europe)
ISO-8859-4 (Baltic)
ISO-8859-5 (Cyrillic)
ISO-8859-7 (Greek)
ISO-8859-9 (Turkish)
ISO-8859-11 (Thai)
ISO-8859-15 (Western Europe)
ISO-8859-16 (Central Europe)
KOI8-R (Russian)
KOI8-U (Ukrainian)
 */
?>
