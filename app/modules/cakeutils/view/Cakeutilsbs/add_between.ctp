<div class="alert alert-success mt-1" role="alert">	
	<code class="text-dark">
	$bs= new TestfkBS();<br/>
	
	<strong style="text-decoration:underline">Aggiungere una condizione di between in query</strong><br/>
	$bs->addBetween("id", "1", "2");<br/>
	
	<br/>
	</code>
</div>

<div class="alert alert-dark mt-1" role="alert">
  	<h4 class="alert-heading">addBetween($key, $start, $end)</h4>
	<footer class="blockquote-footer">function <cite title="Source Title">Aggiunge una condizione di "contenuto in" nella where</cite></footer>
</div>

<div class="ml-5 text-success font-weight-bold"><?php debug($all) ?></div>


<div class="mt-2">
	<a href="<?php echo Router::url(array ('controller' => 'cakeutils','action' => 'appGenericBs'))?>">
		<span class="fa fa-arrow-left"></span>
	</a>
</div>