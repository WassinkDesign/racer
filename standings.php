<?php
require_once("control/init.php");

$addURL = "";
$title = "Standings";

$standings = [
  ["VEHICLE"=>"61",
  "NAME"=>"Trevor Cross",
  "POINTS"=>"352"],
  ["VEHICLE"=>"21",
  "NAME"=>"Angie Wassink",
  "POINTS"=>"284"],
  ["VEHICLE"=>"769",
  "NAME"=>"Chad Koster",
  "POINTS"=>"240"]
];
include(include_url_for('header.php')); 
?>
<div class="container">
  <p class="line">NOTE: This is hard-coded data to view the layout and structure of the points standings page.</p>
<?php 
if ($signedIn === true) { ?>
<div class="section">
<h3>My Standings</h3>
  <table class="striped highlight z-depth-1">
    <tbody>
      <?php 
        echo table_horz_row("Points", "127");
        echo table_horz_row("Position in Class 2", "2");
        echo table_horz_row("Position in Series", "3");
      ?>
    </tbody>
  </table>
</div>
<?php } ?>
<h3>Points Leaders</h3>
<div class="section">
<h4>Overall</h4>
<table class="striped highlight z-depth-1">
  <?php echo table_heading_row(["Vehicle", "Driver", "Points"]);?>
  <tbody><?php
  foreach ($standings as $standing) {
      echo table_row([$standing["VEHICLE"], $standing["NAME"], $standing["POINTS"]]);
  } ?>
  </tbody>
</table>
</div>
<div class="section">
<h4>Class 2</h4>
<table class="striped highlight z-depth-1">
  <?php echo table_heading_row(["Vehicle", "Driver", "Points"]);?>
  <tbody><?php
  foreach ($standings as $standing) {
      echo table_row([$standing["VEHICLE"], $standing["NAME"], $standing["POINTS"]]);
  } ?>
  </tbody>
</table>
</div>
<div class="section">
<h4>Class 7</h4>
<table class="striped highlight z-depth-1">
  <?php echo table_heading_row(["Vehicle", "Driver", "Points"]);?>
  <tbody><?php
  foreach ($standings as $standing) {
      echo table_row([$standing["VEHICLE"], $standing["NAME"], $standing["POINTS"]]);
  } ?>
  </tbody>
</table>
</div>
</div>
<?php 
include(include_url_for('footer.php'));
?>