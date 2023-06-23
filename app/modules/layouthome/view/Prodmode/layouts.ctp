<h2>BOOTSTRAP LAYOUT</h2>

<!-- COLORS -->
<div id="accordionColors" class="m-3">
  <div class="card">
    <div class="card-header" id="headingColors">
      <h5 class="mb-0">
        <button class="btn btn-primary" data-toggle="collapse" data-target="#collapseColors" aria-expanded="false"
          aria-controls="collapseColors">
          Colors
        </button>
      </h5>
    </div>

    <div id="collapseColors" class="collapse" aria-labelledby="headingColors" data-parent="#accordionColors">
      <div class="card-body">
		<?php include_once ROOT."/app/modules/layouthome/layouts/bootstrap/colors.ctp";?>        
      </div>
    </div>
  </div>
</div>


<!-- BUTTONS -->
<div id="accordionButtons" class="m-3">
  <div class="card">
    <div class="card-header" id="headingButtons">
      <h5 class="mb-0">
        <button class="btn btn-primary" data-toggle="collapse" data-target="#collapseButtons" aria-expanded="false"
          aria-controls="collapseButtons">
          Buttons
        </button>
      </h5>
    </div>

    <div id="collapseButtons" class="collapse" aria-labelledby="headingButtons" data-parent="#accordionButtons">
      <div class="card-body">
		<?php include_once ROOT."/app/modules/layouthome/layouts/bootstrap/buttons.ctp";?>        
      </div>
    </div>
  </div>
</div>

<!-- BADGES -->
<div id="accordionBadges" class="m-3">
  <div class="card">
    <div class="card-header" id="headingBadges">
      <h5 class="mb-0">
        <button class="btn btn-primary" data-toggle="collapse" data-target="#collapseBadges" aria-expanded="false"
          aria-controls="collapseBadges">
          Badges
        </button>
      </h5>
    </div>

    <div id="collapseBadges" class="collapse" aria-labelledby="headingBadges" data-parent="#accordionBadges">
      <div class="card-body">
		<?php include_once ROOT."/app/modules/layouthome/layouts/bootstrap/badges.ctp";?>        
      </div>
    </div>
  </div>
</div>


<!-- ALERTS -->
<div id="accordionAlerts" class="m-3">
  <div class="card">
    <div class="card-header" id="headingAlerts">
      <h5 class="mb-0">
        <button class="btn btn-primary" data-toggle="collapse" data-target="#collapseAlerts" aria-expanded="false"
          aria-controls="collapseAlerts">
          Alerts
        </button>
      </h5>
    </div>

    <div id="collapseAlerts" class="collapse" aria-labelledby="headingAlerts" data-parent="#accordionAlerts">
      <div class="card-body">
		<?php include_once ROOT."/app/modules/layouthome/layouts/bootstrap/alerts.ctp";?>        
      </div>
    </div>
  </div>
</div>

<!-- CARDS -->
<div id="accordionCards" class="m-3">
  <div class="card">
    <div class="card-header" id="headingCards">
      <h5 class="mb-0">
        <button class="btn btn-primary" data-toggle="collapse" data-target="#collapseCards" aria-expanded="false"
          aria-controls="collapseCards">
          Cards
        </button>
      </h5>
    </div>

    <div id="collapseCards" class="collapse" aria-labelledby="headingCards" data-parent="#accordionCards">
      <div class="card-body">
		<?php include_once ROOT."/app/modules/layouthome/layouts/bootstrap/cards.ctp";?>        
      </div>
    </div>
  </div>
</div>