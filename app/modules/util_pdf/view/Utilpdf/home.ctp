<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Modulo pdf</h4>
</div>

<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Conversione pdf in allegato</h4>
  	<footer class="blockquote-footer"><cite title="Source Title">Test di chiamata PdfUtility::getAttachmentByPdf</cite></footer>
	<hr/>
    <div class="mt-2">
    	<div class="pt-1">
    		<?php debug($obj);?>
    	</div>
    </div>
</div>

<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Examples pdf</h4>

  	<div class="mt-2">
		<a class="btn btn-primary btn-sm" href="<?php echo Router::url(array('controller' => 'utilpdf', 'action' => 'createPdf')) ?>" target="_blank">
			VEDI UN PDF
		</a>
	</div>
	<div class="mt-2">
		<a class="btn btn-primary btn-sm" href="<?php echo Router::url(array('controller' => 'utilpdf', 'action' => 'createPdf', "?" => array("border" => 1))) ?>" target="_blank">
			VEDI UN PDF CON BORDI
		</a>
	</div>
	<div class="mt-2">
		<a class="btn btn-primary btn-sm" href="<?php echo Router::url(array('controller' => 'utilpdf', 'action' => 'createPdfNumbers')) ?>" target="_blank">
			VEDI UN PDF CON NUMERI DI PAGINA (default on top)
		</a>
	</div>
	<div class="mt-2">
		<a class="btn btn-primary btn-sm" href="<?php echo Router::url(array('controller' => 'utilpdf', 'action' => 'createPdfNumbers', "?" => array("position" => "B"))) ?>" target="_blank">
		VEDI UN PDF CON NUMERI DI PAGINA on bottom
		</a>
	</div>
	<div class="mt-2">
	<h4><strong><i>Possibile solo se è integrato il modulo util_printcodes</i></strong></h4>
		<a class="btn btn-primary btn-sm" href="<?php echo Router::url(array('controller' => 'utilpdf', 'action' => 'createPrintcodes')) ?>" target="_blank">
			VEDI UN PDF CON QRCODE E BARCODE
		</a>
	</div>
</div>


<hr/>

<div class="mt-2">
	<a href="<?php echo Router::url(array('controller' => 'examples', 'action' => 'home')) ?>">
		<span class="fa fa-arrow-left"></span>
	</a>
</div>