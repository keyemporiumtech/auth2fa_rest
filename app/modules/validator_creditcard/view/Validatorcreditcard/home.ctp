<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Modulo validator_creditcard</h4>
</div>

<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Tipi di carte di credito</h4>
	<footer class="blockquote-footer"><cite title="Source Title">Legge il formato di una carta di credito</cite></footer>
	<hr/>
    <div class="mt-2">
    	<div class="pt-1">
    		<form action="<?php echo Router::url(array('controller' => 'validatorcreditcard', 'action' => 'type')) ?>" method="post" class="mt-2">
		    	<div class="form-group">
				    <label for="cod">Codice Tipo carta</label>
				    <input type="text" class="form-control" id="cod" name="cod" value="">
				</div>
				<button type="submit" class="btn btn-primary">Invia</button>
		    </form>
    	</div>
    </div>
</div>

<hr/>

<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Validazione carta di credito</h4>
	<footer class="blockquote-footer"><cite title="Source Title">Valida il formato di una carta di credito</cite></footer>
	<hr/>
    <div class="mt-2">
    	<div class="pt-1">
    		<form action="<?php echo Router::url(array('controller' => 'validatorcreditcard', 'action' => 'creditcard')) ?>" method="post" class="mt-2">
		    	<div class="form-group">
				    <label for="number">Numero</label>
				    <input type="text" class="form-control" id="number" name="number" value="">
				</div>
				<div class="form-group row">
				    <label>Data di scadenza</label>
					<div class="col-2">
						<label for="expireMM">Mese</label>
						<input type="text" class="form-control" id="expireMM" name="expireMM" value="">
					</div>
				    <div class="col-2">
						<label for="expireYY">Anno</label>
						<input type="text" class="form-control" id="expireYY" name="expireYY" value="">
					</div>
					<div class="col-2">
						<label for="cvc">CVC/CVV/CVC2</label>
						<input type="text" class="form-control" id="cvc" name="cvc" value="">
					</div>
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