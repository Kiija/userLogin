<?php
	function logIn($username, $password, $ip) {
		require_once("connect.php");
		$username = mysqli_real_escape_string($link, $username);
		$password = mysqli_real_escape_string($link, $password);
		$loginString = "SELECT * FROM tbl_user WHERE user_uname='{$username}' AND user_pass='{$password}'";
		$user_set = mysqli_query($link, $loginString);

		if(mysqli_num_rows($user_set)) {
			$found_user = mysqli_fetch_array($user_set, MYSQLI_ASSOC);
			$id = $found_user['user_id'];
			$_SESSION['users_id'] = $id;
			$_SESSION['users_name'] = $found_user['user_uname'];
			$_SESSION['users_fname'] = $found_user['user_fname'];//need for personalized greeting
			if(mysqli_query($link, $loginString)) {
				$updateString = "UPDATE tbl_user SET user_ip = '{$ip}' WHERE user_id = {$id}";
				$updateQuery = mysqli_query($link, $updateString);

				//Last Date and Time user logged in
				date_default_timezone_set('America/Toronto');
				$currentDate = date('l F jS Y, \a\t h:ia T');
				$_SESSION['current_hour'] = date('G');
				$changeTime = "UPDATE tbl_user SET user_time = '$currentDate' WHERE user_id = {$id}"; 
				$timestamp = $found_user['user_time'];
				$_SESSION['users_lastLogin'] = $timestamp;
			}
			redirect_to("admin_index.php");
		}else{
			$message = "Uhoh seems like your username or password was wrong. Try Again!";
			return $message;

		}
////////Attempt to get the lockout and adding failed login attempts to user_loginAttempts....it didnt work
//
// 		function addLoginAttempt($value) {
//
//    //Increase number of attempts. Set last login attempt if required.
//
//    $q = "SELECT * FROM tbl_user WHERE ip = '$value'";
//    $result = mysql_query($q, $link);
//    $data = mysql_fetch_array($result);
//
//    if($data)
//    {
//      $attempts = $data["attempts"]+1;
//
//      if($attempts==3) {
//        $q = "UPDATE tbl_user SET attempts=".$attempts.", lastlogin=NOW() WHERE ip = '$value'";
//        $result = mysql_query($q, $link);
//      }
//      else {
//        $q = "UPDATE tbl_user SET attempts=".$attempts." WHERE ip = '$value'";
//        $result = mysql_query($q, $link);
//      }
//    }
//    else {
//      $q = "INSERT INTO tbl_user (user_loginAttempts,user_ip,user_lastLogin) values (1, '$value', NOW())";
//      $result = mysql_query($q, $link);
//    }
// }
//
// function clearLoginAttempts($value) {
//   $q = "UPDATE tbl_user SET attempts = 0 WHERE ip = '$value'";
//   return mysql_query($q, $link);
// }

///////lockout user after failing to login three times by adding +1 to user_loginAttempts for each unsuccessful attempt
		$lockoutString = "SELECT * FROM tbl_user WHERE user_uname = '{$username}' OR user_pass = '{$password}'"; //check if password matches $password and if username matches $username
		$result = mysqli_query($link, $lockoutString); //store results of $lockoutString in $result
		$matchFound = mysqli_num_rows($result); // if password and username match $matchFound=2, if one matches $matchFound=1, if neither matches $matchFound=0
		if($matchFound < 2) { //if $matchFound<2, no matches (password and/or username is incorrect)
			$updateLogin = "UPDATE tbl_user SET user_loginAttempts = user_loginAttempts + 1 WHERE user_ip = '$ip'"; //add +1 to user_loginAttempts column of perpetrating $username
			$updateLoginQuery = mysqli_query($link, $updateLogin);

			$attemptsString = "SELECT user_loginAttempts FROM tbl_user WHERE user_ip ='$ip' AND user_loginAttempts >= '3'"; //select ip of user with more than 3 unsuccessful login attempts
			$attemptsQuery = mysqli_query($link, $attemptsString);
			$threeAttempts = mysqli_num_rows($attemptsQuery);
			//echo $threeAttempts;
			if($threeAttempts == "1") { //if there is an ip with more than 3 unsuccessful login attempts, echo
				//echo 'You have reached 3 failed login attempts.'; ----> this was creating an error with the functions page
				//redirect_to("admin_failed.php"); ---> this would come up even when i entered the correct login information
			}

		}elseif($matchFound = 2) { //if $matchfound=2, password & username both correct
			$updateLogin = "UPDATE tbl_user SET user_loginAttempts = 0 WHERE user_ip = '$ip'"; //reset value of user_loginAttempts to 0 of successful $username
			$updateLoginQuery = mysqli_query($link, $updateLogin);
		}

		//I couldn't get user_loginAttempts to reset to 0 when the user sucessfully logged in, therefore my "you have reach 3 failed login attempts" kept coming up
		//I wasn't able to figure out how to locke out a user after 3 failed login attempts, you can see the commented out code above where i tried...i think

		mysqli_close($link);
	}
?>
