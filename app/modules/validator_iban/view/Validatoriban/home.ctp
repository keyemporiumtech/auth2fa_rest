<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Modulo validator_iban</h4>
</div>

<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Formati di IBAN</h4>
	<footer class="blockquote-footer"><cite title="Source Title">Legge il formato iban delle nazioni</cite></footer>
	<hr/>
    <div class="mt-2">
    	<div class="pt-1">
    		<form action="<?php echo Router::url(array('controller' => 'validatoriban', 'action' => 'format')) ?>" method="post" class="mt-2">
		    	<div class="form-group">
				    <label for="cod">Codice Iso nazione</label>
				    <input type="text" class="form-control" id="cod" name="cod" value="">
				</div>
				<button type="submit" class="btn btn-primary">Invia</button>
		    </form>
    	</div>
    </div>
</div>

<hr/>

<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Validazione di IBAN</h4>
	<footer class="blockquote-footer"><cite title="Source Title">Valida il formato di un iban per nazione</cite></footer>
	<hr/>
    <div class="mt-2">
    	<div class="pt-1">
    		<form action="<?php echo Router::url(array('controller' => 'validatoriban', 'action' => 'iban')) ?>" method="post" class="mt-2">
		    	<div class="form-group">
				    <label for="cod">Codice Iso nazione</label>
				    <input type="text" class="form-control" id="cod" name="cod" value="">
				</div>
				<div class="form-group">
				    <label for="iban">IBAN</label>
				    <input type="text" class="form-control" id="iban" name="iban" value="">
				</div>
				<button type="submit" class="btn btn-primary">Invia</button>
		    </form>
    	</div>
    </div>
</div>

<hr/>

<div class="mt-2">
	<a href="<?php echo Router::url(array('controller' => 'examples', 'action' => 'home')) ?>">
		<span class="fa fa-arrow-left"></span>
	</a>
</div>