<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Validazione Carta di credito</h4>

<?php
if (!empty($cc)) {
    echo "<strong>RESULT</strong><br/>" . json_encode($cc) . "<br/>";
}
?>
<hr/>

<?php if (!empty($message)) {?>
<h5>INVALIDS</h5>
<?php
echo "<strong>{$message}</strong><br/>";
    ?>
<hr/>
<?php }?>

</div>

<div class="mt-2">
	<a href="<?php echo Router::url(array('controller' => 'validatorcreditcard', 'action' => 'home')) ?>">
		<span class="fa fa-arrow-left"></span>
	</a>
</div>