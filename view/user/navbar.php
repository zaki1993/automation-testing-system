<!DOCTYPE html>
<html>
  <head>  
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="view/style/navbar.css">
  </head>
<body>
<?php
  $userRole=getRole($_SESSION);
?>
<div class="topnav">
  <?php
    if($userRole === "admin") {
      echo '<a href="?page=admin/config" class="nav-link">Конфигурация</a>';
      echo '<a href="?page=admin/users" class="nav-link">Потребители</a>';
      echo '<a href="?page=homework/upload" class="nav-link">Качи домашно</a>';
    }
  ?>
  <a href="?page=homework/view" class="nav-link">Преглед на домашните</a>
  <a href="?page=ratings" class="nav-link">Класиране</a>

  <a href="view/user/login" class="nav-link logout-btn">Изход</a>
</div>
</body>
</html>