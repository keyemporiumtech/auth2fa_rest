<div class="alert alert-dark mt-1" role="alert">
  	<h4 class="alert-heading">__translate()</h4>
	<footer class="blockquote-footer">static function <cite title="Source Title">Traduce una chiave secondo la lingua di sistema</cite></footer>
</div>

<div class="ml-5 text-success font-weight-bold"><?php echo $testo ?></div>


<div class="alert alert-dark mt-1" role="alert">
  	<h4 class="alert-heading">__translate_args()</h4>
	<footer class="blockquote-footer">static function <cite title="Source Title">Traduce una chiave che prevede parametri secondo la lingua di sistema</cite></footer>
</div>

<div class="ml-5 text-success font-weight-bold"><?php echo $testoParam ?></div>

<div class="mt-2">
	<a href="<?php echo Router::url(array ('controller' => 'localesystem','action' => 'home'))?>">
		<span class="fa fa-arrow-left"></span>
	</a>
</div>