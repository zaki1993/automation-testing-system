<!DOCTYPE html>
<html>
<head>
	<title>Регистрация</title>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="../../style/forms.css">
</head>
<body>
	<?php
	    # if the user access this page unlog it
	    session_start();
	    $_SESSION['__userData']=NULL;

	    if(isset($_POST["username"]) && isset($_POST["faculty-number"]) && isset($_POST["password"]) && isset($_POST["confirm-password"])) {
		    require_once "register.php";
		    $registrator=new Registrator();
		    $registrator->register($_POST["username"], $_POST["faculty-number"], $_POST["password"], $_POST["confirm-password"]);
	    }
	?>
	<form class="form-content animate" action="./" method="POST">
    <div class="imgcontainer">
      <img src="dummy" alt="Регистрация">
    </div>
    <div class="container">
      <label for="username"><b>Потребителско име</b></label>
      <input type="text" placeholder="Въведи потребителско име" name="username" required>

      <label for="faculty-number"><b>Факултетен номер</b></label>
      <input type="text" placeholder="Въведи факултетен номер" name="faculty-number" required pattern="[0-9]{5}">

      <label for="password"><b>Парола</b></label>
      <input type="password" placeholder="Въведи парола" name="password" required>

      <label for="confirm-password"><b>Повтори паролата</b></label>
      <input type="password" placeholder="Въведи паролата отново" name="confirm-password" required>
        
      <button type="submit">Регистрация</button>
      <a href="../login" type="reset" class="registerbtn">Вход</a>
    </div>
    </div>
  </form>
</body>
</html>