

<ul>
	<li><strong>TIMEZONE DB :</strong> <?php echo $timezoneDb?> - <strong>Riga da db : </strong> <?php echo $actualDb?></li>
	<li><strong>TIMEZONE Server :</strong> <?php echo $timezoneSystem?> - <strong>Data di sistema :</strong> <?php echo $actualSystem?></li>
</ul>

<div class="alert alert-dark mt-1" role="alert">
  	<h4 class="alert-heading">appendTimezone($data)</h4>
	<footer class="blockquote-footer">static function <cite title="Source Title">Aggiunge il timezone di sistema ad una data</cite></footer>
</div>

<div class="ml-5 text-success font-weight-bold">TIMEZONE DB : <?php echo $dbTz1 ?></div>
<div class="ml-5 text-success font-weight-bold">TIMEZONE Server : <?php echo $sysTz1 ?></div>



<div class="alert alert-dark mt-1" role="alert">
  	<h4 class="alert-heading">appendTimezoneSpecifi($data, $tz)</h4>
	<footer class="blockquote-footer">static function <cite title="Source Title">Aggiunge il timezone specificato ad una data</cite></footer>
</div>

<div class="ml-5 text-success font-weight-bold">TIMEZONE DB : <?php echo $dbTz2 ?></div>
<div class="ml-5 text-success font-weight-bold">TIMEZONE Server : <?php echo $sysTz2 ?></div>



<div class="alert alert-dark mt-1" role="alert">
  	<h4 class="alert-heading">convertDateTimezoneToServer($data, $fromTZ, $withP=true, $format='Y-m-d H:i:s')</h4>
	<footer class="blockquote-footer">static function <cite title="Source Title">Ritorna una data dal timezone specificato a quello del sistema</cite></footer>
</div>

<div class="ml-5 text-success font-weight-bold">TIMEZONE DB : <?php echo $convertDbToSystem ?> - <u>non in cifre</u> <?php echo $convertDbToSystemNOP?></div>

<hr/>
<div class="alert alert-dark mt-1" role="alert">
  	<h4 class="alert-heading">ESEMPI DI CONVERSIONE con <i>convertDateTimezoneToServer</i> e <i>convertDateTimezoneToTimezone</i></h4>
</div>

<div class="ml-5"><strong>DATA IN Timezone <?php echo $tz1?></strong> : <?php echo $dta1 ?></div>
<div class="ml-5"><strong>DATA IN Timezone <?php echo $tz2?></strong> : <?php echo $dta2 ?></div>

<h5 class="mt-2">Conversione in <?php echo $timezoneSystem ?></h5>
<div class="ml-5"><strong>DATA 1 :</strong> <?php echo $convert1 ?> - <u>non in cifre</u> <?php echo $convert1NOP?></div>
<div class="ml-5"><strong>DATA 2 :</strong> <?php echo $convert2 ?> - <u>non in cifre</u> <?php echo $convert2NOP?></div>

<h5 class="mt-2">Conversione in <?php echo $tz1 ?></h5>
<div class="ml-5"><strong>DATA 1 :</strong> <?php echo $convert1A ?> - <u>non in cifre</u> <?php echo $convert1ANOP?></div>
<div class="ml-5"><strong>DATA 2 :</strong> <?php echo $convert2A ?> - <u>non in cifre</u> <?php echo $convert2ANOP?></div>

<h5 class="mt-2">Conversione in <?php echo $tz2 ?></h5>
<div class="ml-5"><strong>DATA 1 :</strong> <?php echo $convert1B ?> - <u>non in cifre</u> <?php echo $convert1BNOP?></div>
<div class="ml-5"><strong>DATA 2 :</strong> <?php echo $convert2B ?> - <u>non in cifre</u> <?php echo $convert2BNOP?></div>

<div class="mt-2">
	<a href="<?php echo Router::url(array ('controller' => 'coreutils','action' => 'home'))?>">
		<span class="fa fa-arrow-left"></span>
	</a>
</div>