<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Modulo currency - Plugin Oanda</h4>
</div>

<div class="alert alert-dark mt-1" role="alert">
	<h4 class="alert-heading">Servizio REST</h4>
	<footer class="blockquote-footer">CHIAMATE ESTERNE <cite title="Source Title">https://fxds-public-exchange-rates-api.oanda.com/cc-api/currencies</cite></footer>
  	<hr/>
    <div class="mt-2">
    	<div class="pt-1">
    		<a href="<?php echo Router::url(array('controller' => 'utilcurrency', 'action' => 'oandarest')) ?>">
				Esempio di chiamata e di risposta
			</a>
    	</div>
    </div>
</div>

<div class="alert alert-dark mt-1" role="alert">
	<h4 class="alert-heading">Manager</h4>
	<footer class="blockquote-footer">GESTIONE IN CACHE GIORNALIERA <cite title="Source Title">ManagerOANDA</cite></footer>
  	<hr/>
    <div class="mt-2">
    	<div class="pt-1">
    		<a href="<?php echo Router::url(array('controller' => 'utilcurrency', 'action' => 'oandamanager')) ?>">
				Manager OANDA
			</a>
    	</div>
    </div>
</div>

<div class="alert alert-dark mt-1" role="alert">
	<h4 class="alert-heading">Translator</h4>
	<footer class="blockquote-footer">Traduzione dei nomi delle valute <cite title="Source Title">ManagerOANDA</cite></footer>
  	<hr/>
    <div class="mt-2">
    	<div class="pt-1">
    		<a href="<?php echo Router::url(array('controller' => 'utilcurrency', 'action' => 'oandalanguage')) ?>">
				Manager OANDA - translate
			</a>
    	</div>
    </div>
</div>

<div class="mt-2">
	<a href="<?php echo Router::url(array('controller' => 'utilcurrency', 'action' => 'home')) ?>">
		<span class="fa fa-arrow-left"></span>
	</a>
</div>