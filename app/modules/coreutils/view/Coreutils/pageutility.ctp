<div class="alert alert-dark mt-1" role="alert">
  	<h4 class="alert-heading">getAppNameFolder()</h4>
	<footer class="blockquote-footer">static function <cite title="Source Title">Ritorna il nome della cartella in cui è contenuta l'applicazione</cite></footer>
</div>

<div class="ml-5 text-success font-weight-bold"><?php echo $appFolder ?></div>


<div class="alert alert-dark mt-1" role="alert">
  	<h4 class="alert-heading">getFieldByPhpForm()</h4>
	<footer class="blockquote-footer">static function <cite title="Source Title">Ritorna il valore di un campo di un form utilizzando $_POST, $_GET o $_REQUEST</cite></footer>
</div>

<div class="mt-2">
	<div class="pt-1">
		<form action="<?php echo Router::url(array ('controller' => 'coreutils','action' => 'fieldPhpPost'))?>" method="post" class="mt-2">			
	    	<div class="form-group">
			    <label for="value">Parametro key1 POST</label>
			    <input type="text" class="form-control" id="key1" name="key1" aria-describedby="dataHelp">
			    <small id="dataHelp" class="form-text text-muted">Inserire un valore da recuperare in POST</small>
			</div>
			<button type="submit" class="btn btn-primary">Invia</button>
	    </form>
	</div>
</div>

<div class="mt-2">
	<div class="pt-1">
		<form action="<?php echo Router::url(array ('controller' => 'coreutils','action' => 'fieldPhpGet'))?>" method="get" class="mt-2">			
	    	<div class="form-group">
			    <label for="value">Parametro key1 GET</label>
			    <input type="text" class="form-control" id="key1" name="key1" aria-describedby="dataHelp">
			    <small id="dataHelp" class="form-text text-muted">Inserire un valore da recuperare in GET</small>
			</div>
			<button type="submit" class="btn btn-primary">Invia</button>
	    </form>
	</div>
</div>

<div class="alert alert-dark mt-1" role="alert">
  	<h4 class="alert-heading">getPathApp()</h4>
	<footer class="blockquote-footer">static function <cite title="Source Title">Ritorna il path della cartella app di progetto</cite></footer>
</div>

<div class="ml-5 text-success font-weight-bold"><?php echo $appPath ?></div>


<div class="alert alert-dark mt-1" role="alert">
  	<h4 class="alert-heading">getCurrentUrl()</h4>
	<footer class="blockquote-footer">static function <cite title="Source Title">Ritorna la url corrente</cite></footer>
</div>

<strong>getCurrentUrl($this)</strong><div class="ml-5 text-success font-weight-bold"><?php echo $current1 ?></div>
<strong>getCurrentUrl()</strong><div class="ml-5 text-success font-weight-bold"><?php echo $current2 ?></div>


<div class="alert alert-dark mt-1" role="alert">
  	<h4 class="alert-heading">getCurrentUrlComplete()</h4>
	<footer class="blockquote-footer">static function <cite title="Source Title">Ritorna la url completa leggendola da php</cite></footer>
</div>

<div class="ml-5 text-success font-weight-bold"><?php echo $complete ?></div>

<div class="mt-2">
	<a href="<?php echo Router::url(array ('controller' => 'coreutils','action' => 'home'))?>">
		<span class="fa fa-arrow-left"></span>
	</a>
</div>