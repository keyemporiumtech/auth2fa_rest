<div class="alert alert-success mt-1" role="alert">
	<h4 class="alert-heading">Gestione dei gruppi</h4>
</div>

<div class="alert alert-dark mt-1" role="alert">
  	<h4 class="alert-heading">Lista GRP1 prima</h4>
</div>

<div class="ml-5 text-success font-weight-bold"><?php debug($listGRP1_PRE)?></div>

<div class="alert alert-dark mt-1" role="alert">
  	<h4 class="alert-heading">Lista GRP2 prima</h4>
</div>

<div class="ml-5 text-success font-weight-bold"><?php debug($listGRP2_PRE)?></div>

<div class="alert alert-dark mt-1" role="alert">
  	<h4 class="alert-heading">Esito della modifica rimozione entity da GRP1</h4>
	<footer class="blockquote-footer">groupsdel= <cite title="Source Title">["GRP1"]</cite></footer>
</div>

<div class="alert alert-dark mt-1" role="alert">
  	<h4 class="alert-heading">Lista GRP1 dopo</h4>
</div>

<div class="ml-5 text-success font-weight-bold"><?php debug($listGRP1_POST)?></div>

<div class="alert alert-dark mt-1" role="alert">
  	<h4 class="alert-heading">Lista GRP2 dopo</h4>
</div>

<div class="ml-5 text-success font-weight-bold"><?php debug($listGRP2_POST)?></div>

<div class="mt-2">
	<a href="<?php echo Router::url(array('controller' => 'cakeutils', 'action' => 'genericGroups')) ?>">
		<span class="fa fa-arrow-left"></span>
	</a>
</div>