<div class="alert alert-dark mt-1" role="alert">
  	<h4 class="alert-heading">Po files</h4>
	<footer class="blockquote-footer">
		<div class="ml-5 text-success font-weight-bold">LANGUAGE=<?php echo $language ?></div>
		<div class="ml-5 text-success font-weight-bold">LOCAL=<?php echo $languageLocal ?></div>
		<div class="ml-5 text-success font-weight-bold">SPECIFIC=<?php echo $languageSpecific ?></div>
	</footer>
</div>


<?php foreach ($languages as $lan) {?>
	<a href="<?php echo Router::url(array('controller' => 'localesystem', 'action' => 'changeTranslation', "?" => array("lan" => $lan, "grouped" => $grouped))) ?>" class="mr-2">
		<?php if ($lan == $language) {?><strong><?php echo $lan ?></strong><?php } else {?><span><?php echo $lan ?></span> <?php }?>
	</a>
<?php }?>

<?php if ($grouped) {?>
<a href="<?php echo Router::url(array('controller' => 'localesystem', 'action' => 'translations', "?" => array("grouped" => "0"))) ?>" class="mr-2">
	<span class="fa fa-list"></span>
</a>
<span class="fa fa-outdent text-mute mr-2"></span>
<?php }?>

<?php if (!$grouped) {?>
<span class="fa fa-list text-mute mr-2"></span>
<a href="<?php echo Router::url(array('controller' => 'localesystem', 'action' => 'translations', "?" => array("grouped" => "1"))) ?>" class="mr-2">
	<span class="fa fa-outdent"></span>
</a>
<?php }?>
<hr/>

<?php
if ($grouped) {
    foreach ($groups as $group => $posG) {
        ?>

<div id="accordionGroup<?php echo $group ?>" class="m-1">
	<div class="m-1">
		<a class="btn btn-dark" data-toggle="collapse" href="#collapse<?php echo $group ?>" role="button" aria-expanded="false" aria-controls="collapse<?php echo $group ?>">
			<?php echo $group ?>
		</a>
	</div>
	<div class="collapse" id="collapse<?php echo $group ?>">
		<div class="card card-body">

			<div id="accordionPos<?php echo $group ?>" class="m-1">
			<?php
$btnClass = "";
        foreach ($posG as $key => $values) {
            $btnClass = TranslatorUtility::isPoTranslated($language, $key) ? "btn-primary" : "btn-danger";
            ?>
				<div class="m-1">
					<a class="btn <?php echo $btnClass ?>" data-toggle="collapse" href="#collapse<?php echo str_replace(".po", "", $key) ?>" role="button" aria-expanded="false" aria-controls="collapse<?php echo str_replace(".po", "", $key) ?>">
						<?php echo $key ?>
					</a>
				</div>
				<div class="collapse" id="collapse<?php echo str_replace(".po", "", $key) ?>">
					<div class="card card-body">
						<dl>
						<?php foreach ($values as $value) {?>
							<dt><?php echo $value ?></dt>
							<dd><?php echo __d(str_replace(".po", "", $key), $value) ?></dd>
						<?php }?>
						</dl>
					</div>
				</div>

			<?php }?>
			</div>

		</div>
	</div>
</div>


<?php
}
} else {
    ?>


<div id="accordionPos" class="m-1">
<?php
$btnClass = "";
    foreach ($pos as $key => $values) {
        $btnClass = TranslatorUtility::isPoTranslated($language, $key) ? "btn-primary" : "btn-danger";
        ?>
	<div class="m-1">
		<a class="btn <?php echo $btnClass ?>" data-toggle="collapse" href="#collapse<?php echo str_replace(".po", "", $key) ?>" role="button" aria-expanded="false" aria-controls="collapse<?php echo str_replace(".po", "", $key) ?>">
			<?php echo $key ?>
		</a>
	</div>
	<div class="collapse" id="collapse<?php echo str_replace(".po", "", $key) ?>">
		<div class="card card-body">
			<dl>
			<?php foreach ($values as $value) {?>
				<dt><?php echo $value ?></dt>
				<dd><?php echo __d(str_replace(".po", "", $key), $value) ?></dd>
			<?php }?>
			</dl>
		</div>
	</div>

<?php }?>
</div>

<?php }?>

<div class="mt-2">
	<a href="<?php echo Router::url(array('controller' => 'localesystem', 'action' => 'home')) ?>">
		<span class="fa fa-arrow-left"></span>
	</a>
</div>
