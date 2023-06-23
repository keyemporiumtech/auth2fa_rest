<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Validazione IBAN</h4>

<?php
foreach ($ibans as $cod => $iban) {
    echo "<strong>{$cod}</strong><br/>" . json_encode($iban) . "<br/>";
    echo "<strong>FORMAT</strong><br/>" . $iban->printFormat("<br/>") . "<hr/>";
}
?>
<hr/>

<?php if (count($invalids) > 0) {?>
<h5>INVALIDS</h5>
<?php
foreach ($invalids as $invalid) {
    echo "<strong>{$invalid}</strong><br/>";
}?>
<hr/>
<?php }?>

</div>

<div class="mt-2">
	<a href="<?php echo Router::url(array('controller' => 'validatoriban', 'action' => 'home')) ?>">
		<span class="fa fa-arrow-left"></span>
	</a>
</div>