<div class="alert alert-dark mt-2" role="alert">
  	<h4 class="alert-heading"><?php $mailDto->mail['subject']?></h4>


	<strong>TO</strong><br/>
	<?php foreach ($mailDto->destinators as $receiver) {
    echo "[ " . $receiver['receivername'] . " (" . $receiver['receiveremail'] . ") ]<br/>";
}?>

	<strong>CC</strong><br/>
	<?php if(!empty($mailDto->cc)) { foreach ($mailDto->cc as $receiver) {
    echo "[ " . $receiver['receivername'] . " (" . $receiver['receiveremail'] . ") ]<br/>";
}}?>

	<strong>CCN</strong><br/>
	<?php if(!empty($mailDto->ccn)) { foreach ($mailDto->ccn as $receiver) {
    echo "[ " . $receiver['receivername'] . " (" . $receiver['receiveremail'] . ") ]<br/>";
}}?>

	<?php echo $mailDto->html ?>
	<hr/>

	<strong>ALLEGATI</strong><br/>
	<?php if(!empty($mailDto->attachments)) { foreach ($mailDto->attachments as $mailattachment) {
    $attachment = $mailattachment;
    echo "<a href=\"" . FileUtility::getWebrootFile($attachment['path']) . "\" target=\"_blank\">" . $attachment['name'] . "</a><br/>";
}}?>
</div>

<div class="mt-2">
	<a href="<?php echo Router::url(array('controller' => 'communication', 'action' => 'home')) ?>">
		<span class="fa fa-arrow-left"></span>
	</a>
</div>