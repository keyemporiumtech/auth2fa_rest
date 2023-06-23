<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Modulo shop_warehouse -> TicketUtility -> TicketRequestDataDto</h4>
	<footer class="blockquote-footer">TICKET UTILITY <cite title="Source Title">Utility per la generazione di ticket</cite></footer>
</div>


<div class="alert alert-dark mt-1" role="alert">
  	<h4 class="alert-heading">Evento</h4>
	<?php debug($event)?>
</div>

<div class="alert alert-dark mt-1" role="alert">
  	<h4 class="alert-heading">Richiesta 1</h4>
	<?php debug($request1)?>

	<h4 class="alert-heading">Tickets 1</h4>
	<?php debug($tickets1)?>
</div>

<div class="alert alert-dark mt-1" role="alert">
  	<h4 class="alert-heading">Richiesta 2</h4>
	<?php debug($request2)?>

	<h4 class="alert-heading">Tickets 2</h4>
	<?php debug($tickets2)?>
</div>


<div class="mt-2">
	<a href="<?php echo Router::url(array('controller' => 'shopwarehouse', 'action' => 'ticketUtility')) ?>">
		<span class="fa fa-arrow-left"></span>
	</a>
</div>