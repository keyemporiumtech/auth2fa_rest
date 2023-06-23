<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Modulo shop_warehouse -> PriceUtility</h4>
	<footer class="blockquote-footer">PRICE UTILITY <cite title="Source Title">Utility per il prezzo</cite></footer>	  
</div>


<div class="alert alert-dark mt-1" role="alert">
  	<h4 class="alert-heading">PriceUtility::calcIva</h4>
	<footer class="blockquote-footer">function <cite title="Source Title">calcolo dell'iva a partire da un percentuale</cite></footer>
</div>

<div>
	<h4>Caso1: ESENTE IVA</h4>
	<strong>TOTALE</strong> <?php echo $total1?><br/>
	<strong>PERCENTUALE DI IVA</strong> <?php echo $iva_percent1?><br/>
</div>
<div class="ml-5 text-success font-weight-bold"><?php debug($calc1) ?></div>

<div>
	<h4>Caso2: IVA INCLUSA</h4>
	<strong>TOTALE</strong> <?php echo $total1?><br/>
	<strong>PERCENTUALE DI IVA</strong> <?php echo $iva_percent1?><br/>
</div>
<div class="ml-5 text-success font-weight-bold"><?php debug($calc2) ?></div>

<div>
	<h4>Caso3: IVA ESCLUSA</h4>
	<strong>TOTALE</strong> <?php echo $total1?><br/>
	<strong>PERCENTUALE DI IVA</strong> <?php echo $iva_percent1?><br/>
</div>
<div class="ml-5 text-success font-weight-bold"><?php debug($calc3) ?></div>



<div class="alert alert-dark mt-1" role="alert">
  	<h4 class="alert-heading">PriceUtility::calcIvaPercent</h4>
	<footer class="blockquote-footer">function <cite title="Source Title">calcolo della percentuale iva a partire da un valore</cite></footer>
</div>

<div>
	<h4>Caso1: ESENTE IVA</h4>
	<strong>TOTALE</strong> <?php echo $total1?><br/>
	<strong>IVA</strong> <?php echo $iva1?><br/>
</div>
<div class="ml-5 text-success font-weight-bold"><?php debug($calc4) ?></div>

<div>
	<h4>Caso2: IVA INCLUSA</h4>
	<strong>TOTALE</strong> <?php echo $total1?><br/>
	<strong>IVA</strong> <?php echo $iva1?><br/>
</div>
<div class="ml-5 text-success font-weight-bold"><?php debug($calc5) ?></div>

<div>
	<h4>Caso3: IVA ESCLUSA</h4>
	<strong>TOTALE</strong> <?php echo $total1?><br/>
	<strong>IVA</strong> <?php echo $iva1?><br/>
</div>
<div class="ml-5 text-success font-weight-bold"><?php debug($calc6) ?></div>



<div class="mt-2">
	<a href="<?php echo Router::url(array ('controller' => 'shopwarehouse','action' => 'home'))?>">
		<span class="fa fa-arrow-left"></span>
	</a>
</div>