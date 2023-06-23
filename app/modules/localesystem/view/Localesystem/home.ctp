<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Modulo localesystem</h4>
	<footer class="blockquote-footer">CONFIGURATIONS <cite title="Source Title">Utility locale</cite></footer>
	<hr/>
    <div class="mt-2">
    	<div class="pt-1">
    		<a href="<?php echo Router::url(array('controller' => 'localesystem', 'action' => 'translatorutility')) ?>">
				TranslatorUtility
			</a>
    	</div>
    </div>
	<hr/>
    <div class="mt-2">
    	<div class="pt-1">
    		<a href="<?php echo Router::url(array('controller' => 'localesystem', 'action' => 'localeutility')) ?>">
				LocaleUtility
			</a>
    	</div>
    </div>
	<div class="mt-2">
    	<div class="pt-1">
    		<a href="<?php echo Router::url(array('controller' => 'localesystem', 'action' => 'translations')) ?>">
				Files po di traduzione
			</a>
    	</div>
    </div>
</div>

<div class="alert alert-dark mt-1" role="alert">
  	<h4 class="alert-heading">Session['language']</h4>
	<footer class="blockquote-footer"><cite title="Source Title">Lingua in sessione</cite></footer>

	<hr/>
    <div class="mt-2">
    	<div class="pt-1">
    		<a href="<?php echo Router::url(array('controller' => 'localesystem', 'action' => 'change')) ?>">
				change
			</a>
    	</div>
    </div>
	<hr/>
    <div class="mt-2">
    	<div class="pt-1">
    		<a href="<?php echo Router::url(array('controller' => 'localesystem', 'action' => 'reset')) ?>">
				reset
			</a>
    	</div>
    </div>
</div>

<div class="ml-5 text-success font-weight-bold"><?php echo $language ?></div>

<div class="mt-2">
	<a href="<?php echo Router::url(array('controller' => 'examples', 'action' => 'home')) ?>">
		<span class="fa fa-arrow-left"></span>
	</a>
</div>