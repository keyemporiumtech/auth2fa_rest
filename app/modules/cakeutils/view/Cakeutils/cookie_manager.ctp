<div class="alert alert-success mt-1" role="alert">
	<h4 class="alert-heading">Gestione dei cookie</h4>

</div>


<div class="alert alert-dark mt-1" role="alert">
  	<h4 class="alert-heading">Flag</h4>

	<div class="mt-2">
		isNecessary: <?php echo $isNecessary ?>
		<a class="ml-2 btn btn-sm btn-danger"
			href="<?php echo Router::url(array('controller' => 'cakeutils', 'action' => 'setFlag', "?" => array("type" => EnumCookieType::NECESSARY, "flag" => "0"))) ?>">
			<span class="fa fa-toggle-off"></span>
		</a>
		<a class="ml-2 btn btn-sm btn-success"
			href="<?php echo Router::url(array('controller' => 'cakeutils', 'action' => 'setFlag', "?" => array("type" => EnumCookieType::NECESSARY, "flag" => "1"))) ?>">
			<span class="fa fa-toggle-on"></span>
		</a>
	</div>
	<div class="mt-2">
		isPreference: <?php echo $isPreference ?>
		<a class="ml-2 btn btn-sm btn-danger"
			href="<?php echo Router::url(array('controller' => 'cakeutils', 'action' => 'setFlag', "?" => array("type" => EnumCookieType::PREFERENCE, "flag" => "0"))) ?>">
			<span class="fa fa-toggle-off"></span>
		</a>
		<a class="ml-2 btn btn-sm btn-success"
			href="<?php echo Router::url(array('controller' => 'cakeutils', 'action' => 'setFlag', "?" => array("type" => EnumCookieType::PREFERENCE, "flag" => "1"))) ?>">
			<span class="fa fa-toggle-on"></span>
		</a>
	</div>
	<div class="mt-2">
		isStatistic: <?php echo $isStatistic ?>
		<a class="ml-2 btn btn-sm btn-danger"
			href="<?php echo Router::url(array('controller' => 'cakeutils', 'action' => 'setFlag', "?" => array("type" => EnumCookieType::STATISTIC, "flag" => "0"))) ?>">
			<span class="fa fa-toggle-off"></span>
		</a>
		<a class="ml-2 btn btn-sm btn-success"
			href="<?php echo Router::url(array('controller' => 'cakeutils', 'action' => 'setFlag', "?" => array("type" => EnumCookieType::STATISTIC, "flag" => "1"))) ?>">
			<span class="fa fa-toggle-on"></span>
		</a>
	</div>
	<div class="mt-2">
		isMarketing: <?php echo $isMarketing ?>
		<a class="ml-2 btn btn-sm btn-danger"
			href="<?php echo Router::url(array('controller' => 'cakeutils', 'action' => 'setFlag', "?" => array("type" => EnumCookieType::MARKETING, "flag" => "0"))) ?>">
			<span class="fa fa-toggle-off"></span>
		</a>
		<a class="ml-2 btn btn-sm btn-success"
			href="<?php echo Router::url(array('controller' => 'cakeutils', 'action' => 'setFlag', "?" => array("type" => EnumCookieType::MARKETING, "flag" => "1"))) ?>">
			<span class="fa fa-toggle-on"></span>
		</a>
	</div>
	<div class="mt-2">
		isNotClassified: <?php echo $isNotClassified ?>
		<a class="ml-2 btn btn-sm btn-danger"
			href="<?php echo Router::url(array('controller' => 'cakeutils', 'action' => 'setFlag', "?" => array("type" => EnumCookieType::NOT_CLASSIFIED, "flag" => "0"))) ?>">
			<span class="fa fa-toggle-off"></span>
		</a>
		<a class="ml-2 btn btn-sm btn-success"
			href="<?php echo Router::url(array('controller' => 'cakeutils', 'action' => 'setFlag', "?" => array("type" => EnumCookieType::NOT_CLASSIFIED, "flag" => "1"))) ?>">
			<span class="fa fa-toggle-on"></span>
		</a>
	</div>
</div>


<div class="alert alert-dark mt-1" role="alert">
  	<h4 class="alert-heading">List</h4>

	<h5>Necessary</h5>
	<?php debug($listNecessary)?>

	<h5>Preference</h5>
	<?php debug($listPreference)?>

	<h5>Statistic</h5>
	<?php debug($listStatistic)?>

	<h5>Marketing</h5>
	<?php debug($listMarketing)?>

	<h5>Not Classified</h5>
	<?php debug($listNotClassified)?>
</div>

<div class="alert alert-dark mt-1" role="alert">
  	<h4 class="alert-heading">Instanced</h4>

	<h5>Necessary</h5>
	<?php debug($listInstancedNecessary)?>

	<h5>Preference</h5>
	<?php debug($listInstancedPreference)?>

	<h5>Statistic</h5>
	<?php debug($listInstancedStatistic)?>

	<h5>Marketing</h5>
	<?php debug($listInstancedMarketing)?>

	<h5>Not Classified</h5>
	<?php debug($listInstancedNotClassified)?>
</div>
