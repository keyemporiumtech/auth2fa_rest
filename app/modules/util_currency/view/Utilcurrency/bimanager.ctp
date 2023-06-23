<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Modulo currency - Plugin Banca d'Italia - Manager</h4>			   
</div>

<div class="alert alert-dark mt-1" role="alert">
	<footer class="blockquote-footer">ManagerBI::read() <cite title="Source Title">Legge le ultime valute cachate</cite></footer>
  	<hr/>
    <div class="mt-2">
    	<div class="pt-1">
    		<?php debug($latest)?>	
    	</div>
    </div>
    
    <footer class="blockquote-footer">ManagerBI::get() <cite title="Source Title">Legge dalla cache una valuta specifica</cite></footer>
  	<hr/>
    <div class="mt-2">
    	<div class="pt-1">
    		<?php debug($currency)?>	
    	</div>
    </div>	
    
    <footer class="blockquote-footer">ManagerBI::convert() <cite title="Source Title">Converte da una valuta ad un'altra</cite></footer>
  	<hr/>
    <div class="mt-2">
    	<div class="pt-1">
    		<?php debug($converted)?>	
    	</div>
    </div>	
</div>

<div class="mt-2">
	<a href="<?php echo Router::url(array ('controller' => 'utilcurrency','action' => 'pluginBi'))?>">
		<span class="fa fa-arrow-left"></span>
	</a>
</div>