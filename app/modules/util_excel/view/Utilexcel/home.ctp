<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Modulo excel</h4>
</div>

<!--
<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Conversione pdf in allegato</h4>
  	<footer class="blockquote-footer"><cite title="Source Title">Test di chiamata PdfUtility::getAttachmentByPdf</cite></footer>
	<hr/>
    <div class="mt-2">
    	<div class="pt-1">
    		<?php // debug($obj);?>
    	</div>
    </div>
</div>
-->

<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Examples excel</h4>

  	<div class="mt-2">
		<a class="btn btn-primary btn-sm" href="<?php echo Router::url(array('controller' => 'utilexcel', 'action' => 'createExcel')) ?>" target="_blank">
			VEDI UN EXCEL
		</a>
	</div>	
</div>


<hr/>

<div class="mt-2">
	<a href="<?php echo Router::url(array('controller' => 'examples', 'action' => 'home')) ?>">
		<span class="fa fa-arrow-left"></span>
	</a>
</div>