<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Modulo validator_password</h4>
</div>

<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading">Validazione password</h4>
	<footer class="blockquote-footer"><cite title="Source Title">Validazione secondo livelli di sicurezza</cite></footer>
	<hr/>
    <div class="mt-2">
    	<div class="pt-1">
    		<strong>PASSWORD: <?php echo $passwordValid ?></strong><br/><br/>

			<strong>LEVEL 1</strong> <?php echo $level1['valid']->valid ?><br/>
			<i><?php echo $level1['valid']->message ?></i><br/><br/>

			<strong>LEVEL 2</strong> <?php echo $level2['valid']->valid ?><br/>
			<i><?php echo $level2['valid']->message ?></i><br/><br/>

			<strong>LEVEL 3</strong> <?php echo $level3['valid']->valid ?><br/>
			<i><?php echo $level3['valid']->message ?></i><br/><br/>

			<strong>LEVEL 4</strong> <?php echo $level4['valid']->valid ?><br/>
			<i><?php echo $level4['valid']->message ?></i><br/><br/>
			<hr/>

			<strong>PASSWORD: <?php echo $passwordNotValid ?></strong><br/><br/>

			<strong>LEVEL 1</strong> <?php echo $level1['notValid']->valid ?><br/>
			<i><?php echo $level1['notValid']->message ?></i><br/><br/>

			<strong>LEVEL 2</strong> <?php echo $level2['notValid']->valid ?><br/>
			<i><?php echo $level2['notValid']->message ?></i><br/><br/>

			<strong>LEVEL 3</strong> <?php echo $level3['notValid']->valid ?><br/>
			<i><?php echo $level3['notValid']->message ?></i><br/><br/>

			<strong>LEVEL 4</strong> <?php echo $level4['notValid']->valid ?><br/>
			<i><?php echo $level4['notValid']->message ?></i><br/><br/>
    	</div>
    </div>
</div>

<hr/>

<div class="mt-2">
	<a href="<?php echo Router::url(array('controller' => 'examples', 'action' => 'home')) ?>">
		<span class="fa fa-arrow-left"></span>
	</a>
</div>