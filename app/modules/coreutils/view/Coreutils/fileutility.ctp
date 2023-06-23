
<div class="alert alert-dark mt-1" role="alert">
  	<h4 class="alert-heading">uuid(), uuid_medium(), uuid_medium_unique(), uuid_short(), password()</h4>
	<footer class="blockquote-footer">static function <cite title="Source Title">Genera un uuid</cite></footer>
</div>

<div class="ml-5 text-success font-weight-bold">UUID : <?php echo $uuid ?></div>
<div class="ml-5 text-success font-weight-bold">UUID MEDIUM : <?php echo $uuid_medium ?></div>
<div class="ml-5 text-success font-weight-bold">UUID MEDIUM UNIQUE: <?php echo $uuid_medium_unique ?></div>
<div class="ml-5 text-success font-weight-bold">UUID SHORT : <?php echo $uuid_short ?></div>
<div class="ml-5 text-success font-weight-bold">PASSWORD : <?php echo $password ?></div>

<hr/>

<div class="mt-2">
	<a href="<?php echo Router::url(array ('controller' => 'coreutils','action' => 'home'))?>">
		<span class="fa fa-arrow-left"></span>
	</a>
</div>