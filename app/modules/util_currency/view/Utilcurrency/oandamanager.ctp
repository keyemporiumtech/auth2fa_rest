<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Modulo currency - Plugin Oanda - Manager</h4>
</div>

<div class="alert alert-dark mt-1" role="alert">
	<footer class="blockquote-footer">ManagerOANDA::convert() <cite title="Source Title">Converte una valuta</cite></footer>

	<hr/>
	<div class="mt-2">
    	<div class="pt-1">
    		<form action="<?php echo Router::url(array('controller' => 'utilcurrency', 'action' => 'oandamanager')) ?>" method="post" class="mt-2">
		    	<div class="form-group">
				    <label for="rate">Importo</label>
				    <input type="text" class="form-control" id="rate" name="rate" value="<?php echo $rate ?>">
					<label for="curr1">Converti da</label>
				    <input type="text" class="form-control" id="curr1" name="curr1" value="<?php echo $curr1 ?>">
					<label for="curr2">Converti a</label>
				    <input type="text" class="form-control" id="curr2" name="curr2" value="<?php echo $curr2 ?>">
				</div>
				<button type="submit" class="btn btn-primary">Invia</button>
		    </form>
    	</div>
    </div>
  	<hr/>
    <div class="mt-2">
    	<div class="pt-1">
    		<?php debug($converted)?>
    	</div>
    </div>
</div>

<div class="mt-2">
	<a href="<?php echo Router::url(array('controller' => 'utilcurrency', 'action' => 'pluginOanda')) ?>">
		<span class="fa fa-arrow-left"></span>
	</a>
</div>