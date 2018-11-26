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

    $userData=$session['__userData'];	
    return $userData->getRole();
  }
?>
<div class="topnav">
  <?php
    $userRole=getRole($_SESSION);
    if($userRole === "admin") {
      echo '<a href="?page=admin/config" class="nav-link">Конфигурация</a>';
      echo '<a href="?page=admin/users" class="nav-link">Потребители</a>';
    }
  ?>
  <a href="?page=homeworks" class="nav-link">Домашни</a>
  <a href="./login" class="nav-link logout-btn">Изход</a>
</div>
</body>
</html>