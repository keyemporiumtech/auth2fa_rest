<div class="alert alert-dark mt-1" role="alert">
  	<h4 class="alert-heading">getCurrentTime()</h4>
	<footer class="blockquote-footer">static function <cite title="Source Title">Ritorna la data attuale formato Y-m-d H:i:s</cite></footer>
</div>

<div class="ml-5 text-success font-weight-bold"><?php echo $currentTime ?></div>


<div class="alert alert-dark mt-1" role="alert">
  	<h4 class="alert-heading">getCurrentDate()</h4>
	<footer class="blockquote-footer">static function <cite title="Source Title">Ritorna la data attuale formato d/m/Y H:i:s</cite></footer>
</div>

<div class="ml-5 text-success font-weight-bold"><?php echo $currentDate ?></div>


<div class="mt-2">
	<a href="<?php echo Router::url(array ('controller' => 'coreutils','action' => 'home'))?>">
		<span class="fa fa-arrow-left"></span>
	</a>
</div>