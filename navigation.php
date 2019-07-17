<?php
require_once("control/init.php");

$selfExploded = explode('.php', basename($_SERVER['PHP_SELF']));
$currentSite = $selfExploded[0];
?>
<?php 
if ($signedIn === true && $admin === true) {
  echo "<ul id=\"settings-dd\" class=\"dropdown-content\">
    <li><a href=" . url_for("settings/index.php") . " class=\"grey darken-4 white-text\">Settings</a></li>
    <li><a href=" . url_for("settings/classes.php") . ">Classes</a></li>
    <li><a href=" . url_for("settings/events.php") . ">Events</a></li>
    <li><a href=" . url_for("settings/locations.php") . ">Locations</a></li>
    <li><a href=" . url_for("settings/types.php") . ">Race Types</a></li>
  </ul>";
}
?>
<nav class="green nav-extended" role="navigation">
    <div class="nav-wrapper container">
      <span class="brand-logo center"><?php echo $title; ?></span>
      <a href="#" data-target="nav-mobile" class="sidenav-trigger white-text"><i class="material-icons">menu</i></a>      
      <ul id="nav-mobile" class="sidenav">
        <li><span class="white-text green" id="menu-title">Racer</span></li>
      <li><a href="<?php echo url_for('index.php');?>">Home</a></li>
        <li><a href="<?php echo url_for('standings.php');?>">Standings</a></li>
        <?php
        if ($signedIn === true)
        {
        ?>
        <li><a class="nav-link" href="<?php echo url_for('account.php');?>">Account</a></li>
        <li><a class="nav-link" href="<?php echo url_for('vehicles.php');?>">Vehicles</a></li>
        <?php
        } else {

        }
        ?>
        <!-- general links -- shared with everyone -- center list -->



        <?php
        // Admin links
        if ($signedIn === true && $admin === true) {
          ?>
          <li><a class="nav-link" href="<?php echo url_for('scorekeep/index.php');?>">Scorekeep</a></li>
          <li><a class="dropdown-trigger" href="<?php echo url_for('settings/index.php');?>" 
              data-target="settings-dd">Settings<i class="material-icons right">arrow_drop_down</i></a></li>
          <?php
        }
        // Sign up/in/out links
        if ($signedIn === true)
        {
        ?>
        <li><a class="nav-link" href="<?php echo url_for('logout.php');?>">Sign Out</a></li>
        <?php 
        } else {
        ?>
        <li><a class="nav-link" href="<?php echo url_for('signup.php');?>">Sign Up</a></li>
        <li><a class="nav-link" href="<?php echo url_for('login.php');?>">Sign In</a></li>
        <?php
        }
        ?>
      </ul>
      
    </div>
    <?php
    if ($addURL != "") {
      ?>
        <div class="nav-content">
          <a class="btn-floating btn-large halfway-fab waves-effect waves-light red" href="<?php echo url_for($addURL);?>">
            <i class="material-icons">add</i>
          </a>
        </div>
      <?php
    }
    ?>
  </nav>

