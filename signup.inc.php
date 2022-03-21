<?php
date_default_timezone_set('America/Chicago');
if (isset($_POST['signup-submit'])) {
	require 'dbh.inc.php';
	$firstName = mysqli_real_escape_string($conn, $_POST['firstName']);
	$lastName = mysqli_real_escape_string($conn, $_POST['lastName']);
	$userName = mysqli_real_escape_string($conn, $_POST['userName']);
	$email = mysqli_real_escape_string($conn, $_POST['email']);
	$email = filter_var($email, FILTER_SANITIZE_EMAIL);
	$password = mysqli_real_escape_string($conn, $_POST['password']);
	$passwordRepeat = mysqli_real_escape_string($conn, $_POST['pwd-repeat']);
	$joined = mysqli_real_escape_string($conn, $_POST['date']);
// CHECKS TO SEE IF FIELDS ARE EMPTY
// RETURNS FIELDS THAT ARE NOT EMPTY
// EXCLUDES PASSWORD RETURNS FOR SECURITY
	if (empty($firstName) || empty($lastName) || empty($userName) || empty($email) || empty($password) || empty($passwordRepeat)) {
		header("Location: ../signup.php?error=emptyfields&firstName=".htmlspecialchars($firstName)."&lastName=".htmlspecialchars($lastName)."&userName=".htmlspecialchars($userName)."&email=".htmlspecialchars($email));
		exit();
	}
// CHECKS TO SEE IF USER ENTERED A CORRECT EMAIL AND RETURNS OTHER CORRECT FIELDS
	else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		header("Location: ../signup.php?error=invalidemail&firstName=".htmlspecialchars($firstName)."&lastName=".htmlspecialchars($lastName)."&userName=".htmlspecialchars($userName));
		exit();
	}
	else if ($password !== $passwordRepeat) {
		header("Location: ../signup.php?error=passwordcheck&firstName=".htmlspecialchars($firstName)."&lastName=".htmlspecialchars($lastName)."&email=".htmlspecialchars($email)."&userName=".htmlspecialchars($userName));
		exit();
	}
	else {
		$sql = "SELECT userName FROM users WHERE userName=?";
		$stmt = mysqli_stmt_init($conn);
		if (!mysqli_stmt_prepare($stmt, $sql)) {
			header("Location: ../signup.php?error");
			exit();
		}
		else {
			mysqli_stmt_bind_param($stmt, "s", $userName);
			mysqli_stmt_execute($stmt);
			// $resultCheck = mysqli_stmt_get_result($stmt);
			$resultCheck = mysqli_stmt_num_rows($stmt);
			if($resultCheck > 0) {
				header("Location: ../signup.php?error");
				exit();
			}
			else {
				$sql = "INSERT INTO users (firstName, lastName, userName, email, password, joined, profileimg, profilebanner) VALUES (?, ?, ?, ?, ?, ?, 0, 0)";
				$stmt = mysqli_stmt_init($conn);
				if (!mysqli_stmt_prepare($stmt, $sql)) {
					header("Location: ../signup.php?error");
					exit();
				}
				// If everything works, then this code will enter the fields into the users table
				else {
					$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
					mysqli_stmt_bind_param($stmt, "ssssss", $firstName, $lastName, $userName, $email, $hashedPassword, $joined);
					mysqli_stmt_execute($stmt);
					$sqlcreate = "SELECT * FROM users WHERE userName=? AND firstName=?";
					$stmtcreate = mysqli_stmt_init($conn);
					if (!mysqli_stmt_prepare($stmtcreate, $sqlcreate)) {
						echo "error";
					}
					else {
						mysqli_stmt_bind_param($stmtcreate, "ss", $userName, $firstName);
						mysqli_stmt_execute($stmtcreate);
						$resultcreate = mysqli_stmt_get_result($stmtcreate);
						//this code will make a directory for upload files
						$row = mysqli_fetch_assoc($resultcreate);		
						$userid = $row['userid'];
						mkdir('../posts/'.$userid.'', 0777, true);
						header("Location: ../index.php?signup=success");
						exit();
					}
				}
			}
		}
	}
	mysqli_stmt_close($stmt);
	mysqli_close($conn);
}
else {
	header("Location: ../signup.php");
			exit();
}