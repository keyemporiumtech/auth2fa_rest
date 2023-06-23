<div class="alert alert-success mt-1" role="alert">
	<h4 class="alert-heading">Gestione dei gruppi</h4>
</div>

<div class="alert alert-dark mt-1" role="alert">
  	<h4 class="alert-heading">Lista entità appartenenti ad un gruppo specifico</h4>
	<footer class="blockquote-footer">groups= <cite title="Source Title">["GRP1"]</cite></footer>
</div>

<div class="ml-5 text-success font-weight-bold"><?php debug($list1)?></div>


<div class="alert alert-dark mt-1" role="alert">
  	<h4 class="alert-heading">Lista entità appartenenti a più gruppi</h4>
	<footer class="blockquote-footer">groups= <cite title="Source Title">["GRP1", "GRP2"]</cite></footer>
</div>

<div class="ml-5 text-success font-weight-bold"><?php debug($list2)?></div>


<div class="alert alert-dark mt-1" role="alert">
  	<h4 class="alert-heading">Lista entità appartenenti a più gruppi in condizione Like</h4>
	<footer class="blockquote-footer">groups= <cite title="Source Title">["GRP"]</cite></footer>
	<footer class="blockquote-footer">likegroups= <cite title="Source Title">EnumQueryLike::RIGHT</cite></footer>
</div>

<div class="ml-5 text-success font-weight-bold"><?php debug($list3)?></div>



<hr/>

<div class="alert alert-dark mt-1" role="alert">
  	<h4 class="alert-heading">Salvataggio nei gruppi (Aggiunta entity a GRP1 e GRP2)</h4>
	<footer class="blockquote-footer">groupssave= <cite title="Source Title">["GRP1", "GRP2"]</cite></footer>
	<div class="mt-2">
		<a href="<?php echo Router::url(array('controller' => 'cakeutils', 'action' => 'saveInGroup')) ?>">
			<span class="fa fa-save"></span>
			SALVA TESTFK IN GRP1 E GRP2
		</a>
	</div>
</div>

<div class="alert alert-dark mt-1" role="alert">
  	<h4 class="alert-heading">Aggiornamento nei gruppi (Rimozione entity da GRP1)</h4>
	<footer class="blockquote-footer">groupsdel= <cite title="Source Title">["GRP1"]</cite></footer>


	<div class="mt-2">
    	<div class="pt-1">
    		<form action="<?php echo Router::url(array('controller' => 'cakeutils', 'action' => 'editInGroup1')) ?>" method="post" class="mt-2">
		    	<div class="form-group">
				    <label for="value">ID</label>
				    <input type="text" class="form-control" id="id" name="id" aria-describedby="idHelp">
				    <small id="idHelp" class="form-text text-muted">Inserire un id per rimuovere da GRP1</small>
				</div>
				<button type="submit" class="btn btn-primary">Invia</button>
		    </form>
    	</div>
    </div>

	<hr/>
	<h4 class="alert-heading">Aggiornamento nei gruppi (Rimozione entity da GRP2 e Aggiunta in GRP1)</h4>
	<footer class="blockquote-footer">groupssave= <cite title="Source Title">["GRP1"]</cite></footer>
	<footer class="blockquote-footer">groupsdel= <cite title="Source Title">["GRP2"]</cite></footer>

	<div class="mt-2">
    	<div class="pt-1">
    		<form action="<?php echo Router::url(array('controller' => 'cakeutils', 'action' => 'editInGroup2')) ?>" method="post" class="mt-2">
		    	<div class="form-group">
				    <label for="value">ID</label>
				    <input type="text" class="form-control" id="id" name="id" aria-describedby="idHelp">
				    <small id="idHelp" class="form-text text-muted">Inserire un id per rimuovere da GRP2 e aggiungere a GRP1</small>
				</div>
				<button type="submit" class="btn btn-primary">Invia</button>
		    </form>
    	</div>
    </div>
</div>

<div class="alert alert-dark mt-1" role="alert">
  	<h4 class="alert-heading">Rimozione entity e rimozione nei gruppi</h4>

	<div class="mt-2">
		<div class="pt-1">
			<form action="<?php echo Router::url(array('controller' => 'cakeutils', 'action' => 'delInGroup')) ?>" method="post" class="mt-2">
				<div class="form-group">
					<label for="value">ID</label>
					<input type="text" class="form-control" id="id" name="id" aria-describedby="idHelp">
					<small id="idHelp" class="form-text text-muted">Inserire un id per rimuovere l'entity e cancellarla dai gruppi</small>
				</div>
				<button type="submit" class="btn btn-primary">Invia</button>
			</form>
		</div>
	</div>
</div>

<div class="mt-2">
	<a href="<?php echo Router::url(array('controller' => 'cakeutils', 'action' => 'home')) ?>">
		<span class="fa fa-arrow-left"></span>
	</a>
</div>