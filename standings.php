<?php
require_once("control/init.php");

$addURL = "";
$title = "Standings";

include(include_url_for('header.php')); 
?>
<div class="container">
<?php 
if ($signedIn === true) { ?>
<div class="section">
<h3>My Standings</h3>

<table class="striped highlight z-depth-1">
    <tr>
      <th scope="row">Points</th>
      <td>127</td>
    </tr>
    <tr>
      <th scope="row">Position in Class 2</th>
      <td>1</td>
    </tr>
    <tr>
      <th scope="row">Position in Series</th>
      <td>2</td>
    </tr>
</table>
</div>

<?php } ?>
<h3>Points Leaders</h3>

<div class="section">
<h4>Overall</h4>
<table class="striped highlight z-depth-1">
  <thead class="grey darken-4">
    <tr>
      <th scope="col" class="white-text">Vehicle</th>
      <th scope="col" class="white-text">Driver</th>
      <th scope="col" class="white-text">Points</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row">61</th>
      <td>Trevor Cross</td>
      <td>226</td>
    </tr>
    <tr>
      <th scope="row">21</th>
      <td>Angie Wassink</td>
      <td>127</td>
    </tr>
    <tr>
      <th scope="row">769</th>
      <td>Chad Koster</td>
      <td>120</td>
    </tr>
  </tbody>
</table>
</div>

<div class="section">
<h4>Class 1</h4>
<table class="striped highlight z-depth-1">
<thead class="grey darken-4">
    <tr>
      <th scope="col" class="white-text">Vehicle</th>
      <th scope="col" class="white-text">Driver</th>
      <th scope="col" class="white-text">Points</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row">61</th>
      <td>Trevor Cross</td>
      <td>226</td>
    </tr>
    <tr>
      <th scope="row">21</th>
      <td>Angie Wassink</td>
      <td>127</td>
    </tr>
    <tr>
      <th scope="row">769</th>
      <td>Chad Koster</td>
      <td>120</td>
    </tr>
  </tbody>
</table>

</div>



<div class="section">

<h4>Class 4</h4>

<table class="striped highlight z-depth-1">

<thead class="grey darken-4">

    <tr>

      <th scope="col" class="white-text">Vehicle</th>

      <th scope="col" class="white-text">Driver</th>

      <th scope="col" class="white-text">Points</th>

    </tr>

  </thead>

  <tbody>

    <tr>

      <th scope="row">61</th>

      <td>Trevor Cross</td>

      <td>226</td>

    </tr>

    <tr>

      <th scope="row">21</th>

      <td>Angie Wassink</td>

      <td>127</td>

    </tr>

    <tr>

      <th scope="row">769</th>

      <td>Chad Koster</td>

      <td>120</td>

    </tr>

  </tbody>

</table>

</div>

</div>

<?php 

include(include_url_for('footer.php'));

?>