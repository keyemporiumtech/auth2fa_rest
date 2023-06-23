<div class="alert alert-success mt-1" role="alert">	
	<code class="text-dark">
	$bs= new TestfkBS();<br/>
	
	<strong style="text-decoration:underline">Aggiungere una condizione con operatore di confronto</strong><br/>
	$bs->addSign("id", "1", EnumQuerySign::GREATER); <span class="text-danger">// EnumQuerySign [NOTHING => "", GREATER => "&gt;", GREATER_EQUAL => "&gt;=", LOWER => "&lt;", LOWER_EQUAL => "&lt;=", EQUAL => "=", DIFFERENT => "&lt;&gt;"]</span><br/>
	
	<br/>
	</code>
</div>

<div class="alert alert-dark mt-1" role="alert">
  	<h4 class="alert-heading">addSign($key, $value, EnumQuerySign)</h4>
	<footer class="blockquote-footer">function <cite title="Source Title">Aggiunge una condizione di confronto nella where</cite></footer>
</div>

<div class="ml-5 text-success font-weight-bold"><?php debug($all) ?></div>


<div class="mt-2">
	<a href="<?php echo Router::url(array ('controller' => 'cakeutils','action' => 'appGenericBs'))?>">
		<span class="fa fa-arrow-left"></span>
	</a>
</div>