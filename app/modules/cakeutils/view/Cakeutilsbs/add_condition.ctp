<div class="alert alert-success mt-1" role="alert">	
	<code class="text-dark">
	$bs= new TestfkBS();<br/>
	
	<strong style="text-decoration:underline">Aggiungere una condizione in query</strong><br/>
	$bs->addCondition("cod","FK001");<br/>
	
	<br/>
	</code>
</div>

<div class="alert alert-dark mt-1" role="alert">
  	<h4 class="alert-heading">addCondition($$key, $value)</h4>
	<footer class="blockquote-footer">function <cite title="Source Title">Aggiunge una condizione in where</cite></footer>
</div>

<div class="ml-5 text-success font-weight-bold"><?php debug($unique) ?></div>


<div class="mt-2">
	<a href="<?php echo Router::url(array ('controller' => 'cakeutils','action' => 'appGenericBs'))?>">
		<span class="fa fa-arrow-left"></span>
	</a>
</div>