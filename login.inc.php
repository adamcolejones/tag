<?php

if(isset($_POST['login-submit'])) {
	
	require 'dbh.inc.php';

	$mailuid = $_POST['mailuid'];
	$password = $_POST['password'];

	if (empty($mailuid) || empty($password)) {
		header("Location: ../index.php?error");
		exit();
	}
	else {
		$sql = "SELECT * FROM users WHERE userName=? OR email=?;";
		$stmt = mysqli_stmt_init($conn);
		if (!mysqli_stmt_prepare($stmt, $sql)) {
			header("Location: ../index.php?error");
			exit();
		}
		else {
			mysqli_stmt_bind_param($stmt, "ss", $mailuid, $mailuid);
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);
			if ($row = mysqli_fetch_assoc($result)) {
				$pwdCheck = password_verify($password, $row['password']);
				if ($pwdCheck == false) {
					header("Location: ../index.php?error");
					exit();
				}
				else if ($pwdCheck == true) {
					session_start();
					$_SESSION['userID'] = $row['userid'];
					$_SESSION['userUserName'] = $row['userName'];

					header("Location: ../home.php?user=".$_SESSION['userUserName']."");  //get the url of the current page and send them there instead
					exit();
				}
				else {
					header("Location: ../index.php?error");
					exit();
				}
			}
			else {
				header("Location: ../index.php?error");
				exit();
			}
		}
	}

}
else {
	header("Location: ../index.php");
	exit();
}