<?php
  require_once("phpscripts/config.php");

  $ip = $_SERVER["REMOTE_ADDR"];
  //echo $ip;

  if(isset($_POST['submit'])) {
    //echo "Thanks for clicking...";
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    if($username !== "" && $password !== "") {
      $result = logIn($username, $password, $ip);
      $message = $result;
    }else{
      $message = "Please fill in the required fields.";
    }
  }
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Kiija Gargarello | Login</title>
<link rel="stylesheet" href="css/app.css">
<link href="https://fonts.googleapis.com/css?family=Muli" rel="stylesheet">
</head>
<body>

<h1>Welcome</h1>
<p class="errorTxt"><?php if(!empty($message)){echo $message;} ?></p>
<form action="admin_login.php" method="post">
  <input type="text" name="username" value="" placeholder="Username" id="username">
  <input type="password" name="password" value="" placeholder="Password" id="password">
  <input type="submit" name="submit" value="Login" id="submit">
</form>

  </body>
</html>
