<div class="alert alert-success mt-1" role="alert">	
	<code class="text-dark">
	$bs= new TestfkBS();<br/>
	
	<strong style="text-decoration:underline">Aggiungere una condizione ad una foreign key in query</strong><br/>
	$bs->addBelongsTo("test_fk");<br/>
	$bs->addCondition("test_fk.cod","ENTITY001");<br/>
	
	<br/>
	</code>
</div>

<div class="alert alert-dark mt-1" role="alert">
  	<h4 class="alert-heading">addBelongsTo($keyFK)</h4>
	<footer class="blockquote-footer">function <cite title="Source Title">Chiede l'estrazione del record in foreign key</cite></footer>
</div>

<div class="ml-5 text-success font-weight-bold"><?php debug($unique) ?></div>


<div class="mt-2">
	<a href="<?php echo Router::url(array ('controller' => 'cakeutils','action' => 'appGenericBs'))?>">
		<span class="fa fa-arrow-left"></span>
	</a>
</div>