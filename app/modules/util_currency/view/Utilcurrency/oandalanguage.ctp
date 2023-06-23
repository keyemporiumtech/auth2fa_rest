<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Modulo currency - Plugin Oanda - Manager</h4>
</div>

<div class="alert alert-dark mt-1" role="alert">
	<footer class="blockquote-footer">ManagerOANDA::translate() <cite title="Source Title">Traduce il nome di una valuta in lingua</cite></footer>

	<hr/>
	<div class="mt-2">
    	<div class="pt-1">
    		<form action="<?php echo Router::url(array('controller' => 'utilcurrency', 'action' => 'oandalanguage')) ?>" method="post" class="mt-2">
		    	<div class="form-group">
					<label for="curr1">Valuta</label>
				    <input type="text" class="form-control" id="currency" name="currency" value="<?php echo $currency ?>">
					<label for="curr2">Converti in</label>
				    <input type="text" class="form-control" id="language" name="language" value="<?php echo $language ?>">
				</div>
				<button type="submit" class="btn btn-primary">Invia</button>
		    </form>
    	</div>
    </div>
  	<hr/>
    <div class="mt-2">
    	<div class="pt-1">
    		<?php debug($currencyObject)?>
    	</div>
		<div class="pt-1">
    		<?php debug($currencyName)?>
    	</div>
    </div>
</div>

<div class="alert alert-dark mt-1" role="alert">
	<footer class="blockquote-footer">ManagerOANDA::currencies() <cite title="Source Title">Ritorna tutte le valute per la lingua scelta</cite></footer>
	<hr/>
    <div class="mt-2">
    	<div class="pt-1">
    		<?php debug($currencies)?>
    	</div>
    </div>
</div>

<div class="mt-2">
	<a href="<?php echo Router::url(array('controller' => 'utilcurrency', 'action' => 'pluginOanda')) ?>">
		<span class="fa fa-arrow-left"></span>
	</a>
</div>
