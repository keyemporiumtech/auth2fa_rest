<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Formato IBAN</h4>

<?php foreach ($ibans as $cod => $iban) {
    echo "<strong>{$cod}</strong><br/>" . json_encode($iban) . "<hr/>";
}?>

</div>

<div class="mt-2">
	<a href="<?php echo Router::url(array('controller' => 'validatoriban', 'action' => 'home')) ?>">
		<span class="fa fa-arrow-left"></span>
	</a>
</div>