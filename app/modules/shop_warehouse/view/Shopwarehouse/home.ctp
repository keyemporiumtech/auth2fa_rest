<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Modulo shop_warehouse</h4>
	<footer class="blockquote-footer">PRICE UTILITY <cite title="Source Title">Utility per il prezzo</cite></footer>
	<hr/>
    <div class="mt-2">
    	<div class="pt-1">
    		<a href="<?php echo Router::url(array('controller' => 'shopwarehouse', 'action' => 'priceUtility')) ?>">
				PriceUtility
			</a>
    	</div>
    </div>
</div>

<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Examples pdf Ticket</h4>
	<footer class="blockquote-footer">plugin <cite title="Source Title">ticketPdfMaker</cite></footer>

  	<div class="mt-2">
		<a class="btn btn-primary btn-sm" href="<?php echo Router::url(array('controller' => 'shopwarehouse', 'action' => 'createPdf')) ?>" target="_blank">
			VEDI UN PDF DI UN TICKET
		</a>
	</div>
	<div class="mt-2">
		<a class="btn btn-primary btn-sm" href="<?php echo Router::url(array('controller' => 'shopwarehouse', 'action' => 'createPdf', '?' => array("fillColor" => "#ccff00"))) ?>" target="_blank">
			VEDI UN PDF DI UN TICKET COLORATO
		</a>
	</div>
</div>

<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Examples make Ticket</h4>
	  <footer class="blockquote-footer">TICKET UTILITY <cite title="Source Title">Utility per la generazione di ticket</cite></footer>
	<hr/>
    <div class="mt-2">
    	<div class="pt-1">
    		<a href="<?php echo Router::url(array('controller' => 'shopwarehouse', 'action' => 'ticketUtility')) ?>">
				TicketUtility
			</a>
    	</div>
    </div>
	</div>
</div>


<div class="mt-2">
	<a href="<?php echo Router::url(array('controller' => 'examples', 'action' => 'home')) ?>">
		<span class="fa fa-arrow-left"></span>
	</a>
</div>