<?php
	require_once('phpscripts/config.php');
	confirm_logged_in(); //turn on to prevent ability to login via url
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Kiija Login Page</title>
<link rel="stylesheet" href="css/app.css">
<link href="https://fonts.googleapis.com/css?family=Muli" rel="stylesheet">
</head>
<body>

<?php
  if($_SESSION['current_hour'] <= "11") { //If current_hour is <= then say Good morning
    $greeting = "Rise and Shine! Have a great day ";
  }elseif($_SESSION['current_hour'] >= "12" && $_SESSION['current_hour'] <= "16") { //If current_hour is between 12 & 16, say Good afternoon
    $greeting = "The day is almost over! You can make it ";
  }elseif($_SESSION['current_hour'] >= "17") { // if current_hour is >= 17 say good evening
    $greeting = "You made it through the day! Sweet dreams ";

	}
?>

	<h1><?php echo $greeting; echo $_SESSION['users_fname']; ?>.</h1>

	<br>
	<p id="lastLogin"><?php echo $_SESSION['users_fname']; ?>, you last logged in on <?php echo $_SESSION['users_lastLogin']; ?>.</p>

</body>
</html>
