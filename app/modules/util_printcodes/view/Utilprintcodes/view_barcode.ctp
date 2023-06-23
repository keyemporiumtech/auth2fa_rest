<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Modulo printcodes - BARCODE</h4>	
</div>



<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading"><?php echo $text?></h4>
	<footer class="blockquote-footer"><cite title="Source Title">Allegato da barcode</cite></footer>
	<hr/>
    <div class="mt-2">
    	<div class="pt-1">
    		<?php debug($attachment)?>	    		   
    	</div>
    </div>
    <hr/>
    <div class="mt-2">
    	<div class="pt-1">
    		<img src="data:<?php echo $attachment['Attachment']['mimetype']?>;base64,<?php echo $attachment['Attachment']['content']?>" width="360px"/>	    		   
    	</div>
    </div>
</div> 

<hr/>   


<div class="mt-2">
	<a href="<?php echo Router::url(array ('controller' => 'utilprintcodes','action' => 'home'))?>">
		<span class="fa fa-arrow-left"></span>
	</a>
</div>