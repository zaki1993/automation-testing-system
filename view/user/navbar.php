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
    }
  ?>
  <div class="dropdown">
    <button class="dropbtn">Домашни 
      <i class="fa fa-caret-down"></i>
    </button>
    <div class="dropdown-content">
      <?php 
        if($userRole === "admin") {
          echo '<a href="?page=homework/upload">Качи домашно</a>';
        }
      ?>
      <a href="?page=homework/view">Преглед на домашните</a>
    </div>
  </div> 
  <a href="view/user/login" class="nav-link logout-btn">Изход</a>
</div>
</body>
</html>