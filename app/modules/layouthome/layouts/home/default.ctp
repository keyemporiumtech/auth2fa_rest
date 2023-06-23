<?php
require_once ROOT . "/app/Config/system/cores.php";
App::uses('Defaults', 'Config/system');
App::uses('Enables', 'Config/system');
App::uses('LayouthomeConfig', 'modules/layouthome/config');
App::uses('ProductionVisibilityUtility', 'modules/layouthome/utility');
?>
<h2><?php echo LayouthomeConfig::$TITLE; ?></h2>
<p>
	<?php echo LayouthomeConfig::$DESCRIPTION; ?>
</p>

<div class="mt-2">
	<strong>Defaults::get("db_name")</strong> =<?php echo Defaults::get("db_name"); ?><br/>
	<strong>Defaults::get("timezone_db")</strong> =<?php echo Defaults::get("timezone_db"); ?><br/>
	<strong>Defaults::get("timezone")</strong> =<?php echo Defaults::get("timezone"); ?><br/>
	<strong>Router::url('/', true)</strong> =<?php echo Router::url('/', true); ?><br/>
	<strong>$_SERVER['DOCUMENT_ROOT']</strong> = <?php echo $_SERVER['DOCUMENT_ROOT'] ?><br/>
	<strong>$_SERVER['REQUEST_URI']</strong> = <?php echo $_SERVER['REQUEST_URI'] ?><br/>
	<strong>$_SERVER['HTTP_HOST']</strong> = <?php echo $_SERVER['HTTP_HOST'] ?><br/>
	<strong>this->base</strong> = <?php echo $this->base ?><br/>
	<strong>WWW_ROOT</strong> = <?php echo WWW_ROOT ?><br/>
	<strong>ROOT</strong> = <?php echo ROOT ?><br/>
	<strong>DIRECTORY SEPARATOR</strong> = <?php echo DS ?><br/>
	<strong>CakeSession::id()</strong> = <?php echo CakeSession::id() ?>
</div>

<hr/>
<h4><span class="badge badge-secondary">Moduli</span></h4>
<?php
$modules = Cores::readJson(ROOT . "/versioning.json");
?>
<div class="mt-2">
	<?php foreach ($modules as $module => $version) {
    if ($module !== "version") {

        if ($version == "0.0.0") {
            $className = "alert alert-danger";
        } else {
            $className = "alert alert-success";
        }
        ?>
		<div class="<?php echo $className ?>">
			<strong><?php echo $module ?></strong> =<?php echo $version == "0.0.0" ? "NONE" : $version; ?>
		</div>
	<?php }}?>
</div>
