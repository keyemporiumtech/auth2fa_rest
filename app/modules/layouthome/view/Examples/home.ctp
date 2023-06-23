<?php
require_once ROOT . "/app/Config/system/cores.php";
?>

<h1>API URL</h1>
<a href="<?php echo Router::url(array('controller' => 'examples', 'action' => 'apiList')) ?>" class="alert-link">
	api
</a>
<br/>
<a href="<?php echo Router::url(array('controller' => 'examples', 'action' => 'apiProfiles')) ?>" class="alert-link">
	api Profiles
</a>

<h1>ESEMPI APPLICATIVI</h1>
<?php
$modules = Cores::readJson(ROOT . "" . DS . "app" . DS . "Config" . DS . "json" . DS . "examples.json");
if (!Cores::isEmpty($modules)) {
    foreach ($modules as $name => $others) {
        ?>
<div class="alert alert-success mt-2" role="alert">
	<h4 class="alert-heading">Modulo <?php echo $name ?></h4>
  	<hr>
  	<div class="mb-0">
  		<a href="<?php echo Router::url(array('controller' => $others['controller'], 'action' => $others['action'])) ?>" class="alert-link">
			<?php echo $others['textlink'] ?>
		</a>
  	</div>
</div>
<?php
}
}
