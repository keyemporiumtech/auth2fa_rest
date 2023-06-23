<div class="alert alert-dark mt-1" role="alert">
  	<h4 class="alert-heading">Lista di elementi</h4>	
</div>

<div class="ml-5 text-success font-weight-bold"><?php debug($records, true, false) ?></div>

<div class="mt-2">
	<a href="<?php echo Router::url(array ('controller' => 'entitydb','action' => 'home'))?>">
		<span class="fa fa-arrow-left"></span>
	</a>
</div>