<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Modulo shop_warehouse -> TicketUtility</h4>
	<footer class="blockquote-footer">TICKET UTILITY <cite title="Source Title">Utility per la generazione di ticket</cite></footer>
</div>


<div class="alert alert-dark mt-1" role="alert">
  	<h4 class="alert-heading">TicketUtility::makeTicketsByEvent</h4>
	<footer class="blockquote-footer">function <cite title="Source Title">costruisce un set di tickets a partire da un evento</cite></footer>
</div>

<div>
	<h4>Caso1: TicketRequestDataDto</h4>
	<footer class="blockquote-footer">request <cite title="Source Title">ticket per una data specifica compresa nell'evento</cite></footer>
	<div class="mt-2">
		<a class="btn btn-primary btn-sm" href="<?php echo Router::url(array('controller' => 'shopwarehouse', 'action' => 'ticketRequestData')) ?>">
			Evento con data inizio
		</a>
		<a class="btn btn-primary btn-sm" href="<?php echo Router::url(array('controller' => 'shopwarehouse', 'action' => 'ticketRequestData', '?' => array("flgDtaTo" => "1"))) ?>">
			Evento con data inizio e data fine
		</a>
	</div>
</div>


<div class="mt-2">
	<a href="<?php echo Router::url(array('controller' => 'shopwarehouse', 'action' => 'home')) ?>">
		<span class="fa fa-arrow-left"></span>
	</a>
</div>