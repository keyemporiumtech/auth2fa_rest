<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Modulo cakeutils</h4>
	<footer class="blockquote-footer">CONFIGURATIONS <cite title="Source Title">Utility per cakePhp</cite></footer>
	<hr/>
    <div class="mt-2">
    	<div class="pt-1">
    		<a href="<?php echo Router::url(array('controller' => 'cakeutils', 'action' => 'appGenericBs')) ?>">
				AppGenericBS
			</a>
			<br/>
			<a href="<?php echo Router::url(array('controller' => 'cakeutils', 'action' => 'cookieManager')) ?>">
				Gestione dei cookie
			</a>
			<br/>
			<a href="<?php echo Router::url(array('controller' => 'cakeutils', 'action' => 'genericGroups')) ?>">
				Gestione dei gruppi
			</a>
    	</div>
    </div>
</div>

<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">DelegateUtility::getObj & DelegateUtility::getObjList</h4>
	<footer class="blockquote-footer"><cite title="Source Title">Utility che dato un json ritorna la sua decodifica</cite></footer>
	<hr/>
    <div class="mt-2">
    	<div class="pt-1">
    		<h5>Oggetto json</h5>
    		<?php echo $objJson ?>
    		<br/>
    		<?php debug($objConverted)?>

    		<h5>Lista json</h5>
    		<?php echo $listJson ?>
    		<br/>
    		<?php debug($listConverted)?>
    	</div>
    </div>
</div>

<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">DelegateUtility::mapEntityByJson & DelegateUtility::mapEntityListByJson</h4>
	<footer class="blockquote-footer"><cite title="Source Title">Utility che dato un json con dati ritorna una entity</cite></footer>
	<hr/>
    <div class="mt-2">
    	<div class="pt-1">
    		<h5>Oggetto json</h5>
    		<?php echo $jsonTestfk ?>
    		<br/>
    		<?php debug($convertedTestfk)?>

    		<h5>Lista json</h5>
    		<?php echo $listJsonTestfk ?>
    		<br/>
    		<?php debug($listConvertedTestfk)?>
    	</div>
    </div>
</div>

<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">DelegateUtility::mapEntityJsonByDelegate & DelegateUtility::mapEntityListJsonByDelegate</h4>
	<footer class="blockquote-footer"><cite title="Source Title">Utility che dato un json con dati ritorna una entity</cite></footer>
	<hr/>
    <div class="mt-2">
    	<div class="pt-1">
    		<h5>Oggetto json</h5>
    		<?php echo $jsonTestfk ?>
    		<br/>
    		<?php debug($convertedTestfkByDelegate)?>

    		<h5>Lista json</h5>
    		<?php echo $listJsonTestfk ?>
    		<br/>
    		<?php debug($listConvertedTestfkByDelegate)?>
    	</div>
    </div>
</div>

<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Prova API</h4>
	<footer class="blockquote-footer"><cite title="Source Title">Test di chiamata API controller in json</cite></footer>
	<hr/>
    <div class="mt-2">
    	<div class="pt-1">
    		<form action="<?php echo Router::url(array('controller' => 'testfk', 'action' => 'get')) ?>" method="post" class="mt-2">
				<div class="form-group">
				    <label for="typeCrypt">Richiesta con Foreign key</label>
				    <select class="form-control form-control-sm" id="checkBelongs" name="checkBelongs">
				      <option value="">Null</option>
				      <option value="true">true</option>
				    </select>
				</div>
		    	<div class="form-group">
				    <label for="value">ID</label>
				    <input type="text" class="form-control" id="id" name="id" aria-describedby="idHelp">
				    <small id="idHelp" class="form-text text-muted">Inserire un id</small>
				</div>
				<div class="form-group">
				    <label for="value">COD</label>
				    <input type="text" class="form-control" id="cod" name="cod" aria-describedby="codHelp">
				    <small id="codHelp" class="form-text text-muted">Inserire un codice</small>
				</div>
				<button type="submit" class="btn btn-primary">Invia</button>
		    </form>
    	</div>
    </div>
</div>



<div class="mt-2">
	<a href="<?php echo Router::url(array('controller' => 'examples', 'action' => 'home')) ?>">
		<span class="fa fa-arrow-left"></span>
	</a>
</div>