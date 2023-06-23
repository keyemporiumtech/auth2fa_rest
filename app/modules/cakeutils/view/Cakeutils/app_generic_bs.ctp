<div class="alert alert-success mt-1" role="alert">
	<h4 class="alert-heading">Uso semplice delle condizioni</h4>
	<code class="text-dark">
	$bs= new TestfkBS();<br/>
	
	<strong style="text-decoration:underline">Impostare i campi da estrarre in query</strong><br/>
	$bs->addFields(array('id','cod'));<br/>
	<a href="<?php echo Router::url(array ('controller' => 'cakeutilsbs','action' => 'addFields'))?>">
		Test
	</a>
	<br/>
	
	<strong style="text-decoration:underline">Aggiungere una condizione in query</strong><br/>
	$bs->addCondition("cod","FK001");<br/>
	<a href="<?php echo Router::url(array ('controller' => 'cakeutilsbs','action' => 'addCondition'))?>">
		Test
	</a>
	<br/>
	
	<strong style="text-decoration:underline">Aggiungere una condizione ad una foreign key in query</strong><br/>
	$bs->addBelongsTo("test_fk");<br/>
	$bs->addCondition("test_fk.cod","ENTITY001");<br/>
	<a href="<?php echo Router::url(array ('controller' => 'cakeutilsbs','action' => 'addBelongsTo'))?>">
		Test
	</a>
	<br/>
	
	<strong style="text-decoration:underline">Aggiungere una condizione di like</strong><br/>
	$bs->addLike("cod", "001", EnumQueryLike::LEFT); <span class="text-danger">// EnumQueryLike [LEFT => %VAL, RIGHT => VAL%, LEFT_RIGHT => %VAL%, PRECISION => VAL]</span><br/>	
	<a href="<?php echo Router::url(array ('controller' => 'cakeutilsbs','action' => 'addLike'))?>">
		Test
	</a>
	<br/>
	
	<strong style="text-decoration:underline">Aggiungere una condizione con operatore di confronto</strong><br/>
	$bs->addSign("id", "1", EnumQuerySign::GREATER); <span class="text-danger">// EnumQuerySign [NOTHING => "", GREATER => "&gt;", GREATER_EQUAL => "&gt;=", LOWER => "&lt;", LOWER_EQUAL => "&lt;=", EQUAL => "=", DIFFERENT => "&lt;&gt;"]</span><br/>	
	<a href="<?php echo Router::url(array ('controller' => 'cakeutilsbs','action' => 'addSign'))?>">
		Test
	</a>
	<br/>
	
	<strong style="text-decoration:underline">Aggiungere una condizione di between in query</strong><br/>
	$bs->addBetween("id", "1", "2");<br/>
	<a href="<?php echo Router::url(array ('controller' => 'cakeutilsbs','action' => 'addBetween'))?>">
		Test
	</a>
	<br/>
	
	<br/><br/>
	</code>
</div>


<div class="alert alert-dark mt-1" role="alert">
  	<h4 class="alert-heading">LIST: genericQuery() - NO NEED TO ALIAS</h4>
	<footer class="blockquote-footer">function <cite title="Source Title">query generica</cite></footer>
</div>

<div class="ml-5 text-success font-weight-bold"><?php debug($listQuery) ?></div>

<div class="alert alert-dark mt-1" role="alert">
  	<h4 class="alert-heading">LIST: query() - ALIAS NEEDED</h4>
	<footer class="blockquote-footer">function <cite title="Source Title">query generica con alias alla tabella</cite></footer>
</div>

<div class="ml-5 text-success font-weight-bold"><?php debug($listSqlQuery) ?></div>

<div class="alert alert-dark mt-1" role="alert">
  	<h4 class="alert-heading">UNIQUE: genericQuery() - NO NEED TO ALIAS</h4>
	<footer class="blockquote-footer">function <cite title="Source Title">array di array senza alias. obj[0] needed</cite></footer>
</div>

<div class="ml-5 text-success font-weight-bold"><?php debug($uniqueQuery) ?></div>

<div class="alert alert-dark mt-1" role="alert">
  	<h4 class="alert-heading">UNIQUE: query() - ALIAS NEEDED</h4>
	<footer class="blockquote-footer">function <cite title="Source Title">oggetto singolo con alias alla tabella</cite></footer>
</div>

<div class="ml-5 text-success font-weight-bold"><?php debug($uniqueSqlQuery) ?></div>

<hr/>

<div class="alert alert-dark mt-1" role="alert">
  	<h4 class="alert-heading">instance()</h4>
	<footer class="blockquote-footer">function <cite title="Source Title">istanza con valori di default</cite></footer>
</div>

<div class="ml-5 text-success font-weight-bold"><?php debug($instance) ?></div>


<div class="alert alert-dark mt-1" role="alert">
  	<h4 class="alert-heading">unique()</h4>
	<footer class="blockquote-footer">function <cite title="Source Title">torna un record dall'id</cite></footer>
</div>

<div class="ml-5 text-success font-weight-bold"><?php debug($unique) ?></div>


<div class="alert alert-dark mt-1" role="alert">
  	<h4 class="alert-heading">addBelongsTo()</h4>
	<footer class="blockquote-footer">function <cite title="Source Title">aggiunge una foreign key</cite></footer>
</div>

<div class="ml-5 text-success font-weight-bold"><?php debug($fk) ?></div>


<div class="alert alert-dark mt-1" role="alert">
  	<h4 class="alert-heading">logDataSource()</h4>
	<footer class="blockquote-footer">function <cite title="Source Title">logga le query eseguite dal dao</cite></footer>
</div>

<div class="ml-5 text-success font-weight-bold"><?php debug($log) ?></div>
<div class="ml-5 text-success font-weight-bold"><?php debug($logquery) ?></div>

<div class="mt-2">
	<a href="<?php echo Router::url(array ('controller' => 'cakeutils','action' => 'home'))?>">
		<span class="fa fa-arrow-left"></span>
	</a>
</div>