
<?php 
App::uses('ProductionVisibilityUtility', 'modules/layouthome/utility');

?>

<?php if(ProductionVisibilityUtility::verifyLogin()) {
	include_once ROOT. "/app/modules/layouthome/layouts/menu-default.ctp";
?>
  
  <a class="navbar-brand" href="<?php echo Router::url(array ('controller' => 'prodmode','action' => 'logout')); ?>">
    LOGOUT
  </a>
<?php } else { ?>

  <a class="navbar-brand" href="<?php echo Router::url(array ('controller' => 'prodmode','action' => 'login')); ?>">
    LOGIN
  </a>
  
<?php } ?>