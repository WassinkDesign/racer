<?php
include('signed-in-check.php');

$title = "Welcome";
include('header.php'); 

?>
<div class="page-header">
    <h1>OORA Standings</h1>
</div>
<?php 
if ($signedIn == true) { ?>
<h2>My Standings</h2>
<table class="table table-striped">
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
<?php } ?>
<h2>Points Leaders</h2>
<h3>Overall</h3>
<table class="table table-striped">
  <thead class="thead-dark">
    <tr>
      <th scope="col">Vehicle</th>
      <th scope="col">Driver</th>
      <th scope="col">Points</th>
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
<h3>Class 1</h3>
<table class="table table-striped">
  <thead class="thead-dark">
    <tr>
      <th scope="col">Vehicle</th>
      <th scope="col">Driver</th>
      <th scope="col">Points</th>
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
<h3>Class 4</h3>
<table class="table table-striped">
  <thead class="thead-dark">
    <tr>
      <th scope="col">Vehicle</th>
      <th scope="col">Driver</th>
      <th scope="col">Points</th>
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