<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Formato Carta di credito</h4>

<?php foreach ($types as $cod => $cc) {
    echo "<strong>{$cod}</strong><br/>" . json_encode($cc) . "<hr/>";
}?>

</div>

<div class="mt-2">
	<a href="<?php echo Router::url(array('controller' => 'validatorcreditcard', 'action' => 'home')) ?>">
		<span class="fa fa-arrow-left"></span>
	</a>
</div>