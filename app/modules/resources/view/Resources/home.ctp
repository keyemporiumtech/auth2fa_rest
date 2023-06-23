<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Modulo resources</h4>	
</div>

<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Prova URL</h4>
	<footer class="blockquote-footer"><cite title="Source Title">Test di chiamata AttachmentUtility::getObjByPath</cite></footer>
	<hr/>
    <div class="mt-2">
    	<div class="pt-1">
    		URL: <?php echo $url?><br/>
    		ATTACHMENT: <?php debug($urlAttachment)?>	    		   
    	</div>
    </div>
</div>   

<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Prova PATH</h4>
	<footer class="blockquote-footer"><cite title="Source Title">Test di chiamata AttachmentUtility::getObjByPath</cite></footer>
	<hr/>
    <div class="mt-2">
    	<div class="pt-1">
    		PATH: <?php echo $path?><br/>
    		ATTACHMENT: <?php debug($pathAttachment)?>	    		   
    	</div>
    </div>
</div>  

<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Prova DELEGATE</h4>
	<footer class="blockquote-footer"><cite title="Source Title">Test di compilazione automatica dell'entity Attachment</cite></footer>
	<hr/>
    <div class="mt-2">
    	<div class="pt-1">    		
    		ATTACHMENT: <?php debug($attachment)?>	    		   
    	</div>
    </div>
</div> 
    
<div class="mt-2">
	<a href="<?php echo Router::url(array ('controller' => 'examples','action' => 'home'))?>">
		<span class="fa fa-arrow-left"></span>
	</a>
</div>