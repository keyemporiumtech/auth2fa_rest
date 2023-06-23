<div class="alert alert-success mt-1" role="alert">
	<h4 class="alert-heading">Token generato</h4>
	<?php echo $token?>
</div>


<div class="mt-2">
	<a href="<?php echo Router::url(array ('controller' => 'authentication','action' => 'home'))?>">
		<span class="fa fa-arrow-left"></span>
	</a>
</div>