<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Modulo work_cv</h4>
</div>

<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Examples pdf</h4>

  	<div class="mt-2">
		<a class="btn btn-primary btn-sm" href="<?php echo Router::url(array('controller' => 'workcv', 'action' => 'createPdf')) ?>" target="_blank">
			VEDI UN PDF
		</a>
	</div>
	<div class="mt-2">
		<a class="btn btn-primary btn-sm" href="<?php echo Router::url(array('controller' => 'workcv', 'action' => 'cvProfession')) ?>" target="_blank">
			VEDI UN PDF di una professione
		</a>
	</div>
</div>


<hr/>

<div class="mt-2">
	<a href="<?php echo Router::url(array('controller' => 'examples', 'action' => 'home')) ?>">
		<span class="fa fa-arrow-left"></span>
	</a>
</div>