<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Modulo crypting</h4>
	<footer class="blockquote-footer">REQUIRED <cite title="Source Title">Crypting e Decrypting esempi</cite></footer>
	<hr/>
    <div class="mt-2">
    	<div class="pt-1">
    		<form action="<?php echo Router::url(array ('controller' => 'crypting','action' => 'encrypt'))?>" method="post" class="mt-2">
				<div class="form-group">
				    <label for="typeCrypt">Tipo di cryptaggio</label>
				    <select class="form-control form-control-sm" id="type" name="type">
				      <option value="<?php echo EnumTypeCrypt::INNER?>">Sistema</option>
				      <option value="<?php echo EnumTypeCrypt::RIJNDAEL?>">RIJNDAEL</option>
				      <option value="<?php echo EnumTypeCrypt::AES?>">AES</option>
				      <option value="<?php echo EnumTypeCrypt::SHA256?>">SHA256</option>
				    </select>
				</div>
		    	<div class="form-group">
				    <label for="value">Stringa da cryptare</label>
				    <input type="text" class="form-control" id="value" name="value" aria-describedby="dataHelp">
				    <small id="dataHelp" class="form-text text-muted">Inserire un valore da cryptare</small>
				</div>
				<button type="submit" class="btn btn-primary">Crypta</button>
		    </form>
		    
		    <form action="<?php echo Router::url(array ('controller' => 'crypting','action' => 'decrypt'))?>" method="post" class="mt-2">
		    	<div class="form-group">
				    <label for="typeCrypt">Tipo di cryptaggio</label>
				    <select class="form-control form-control-sm" id="type" name="type">
				      <option value="<?php echo EnumTypeCrypt::INNER?>">Sistema</option>
				      <option value="<?php echo EnumTypeCrypt::RIJNDAEL?>">RIJNDAEL</option>
				      <option value="<?php echo EnumTypeCrypt::AES?>">AES</option>
				      <option value="<?php echo EnumTypeCrypt::SHA256?>">SHA256</option>
				    </select>
				</div>
				<div class="form-group">
					<label for="value">Stringa da decryptare</label>
				    <input type="text" class="form-control" id="value" name="value" aria-describedby="dataHelp">
				    <small id="dataHelp" class="form-text text-muted">Inserire un valore da decryptare</small>
				</div>
				<button type="submit" class="btn btn-primary">Decrypta</button>
		    </form>	
    	</div>
    </div>
</div>

<div class="mt-2">
	<a href="<?php echo Router::url(array ('controller' => 'examples','action' => 'home'))?>">
		<span class="fa fa-arrow-left"></span>
	</a>
</div>

