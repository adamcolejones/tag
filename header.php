<?php  
	require 'includes/dbh.inc.php';
	session_start();
	date_default_timezone_set('America/Chicago');
	include 'includes/posts.inc.php';
	include 'includes/friends.inc.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="This is an example of a meta description.  This will often show up in search results.">
		<meta name=viewport content="width=device-width, initial-scale=1">
		<title>TAG</title>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>
		<div id="headerContainer">

<!-- If the user is logged in, set username and profile picture.-->
			<p>TAG</p>
			<?php 
				if (isset($_SESSION['userID'])) {
					$id = $_SESSION['userID'];
					$sqlImg = "SELECT * FROM users WHERE userid='$id'";
					$resultImg = mysqli_query($conn, $sqlImg);
					while ($rowImg = mysqli_fetch_assoc($resultImg)) {		
						if ($rowImg['profileimg'] == 1) {
							$filename = "profilepics/profile".$id."*";
							$fileinfo = glob($filename);
							$fileext = explode(".", $fileinfo[0]);
							$fileactualext = $fileext[1];
							echo "<div class=userPicture><img src='profilepics/profile".$id.".".$fileactualext."?".mt_rand()."'></div>";
						}
						else {
							echo "<div class='userPicture'><img src='profilepics/noUser.png'></div>";
						}
					}
					echo	'
							<div class="userName">'. $_SESSION['userUserName'] .'</div>

							<div class="DropDownMenu">
								<button class="DropDownButton">•••</button>
								<div class="DropDownContent">
									<a href="settings.php">Settings</a>
									<a href="includes/logout.inc.php">Logout</a>
								</div>
							</div>

							<div class="PostButton">
								<a href="post.php?type=none">Post</a>
							</div>

							';
				}

// If the user is not logged in, set default profile pic and give them the options to sign in or sign up

				else {
					echo 	'
							<div class="userPicture"><img src="profilepics/noUser.png"></div>

							<div id="loginForm">
								<form action="includes/login.inc.php" method="post">
									<input type="text" name="mailuid" placeholder="Username/E-mail">
									<input type="password" name="password" placeholder="Password">
									<button type="Submit" name="login-submit">Login</button>
								</form>
							</div>

							<div id="signupForm">
								<a href="signup.php">or Signup</a>
							</div>

							';
				}
			?>			
		</div>