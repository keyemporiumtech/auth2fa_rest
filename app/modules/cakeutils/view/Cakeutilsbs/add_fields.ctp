<div class="alert alert-success mt-1" role="alert">	
	<code class="text-dark">
	$bs= new TestfkBS();<br/>
	
	<strong style="text-decoration:underline">Impostare i campi da estrarre in query</strong><br/>
	$bs->addFields(array('id','cod'));<br/>
	
	<br/>
	</code>
</div>

<div class="alert alert-dark mt-1" role="alert">
  	<h4 class="alert-heading">addFields($fields=array())</h4>
	<footer class="blockquote-footer">function <cite title="Source Title">Dice quali campi estrarre</cite></footer>
</div>

<div class="ml-5 text-success font-weight-bold"><?php debug($unique) ?></div>


<div class="mt-2">
	<a href="<?php echo Router::url(array ('controller' => 'cakeutils','action' => 'appGenericBs'))?>">
		<span class="fa fa-arrow-left"></span>
	</a>
</div>