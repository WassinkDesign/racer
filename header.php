<!DOCTYPE html>
  <html>
    <head>
      <!--Import Google Icon Font-->
      <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
      <!--Import materialize.css-->
      <link type="text/css" rel="stylesheet" href="<?php echo url_for('css/materialize.min.css'); ?>"  media="screen,projection"/>
      <link type="text/css" rel="stylesheet" href="<?php echo url_for('css/style.css'); ?>" media="screen,projection"/>
      <title><?php echo $title?></title>

      <!--Let browser know website is optimized for mobile-->
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    </head>
<body>
<header>
<?php

include(include_url_for('navigation.php'));
?>    
</header>
<main>
<div class="container-fluid">