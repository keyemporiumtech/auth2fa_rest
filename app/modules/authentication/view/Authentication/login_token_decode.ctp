<div class="alert alert-success mt-1" role="alert">
	<h4 class="alert-heading">Verifica Token</h4>
	<?php echo $verify ? "VERIFICATO" : "NON VALIDO"?>
	<hr/>
	Data: <?php debug($loginObj)?>
	<br/>
	<?php
	if(array_key_exists("payload",$loginObj)){
		echo "iat:".date('d/m/Y H:i:s', $loginObj['payload']['iat'])."<br/>";
		echo "nbf:".date('d/m/Y H:i:s', $loginObj['payload']['nbf'])."<br/>";
		echo "exp:".date('d/m/Y H:i:s', $loginObj['payload']['exp'])."<br/>";
	}
	?>
</div>


<div class="mt-2">
	<a href="<?php echo Router::url(array ('controller' => 'authentication','action' => 'home'))?>">
		<span class="fa fa-arrow-left"></span>
	</a>
</div>