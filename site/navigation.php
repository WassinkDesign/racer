<?php
$selfExploded = explode('.php', basename($_SERVER['PHP_SELF']));
$currentSite = $selfExploded[0];
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="index.php">OORA RACER</a>
  <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="navbar-collapse collapse" id="navbarColor01" style="">
  <ul class="navbar-nav mr-auto">
    <li class="nav-item <?php if ($currentSite == "index"){echo'active';}?>">
          <a class="nav-link" href="index.php">Home</a>
    </li>
    <li class="nav-item <?php if ($currentSite == "standings"){echo'active';}?>">
      <a class="nav-link" href="standings.php">Standings</a>
    </li>
<?php
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)
{
?>
<li class="nav-item <?php if ($currentSite == "account"){echo'active';}?>">
  <a class="nav-link" href="account.php">Account</a>
</li>
<li class="nav-item <?php if ($currentSite == "scorekeep"){echo'active';}?>">
  <a class="nav-link" href="scorekeep.php">Scorekeep</a>
</li>
<?php
} else {

}
?>
<!-- general links -- shared with everyone -- center list -->



<?php
// Sign up/in/out links
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)
{
?>
<li class="nav-item">
  <a class="nav-link" href="logout.php">Sign Out</a>
</li>
<?php 
} else {
?>
<li class="nav-item <?php if ($currentSite == "signup"){echo'active';}?>">
  <a class="nav-link" href="signup.php">Sign Up <?php if ($currentSite == "signup"){echo'<span class="sr-only">(current)</span>';}?></a>
</li>
<li class="nav-item <?php if ($currentSite == "login"){echo'active';}?>">
  <a class="nav-link" href="login.php">Sign In <?php if ($currentSite == "login"){echo'<span class="sr-only">(current)</span>';}?></a>
</li>
<?php
}
?>
    </ul>
  </div>
</nav>

