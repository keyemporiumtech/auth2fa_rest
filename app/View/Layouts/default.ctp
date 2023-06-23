<?php
App::uses('LayouthomeConfig', 'modules/layouthome/config');
App::uses('Enables', 'Config/system');
App::uses("ProductionVisibilityUtility", "modules/layouthome/utility");
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

// READ VERSION BY COMPOSER
$CURRENT_VERSION = 'NON DEFINITO';
$LIB_VERSION = 'NON DEFINITO';
$string = file_get_contents(WWW_ROOT . "../../versioning.json");
if ($string === false) {
    debug("errore nella lettura del composer");
}

$json_a = json_decode($string, true);
if ($json_a === null) {
    debug("errore nella decodifica json del composer");
}

if (!empty($json_a['version'])) {
    $CURRENT_VERSION = $json_a['version'];
}

if (!empty($json_a['template'])) {
    $LIB_VERSION = $json_a['template'];
}
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo LayouthomeConfig::$TITLE ?>
	</title>
	<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
	<?php
echo $this->Html->meta('icon');

echo $this->Html->css('../bootstrap/mybootstrap');
echo $this->Html->css('../bootstrap/css/bootstrap');
echo $this->Html->script('../bootstrap/js/bootstrap.min');
echo $this->Html->css('../fontawesome/css/all');

echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
</head>
<body>
	<div class="container-fluid">
		<nav class="navbar navbar-dark bg-dark">
		  <a class="navbar-brand" href="<?php echo Router::url(array('controller' => 'pages', 'action' => '')); ?>">
		    <img src="<?php echo $this->base . "/img/logo.png" ?>" width="30" class="d-inline-block align-top" alt="" loading="lazy">
		    <?php echo LayouthomeConfig::$TITLE ?>
		  </a>
		  <a class="navbar-brand" href="<?php echo Router::url(array('controller' => 'prodmode', 'action' => 'layouts')); ?>">
		    Layout
		  </a>
		  <?php
if (!Enables::isProd()) {
    include_once ROOT . "/app/modules/layouthome/layouts/menu-default.ctp";
} else {
    include_once ROOT . "/app/modules/layouthome/layouts/menu-production.ctp";
}
?>
		</nav>

		<div id="content">

			<?php echo $this->Session->flash(); ?>

			<?php echo $this->fetch('content'); ?>
		</div>
		<div id="footer" class="bg-dark text-white p-2">
			<p>
			<?php echo LayouthomeConfig::$TITLE . " " . $CURRENT_VERSION ?> | <?php echo "TEMPLATE " . $LIB_VERSION ?> | <?php echo __d('cake_dev', 'CakePHP %s', Configure::version()); ?> | <?php echo __d('cake_dev', 'PHP %s', PHP_VERSION); ?>
			</p>
		</div>
	</div>
	<?php echo $this->element('sql_dump'); ?>

</body>
</html>
