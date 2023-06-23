<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Modulo authentication</h4>	
</div>

<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Costruisci API</h4>
	<footer class="blockquote-footer"><cite title="Source Title">Utility per comporre l'url di una chiamata</cite></footer>
	<hr/>
    <div class="mt-2">
    	<div class="pt-1">
    		<form action="<?php echo Router::url(array ('controller' => 'authentication','action' => 'buildApi'))?>" method="post" class="mt-2">				
		    	<div class="form-group">
				    <label for="value">Controller</label>
				    <input type="text" class="form-control" id="ctrl" name="ctrl">				    
				</div>
				<div class="form-group">
				    <label for="value">Action</label>
				    <input type="text" class="form-control" id="act" name="act">				    
				</div>
				<div class="form-group">
				    <label for="value">Token client</label>
				    <input type="text" class="form-control" id="clientT" name="clientT" aria-describedby="clientHelp"
				    	value="d0pOWnlRaXFZd0ova1ovb3grSE03QT09.5cc6da3ce7a5495e131f827fd29eca657a374c6b">
				    <small id="clientHelp" class="form-text text-muted">Token per autorizzare il client</small>
				</div>
				<div class="form-group">
				    <label for="value">Token login</label>
				    <input type="text" class="form-control" id="sessionT" name="sessionT" aria-describedby="sessionHelp">
				    <small id="sessionHelp" class="form-text text-muted">Token ricevuto dopo il login per autorizzare una sessione utente</small>
				</div>
				<div class="form-group">
				    <label for="value">PARAMETRI</label>
				    <input type="text" class="form-control" id="paramsS" name="paramsS" aria-describedby="paramsHelp">
				    <small id="paramsHelp" class="form-text text-muted">Esempio id=1&cod=ciao</small>
				</div>
				<button type="submit" class="btn btn-primary">Invia</button>
		    </form>		    		   
    	</div>
    </div>
</div>  

<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Prova Token</h4>
	<footer class="blockquote-footer"><cite title="Source Title">Test di generazione token client</cite></footer>
	<hr/>
    <div class="mt-2">
    	<div class="pt-1">
    		<form action="<?php echo Router::url(array ('controller' => 'authentication','action' => 'clientTokenEncode'))?>" method="post" class="mt-2">
				<div class="form-group">
				    <label for="client_id">Id del client</label>
				    <input type="text" class="form-control" id="client_id" name="client_id" aria-describedby="idHelp">
				</div>		    	
				<button type="submit" class="btn btn-primary">Invia</button>
		    </form>		    		   
    	</div>
    </div>
</div>

<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Prova Token</h4>
	<footer class="blockquote-footer"><cite title="Source Title">Test di decodifica e verifica token client</cite></footer>
	<hr/>
    <div class="mt-2">
    	<div class="pt-1">
    		<form action="<?php echo Router::url(array ('controller' => 'authentication','action' => 'clientTokenDecode'))?>" method="post" class="mt-2">
				<div class="form-group">
				    <label for="token">Token del client</label>
				    <input type="text" class="form-control" id="token" name="token" aria-describedby="idHelp">
				</div>		    	
				<button type="submit" class="btn btn-primary">Invia</button>
		    </form>		    		   
    	</div>
    </div>
</div>

<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Test URL token</h4>
  	
  	<a href="<?php echo Router::url(array ('controller' => 'authentication','action' => 'tokenNull'))?>" class="btn btn-sm btn-dark mr-2" target="_blank">Token not found</a>
  	<a href="<?php echo Router::url(array ('controller' => 'authentication','action' => 'tokenInvalid'))."?{$clientNameToken}=ALTRO.b6d59ae584de0adeee2957a7599e8b99c32da506"?>" class="btn btn-sm btn-dark mr-2" target="_blank">Token not valid</a>
  	<a href="<?php echo Router::url(array ('controller' => 'authentication','action' => 'tokenValid'))."?{$clientNameToken}=K3o0bzJvKzh4VGNsRkJpcVNoSGtMQT09.b6d59ae584de0adeee2957a7599e8b99c32da506"?>" class="btn btn-sm btn-success mr-2" target="_blank">Token valid</a>
</div>

<hr/>   


<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Prova Login Token</h4>
	<footer class="blockquote-footer"><cite title="Source Title">Test di generazione token login</cite></footer>
	<hr/>
    <div class="mt-2">
    	<div class="pt-1">
    		<form action="<?php echo Router::url(array ('controller' => 'authentication','action' => 'loginTokenEncode'))?>" method="post" class="mt-2">
				<div class="form-group">
				    <label for="client_id">Id del client</label>
				    <input type="text" class="form-control" id="client_id" name="client_id" aria-describedby="idHelp">
				</div>
				<div class="form-group">
				    <label for="payload">Payload</label>
				    <input type="text" class="form-control" id="payload" name="payload" aria-describedby="idHelp">
				</div>		    	
				<button type="submit" class="btn btn-primary">Invia</button>
		    </form>		    		   
    	</div>
    </div>
</div>

<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Prova Login Token</h4>
	<footer class="blockquote-footer"><cite title="Source Title">Test di decodifica e verifica token login</cite></footer>
	<hr/>
    <div class="mt-2">
    	<div class="pt-1">
    		<form action="<?php echo Router::url(array ('controller' => 'authentication','action' => 'loginTokenDecode'))?>" method="post" class="mt-2">
				<div class="form-group">
				    <label for="token">Token login</label>
				    <input type="text" class="form-control" id="token" name="token" aria-describedby="idHelp">
				</div>		    	
				<button type="submit" class="btn btn-primary">Invia</button>
		    </form>		    		   
    	</div>
    </div>
</div>

<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Test URL token Login</h4>
  	
  	<a href="<?php echo Router::url(array ('controller' => 'authentication','action' => 'loginNull'))?>" class="btn btn-sm btn-dark mr-2" target="_blank">Token not found</a>
  	<a href="<?php echo Router::url(array ('controller' => 'authentication','action' => 'loginInvalid'))."?{$loginNameToken}=ALTRO.eyJpc3MiOiJLRVlFTVBPUklVTVNFUlZJQ0VTIiwiYXVkIjoiTUlDTElFIiwiaWF0IjoxNjEwMzY2MDAyMDAwLCJuYmYiOjE2MTAzNjYwMTIwMDAsImV4cCI6MTYxMDM2NzgwMjAwMCwiZGF0YSI6InByb3ZhIn0=.21db500536a9ba6df5b21c828a0611a2b060083d"?>" class="btn btn-sm btn-dark mr-2" target="_blank">Token not valid</a>
  	<a href="<?php echo Router::url(array ('controller' => 'authentication','action' => 'loginValid'))."?{$loginNameToken}=eyJhbGciOiJTSEExIiwidHlwZSI6IkREQyJ9.eyJpc3MiOiJLRVlFTVBPUklVTVNFUlZJQ0VTIiwiYXVkIjoiTUlDTElFIiwiaWF0IjoxNjEwMzY2MDAyMDAwLCJuYmYiOjE2MTAzNjYwMTIwMDAsImV4cCI6MTYxMDM2NzgwMjAwMCwiZGF0YSI6InByb3ZhIn0=.21db500536a9ba6df5b21c828a0611a2b060083d"?>" class="btn btn-sm btn-success mr-2" target="_blank">Token valid</a>  
</div>
 
    
<div class="mt-2">
	<a href="<?php echo Router::url(array ('controller' => 'examples','action' => 'home'))?>">
		<span class="fa fa-arrow-left"></span>
	</a>
</div>