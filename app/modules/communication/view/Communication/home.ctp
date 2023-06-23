<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Modulo communications</h4>
</div>


<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Test Read Mail</h4>

  	<a href="<?php echo Router::url(array('controller' => 'communication', 'action' => 'readMail')) . "?id=1" ?>" class="btn btn-sm btn-success mr-2">Email di prova</a>
</div>

<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Read Mail</h4>
	<footer class="blockquote-footer"><cite title="Source Title">Legge il contenuto di una mail</cite></footer>
	<hr/>
    <div class="mt-2">
    	<div class="pt-1">
    		<form action="<?php echo Router::url(array('controller' => 'communication', 'action' => 'readMail')) ?>" method="post" class="mt-2">
		    	<div class="form-group">
				    <label for="id">Identificativo</label>
				    <input type="text" class="form-control" id="id" name="id" value="1">
				</div>
				<input type="hidden" id="innertoken" name="innertoken"
					value="VHBGRFdDYnczd2Y0VkZjQVJ5MWFtMVZRTnROd2dXL1JpREdoY3FwOStrMnk5ZnpqTlpEcE1IQUU5azAvQ2ZOZS9DcTQ3Yk5YNU53ai9BYzhWNGM1dllxUzZwdVRBM05Pa1ZMZTZaa1c1NDIzT3R5UDRINkZhN1VYbG1jd2J3YWw=">
				<button type="submit" class="btn btn-primary">Invia</button>
		    </form>
    	</div>
    </div>
</div>

<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Send Mail</h4>
	<footer class="blockquote-footer"><cite title="Source Title">Test invio mail indirizzo di configurazione</cite></footer>
	<hr/>
    <div class="mt-2">
    	<div class="pt-1">
    		<form action="<?php echo Router::url(array('controller' => 'communication', 'action' => 'sendMail')) ?>" method="post" class="mt-2">
		    	<div class="form-group">
				    <label for="subject">Oggetto</label>
				    <input type="text" class="form-control" id="subject" name="subject" value="">
				</div>
				<div class="form-group">
				    <label for="fromName">Inviato da (Nominativo)</label>
				    <input type="text" class="form-control" id="fromName" name="fromName" value="">
				</div>
				<div class="form-group">
				    <label for="fromEmail">Inviato da (Email)</label>
				    <input type="text" class="form-control" id="fromEmail" name="fromEmail" value="">
				</div>
				<div class="form-group">
				    <label for="message">Messaggio</label>
				    <textarea class="form-control" id="message" name="message"></textarea>
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
	<a href="<?php echo Router::url(array('controller' => 'examples', 'action' => 'home')) ?>">
		<span class="fa fa-arrow-left"></span>
	</a>
</div>