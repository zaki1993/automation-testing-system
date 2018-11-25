<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" type="text/css" href="../../style/login.css">
  <title>Login</title>
</head>
<body>
  <script type="text/javascript">
    // close the div in 5 secs
    window.setTimeout("closeDiv();", 5000);

    function closeDiv(){
    document.getElementById("login-failed").style.display=" none";
    }
  </script>
  <?php

    require_once "authenticator.php";

    function login($username, $password) {
      $authenticator=new Authenticator($username, $password);
      $isAuthorized=$authenticator->login($username, $password);
      if ($isAuthorized) {
        $_SESSION["__userData"]=$authenticator->getUser();
        # redirect to home page
        echo '<script type="text/javascript">
                window.location = "../"
             </script>';
      } else {
        # create div with error message which will be closed after 5 seconds
        echo '<div class="imgcontainer" id="login-failed"><img src="dummy" alt="Incorrect username and password..!"</img></div>';
      }
    }

    # if the user access this page unlog it
    session_start();
    $_SESSION["__userData"]=NULL;
    if (isset($_POST["username"]) && isset($_POST["password"])) {
      login($_POST["username"], $_POST["password"]);  
    }
  ?>
  <form class="form-content animate" action="./" method="POST">
    <div class="imgcontainer">
      <img src="dummy" alt="Вход в системата">
    </div>
    <div class="container">
      <label for="username"><b>Потребителско име</b></label>
      <input type="text" placeholder="Въведи потребителско име" name="username" required>

      <label for="password"><b>Парола</b></label>
      <input type="password" placeholder="Въведи парола" name="password" required>
        
      <!-- Hack: set width to 50% and align the buttons on the same line -->
      <button type="submit">Вход</button><button type="reset" class="cancelbtn">Отказ</button>
    </div>
    </div>
  </form>
</body>
</html>