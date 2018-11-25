<!DOCTYPE html>
<html>
  <head>  
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../style/navbar.css">
  </head>
<body>
<?php

  function getRole($session) {
    $userData=unserialize($session["__userData"]);
    return $userData->getRole();
  }
?>
<div class="topnav">
  <?php
    if(getRole($_SESSION)) {
      echo '<a href="#home" class="active nav-link">Home</a>';
    }
  ?>
  <a href="#news" class="nav-link">News</a>
  <a href="#contact" class="nav-link">Contact</a>
  <a href="#about" class="nav-link">About</a>
  <a href="./login" class="nav-link logout-btn">Изход</a>
</div>

<div style="padding-left:16px">
  <h2>Top Navigation Example</h2>
  <p>Some content..</p>
</div>

</body>
</html>