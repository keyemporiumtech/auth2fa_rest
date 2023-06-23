<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Modulo currency - Plugin Banca d'Italia</h4>			   
</div>

<div class="alert alert-dark mt-1" role="alert">
	<h4 class="alert-heading">Servizio REST</h4>
	<footer class="blockquote-footer">CHIAMATE ESTERNE <cite title="Source Title">https://tassidicambio.bancaditalia.it/terzevalute-wf-web/rest/v1.0</cite></footer>
  	<hr/>
    <div class="mt-2">
    	<div class="pt-1">
    		<a href="<?php echo Router::url(array ('controller' => 'utilcurrency','action' => 'bilatest'))?>">
				Ultime valute con chiamata
			</a>	
    	</div>
    </div>	
</div>

<div class="alert alert-dark mt-1" role="alert">
	<h4 class="alert-heading">Manager</h4>
	<footer class="blockquote-footer">GESTIONE IN CACHE GIORNALIERA <cite title="Source Title">ManagerBI</cite></footer>
  	<hr/>
    <div class="mt-2">
    	<div class="pt-1">
    		<a href="<?php echo Router::url(array ('controller' => 'utilcurrency','action' => 'bimanager'))?>">
				Manager BI
			</a>	
    	</div>
    </div>	
</div>

<div class="mt-2">
	<a href="<?php echo Router::url(array ('controller' => 'utilcurrency','action' => 'home'))?>">
		<span class="fa fa-arrow-left"></span>
	</a>
</div>