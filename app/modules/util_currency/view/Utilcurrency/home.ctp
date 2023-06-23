<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Modulo currency</h4>
	<footer class="blockquote-footer">CONFIGURATIONS <cite title="Source Title">Utility currency</cite></footer>
	
	<hr/>
    <div class="mt-2">
    	<div class="pt-1">
    		<a href="<?php echo Router::url(array ('controller' => 'utilcurrency','action' => 'pluginBi'))?>">
				Plugin Banca d'Italia
			</a>	
    	</div>
    </div>
	<hr/>
    <div class="mt-2">
    	<div class="pt-1">
    		<a href="<?php echo Router::url(array ('controller' => 'utilcurrency','action' => 'pluginOanda'))?>">
				Plugin Oanda
			</a>	
    	</div>
    </div>	   
</div>

<div class="alert alert-dark mt-1" role="alert">
  	<h4 class="alert-heading">Session['currency']</h4>
	<footer class="blockquote-footer"><cite title="Source Title">Valuta in sessione</cite></footer>
	
	<hr/>
    <div class="mt-2">
    	<div class="pt-1">
    		<a href="<?php echo Router::url(array ('controller' => 'utilcurrency','action' => 'change'))?>">
				change
			</a>	
    	</div>
    </div>
	<hr/>
    <div class="mt-2">
    	<div class="pt-1">
    		<a href="<?php echo Router::url(array ('controller' => 'utilcurrency','action' => 'reset'))?>">
				reset
			</a>	
    	</div>
    </div>
</div>

<div class="ml-5 text-success font-weight-bold"><?php echo $currency ?></div>

<div class="mt-2">
	<a href="<?php echo Router::url(array ('controller' => 'examples','action' => 'home'))?>">
		<span class="fa fa-arrow-left"></span>
	</a>
</div>