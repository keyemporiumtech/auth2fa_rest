<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Modulo printcodes</h4>	
</div>



<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Read Qrcode</h4>
	<footer class="blockquote-footer"><cite title="Source Title">Visualizza un qrcode</cite></footer>
	<hr/>
    <div class="mt-2">
    	<div class="pt-1">
    		<form action="<?php echo Router::url(array ('controller' => 'utilprintcodes','action' => 'viewQrcode'))?>" method="post" class="mt-2">				
		    	<div class="form-group">
				    <label for="value">Testo</label>
				    <input type="text" class="form-control" id="text" name="text" value="Prova">				    
				</div>
				<input type="hidden" id="innertoken" name="innertoken" 
					value="VHBGRFdDYnczd2Y0VkZjQVJ5MWFtMVZRTnROd2dXL1JpREdoY3FwOStrMnk5ZnpqTlpEcE1IQUU5azAvQ2ZOZS9DcTQ3Yk5YNU53ai9BYzhWNGM1dllxUzZwdVRBM05Pa1ZMZTZaa1c1NDIzT3R5UDRINkZhN1VYbG1jd2J3YWw=">							
				<button type="submit" class="btn btn-primary">Invia</button>
		    </form>		    		   
    	</div>
    </div>
</div> 

<hr/>   

<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Read Barcode</h4>
	<footer class="blockquote-footer"><cite title="Source Title">Visualizza un barcode</cite></footer>
	<hr/>
    <div class="mt-2">
    	<div class="pt-1">
    		<form action="<?php echo Router::url(array ('controller' => 'utilprintcodes','action' => 'viewBarcode'))?>" method="post" class="mt-2">				
		    	<div class="form-group">
				    <label for="value">Testo</label>
				    <input type="text" class="form-control" id="text" name="text" value="Prova">				    
				</div>
				<input type="hidden" id="innertoken" name="innertoken" 
					value="VHBGRFdDYnczd2Y0VkZjQVJ5MWFtMVZRTnROd2dXL1JpREdoY3FwOStrMnk5ZnpqTlpEcE1IQUU5azAvQ2ZOZS9DcTQ3Yk5YNU53ai9BYzhWNGM1dllxUzZwdVRBM05Pa1ZMZTZaa1c1NDIzT3R5UDRINkZhN1VYbG1jd2J3YWw=">							
				<button type="submit" class="btn btn-primary">Invia</button>
		    </form>		    		   
    	</div>
    </div>
</div> 

<hr/> 

<div class="mt-2">
	<a href="<?php echo Router::url(array ('controller' => 'examples','action' => 'home'))?>">
		<span class="fa fa-arrow-left"></span>
	</a>
</div>