<?php
App::uses('Enables', 'Config/system');
App::uses("ProductionVisibilityUtility", "modules/layouthome/utility");

if (! Enables::isProd() || ProductionVisibilityUtility::verifyLogin()) {
	include_once ROOT . "/app/modules/layouthome/layouts/home/default.ctp";
} else {
	include_once ROOT . "/app/modules/layouthome/layouts/home/production.ctp";
}

