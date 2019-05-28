<?php
include_once('../control/signed-in-check.php');

$selfExploded = explode('.php', basename($_SERVER['PHP_SELF']));
$currentSite = $selfExploded[0];
?>

<nav class="light-blue lighten-1" role="navigation">
    <div class="nav-wrapper container">
      <!-- <a id="logo-container" href="#" class="brand-logo">Racer</a> -->
      <ul class="right hide-on-med-and-down">
        <li><a href="index.php">Home</a></li>
        <li><a href="standings.php">Standings</a></li>
        <?php
        if ($signedIn === true)
        {
        ?>
        <li><a class="nav-link" href="account.php">Account</a></li>
        <li><a class="nav-link" href="scorekeep.php">Scorekeep</a></li>
        <?php
        } else {

        }
        ?>
        <!-- general links -- shared with everyone -- center list -->



        <?php
        // Sign up/in/out links
        if ($signedIn === true)
        {
        ?>
        <li><a class="nav-link" href="settings-main.php">Settings</a></li>
        <li><a class="nav-link" href="logout.php">Sign Out</a></li>
        <?php 
        } else {
        ?>
        <li><a class="nav-link" href="signup.php">Sign Up</a></li>
        <li><a class="nav-link" href="login.php">Sign In</a></li>
        <?php
        }
        ?>
      </ul>

      <ul id="nav-mobile" class="sidenav">
      <li><a href="index.php">Home</a></li>
        <li><a href="standings.php">Standings</a></li>
        <?php
        if ($signedIn === true)
        {
        ?>
        <li><a class="nav-link" href="account.php">Account</a></li>
        <li><a class="nav-link" href="scorekeep.php">Scorekeep</a></li>
        <?php
        } else {

        }
        ?>
        <!-- general links -- shared with everyone -- center list -->



        <?php
        // Sign up/in/out links
        if ($signedIn === true)
        {
        ?>
        <li><a class="nav-link" href="settings-main.php">Settings</a></li>
        <li><a class="nav-link" href="logout.php">Sign Out</a></li>
        <?php 
        } else {
        ?>
        <li><a class="nav-link" href="signup.php">Sign Up</a></li>
        <li><a class="nav-link" href="login.php">Sign In</a></li>
        <?php
        }
        ?>
      </ul>
      <a href="#" data-target="nav-mobile" class="sidenav-trigger">
      <i class="material-icons">menu</i></a>
    </div>
  </nav>

