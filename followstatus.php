<?php
	require "header.php";
/*
Get userid
Get otherid
change status
The link to this page should determine what the satus wants to be changed to
*/

	if (isset($_SESSION['userID'])) {
		$userid = $_SESSION['userID'];
		$otherusername = $_GET["user"];
		$newstatus = $_GET["set"];
		$date = date("Y-m-d H:i:s");
		$otherusernameSQL = "SELECT * FROM users WHERE userName=?"; // $otheruserName
		$otherusernameSTMT = mysqli_stmt_init($conn);
		if (!mysqli_stmt_prepare($otherusernameSTMT, $otherusernameSQL)) {
			echo "error";
		}
		else {
			mysqli_stmt_bind_param($otherusernameSTMT, "s", $otherusername);
			mysqli_stmt_execute($otherusernameSTMT);
			$otherusernameRESULT = mysqli_stmt_get_result($otherusernameSTMT);
			while ($otherusernameROW = mysqli_fetch_assoc($otherusernameRESULT)) {
				$otheruserid = $otherusernameROW['userid'];
				$friendSQL = "SELECT * FROM friends WHERE ((user1 = ?) OR (user2 = ?)) AND ((user1 = ?) OR (user2 = ?))";
				$friendSTMT = mysqli_stmt_init($conn);
				if (!mysqli_stmt_prepare($friendSTMT, $friendSQL)) {
					echo "error";
				}
				else {
					mysqli_stmt_bind_param($friendSTMT, "iiii", $_SESSION['userID'], $_SESSION['userID'], $otheruserid, $otheruserid);
					mysqli_stmt_execute($friendSTMT);
					$friendRESULT = mysqli_stmt_get_result($friendSTMT);
				// when there is not a pre-existing relationship
					if (mysqli_num_rows($friendRESULT) == 0) {
						// create space in the friends table for both users
						// set the new relationship status
						// follow,			nothing-1
						if (($userid < $otheruserid) AND ($newstatus == 'follow')) {
							$newrelationSQL = "INSERT INTO friends (user1, user2, status, statusdate) VALUES (?, ?, 1, ?)";
							$newrelationSTMT = mysqli_stmt_init($conn);
							if (!mysqli_stmt_prepare($newrelationSTMT, $newrelationSQL)) {
								header("Location: home.php?user=".$otherusername."");  // eventually change this to get the same page where the user was previously
							}
							else {
								mysqli_stmt_bind_param($newrelationSTMT, "iis", $userid, $otheruserid, $date);
								mysqli_stmt_execute($newrelationSTMT);
								header("Location: home.php?user=".$otherusername."");
							}	
						}
						// block, 			nothing-4
						else if (($userid < $otheruserid) AND ($newstatus == 'block')) {
							$newrelationSQL = "INSERT INTO friends (user1, user2, status, statusdate) VALUES (?, ?, 4, ?)";
							$newrelationSTMT = mysqli_stmt_init($conn);
							if (!mysqli_stmt_prepare($newrelationSTMT, $newrelationSQL)) {
								header("Location: home.php?user=".$otherusername."");  // eventually change this to get the same page where the user was previously
							}
							else {
								mysqli_stmt_bind_param($newrelationSTMT, "iis", $userid, $otheruserid, $date);
								mysqli_stmt_execute($newrelationSTMT);
								header("Location: home.php?user=".$otherusername."");
							}	
						}
						else if (($otheruserid < $userid) AND ($newstatus == 'follow')) {
							$newrelationSQL = "INSERT INTO friends (user1, user2, status, statusdate) VALUES (?, ?, 2, ?)";
							$newrelationSTMT = mysqli_stmt_init($conn);
							if (!mysqli_stmt_prepare($newrelationSTMT, $newrelationSQL)) {
								header("Location: home.php?user=".$otherusername."");  // eventually change this to get the same page where the user was previously
							}
							else {
								mysqli_stmt_bind_param($newrelationSTMT, "iis", $otheruserid, $userid, $date);
								mysqli_stmt_execute($newrelationSTMT);
								header("Location: home.php?user=".$otherusername."");
							}
						}
						else if (($otheruserid < $userid) AND ($newstatus == 'block')) {
							$newrelationSQL = "INSERT INTO friends (user1, user2, status, statusdate) VALUES (?, ?, 5, ?)";
							$newrelationSTMT = mysqli_stmt_init($conn);
							if (!mysqli_stmt_prepare($newrelationSTMT, $newrelationSQL)) {
								header("Location: home.php?user=".$otherusername."");  // eventually change this to get the same page where the user was previously
							}
							else {
								mysqli_stmt_bind_param($newrelationSTMT, "iis", $otheruserid, $userid, $date);
								mysqli_stmt_execute($newrelationSTMT);
								header("Location: home.php?user=".$otherusername."");
							}
						}
						else {

						}
					}
				// when there is a pre-existing relationship
					else {
						while ($friendROW = mysqli_fetch_assoc($friendRESULT)) {
							$relationid = $friendROW['relationid'];
							$user1 = $friendROW['user1'];
							$user2 = $friendROW['user2'];
							$currentstatus = $friendROW['status'];
							if ($userid == $user1) {
								// follow, 			0-1
								if (($newstatus == 'follow') AND ($currentstatus == 0)) {
									$newrelationSQL = "UPDATE friends SET status = 1, statusdate = ? WHERE relationid = ?";
									$newrelationSTMT = mysqli_stmt_init($conn);
									if (!mysqli_stmt_prepare($newrelationSTMT, $newrelationSQL)) {
										header("Location: home.php?user=".$otherusername."");  // eventually change this to get the same page where the user was previously
									}
									else {
										mysqli_stmt_bind_param($newrelationSTMT, "si", $date, $relationid);
										mysqli_stmt_execute($newrelationSTMT);
										header("Location: home.php?user=".$otherusername."");
									}
								}
								// unfollow, 		1-0
								else if (($newstatus == 'unfollow') AND ($currentstatus == 1)) {
									$newrelationSQL = "UPDATE friends SET status = 0, statusdate = ? WHERE relationid = ?";
									$newrelationSTMT = mysqli_stmt_init($conn);
									if (!mysqli_stmt_prepare($newrelationSTMT, $newrelationSQL)) {
										header("Location: home.php?user=".$otherusername."");  // eventually change this to get the same page where the user was previously
									}
									else {
										mysqli_stmt_bind_param($newrelationSTMT, "si", $date, $relationid);
										mysqli_stmt_execute($newrelationSTMT);
										header("Location: home.php?user=".$otherusername."");
									}
								}
								// friend, 			2-3
								else if (($newstatus == 'follow') AND ($currentstatus == 2)) {
									$newrelationSQL = "UPDATE friends SET status = 3, statusdate = ? WHERE relationid = ?";
									$newrelationSTMT = mysqli_stmt_init($conn);
									if (!mysqli_stmt_prepare($newrelationSTMT, $newrelationSQL)) {
										header("Location: home.php?user=".$otherusername."");  // eventually change this to get the same page where the user was previously
									}
									else {
										mysqli_stmt_bind_param($newrelationSTMT, "si", $date, $relationid);
										mysqli_stmt_execute($newrelationSTMT);
										header("Location: home.php?user=".$otherusername."");
									}
								}
								// unfriend, 		3-2
								else if (($newstatus == 'unfollow') AND ($currentstatus == 3)) {
									$newrelationSQL = "UPDATE friends SET status = 2, statusdate = ? WHERE relationid = ?";
									$newrelationSTMT = mysqli_stmt_init($conn);
									if (!mysqli_stmt_prepare($newrelationSTMT, $newrelationSQL)) {
										header("Location: home.php?user=".$otherusername."");  // eventually change this to get the same page where the user was previously
									}
									else {
										mysqli_stmt_bind_param($newrelationSTMT, "si", $date, $relationid);
										mysqli_stmt_execute($newrelationSTMT);
										header("Location: home.php?user=".$otherusername."");
									}
								}
								// block,			0-4		1-4		2-4		3-4
								else if (($newstatus == 'block') AND (($currentstatus == 0) OR ($currentstatus == 1) OR ($currentstatus == 2) OR ($currentstatus == 3))) {
									$newrelationSQL = "UPDATE friends SET status = 4, statusdate = ? WHERE relationid = ?";
									$newrelationSTMT = mysqli_stmt_init($conn);
									if (!mysqli_stmt_prepare($newrelationSTMT, $newrelationSQL)) {
										header("Location: home.php?user=".$otherusername."");  // eventually change this to get the same page where the user was previously
									}
									else {
										mysqli_stmt_bind_param($newrelationSTMT, "si", $date, $relationid);
										mysqli_stmt_execute($newrelationSTMT);
										header("Location: home.php?user=".$otherusername."");
									}
								}
								// unblock, 		4-0
								else if (($newstatus == 'unblock') AND ($currentstatus == 4)) {
									$newrelationSQL = "UPDATE friends SET status = 0, statusdate = ? WHERE relationid = ?";
									$newrelationSTMT = mysqli_stmt_init($conn);
									if (!mysqli_stmt_prepare($newrelationSTMT, $newrelationSQL)) {
										header("Location: home.php?user=".$otherusername."");  // eventually change this to get the same page where the user was previously
									}
									else {
										mysqli_stmt_bind_param($newrelationSTMT, "si", $date, $relationid);
										mysqli_stmt_execute($newrelationSTMT);
										header("Location: home.php?user=".$otherusername."");
									}
								}
								// block mutual		5-6	
								else if (($newstatus == 'block') AND ($currentstatus == 5)) {
									$newrelationSQL = "UPDATE friends SET status = 6, statusdate = ? WHERE relationid = ?";
									$newrelationSTMT = mysqli_stmt_init($conn);
									if (!mysqli_stmt_prepare($newrelationSTMT, $newrelationSQL)) {
										header("Location: home.php?user=".$otherusername."");  // eventually change this to get the same page where the user was previously
									}
									else {
										mysqli_stmt_bind_param($newrelationSTMT, "si", $date, $relationid);
										mysqli_stmt_execute($newrelationSTMT);
										header("Location: home.php?user=".$otherusername."");
									}
								}
								// unblock mutual	6-5
								else if (($newstatus == 'unblock') AND ($currentstatus == 6)) {
									$newrelationSQL = "UPDATE friends SET status = 5, statusdate = ? WHERE relationid = ?";
									$newrelationSTMT = mysqli_stmt_init($conn);
									if (!mysqli_stmt_prepare($newrelationSTMT, $newrelationSQL)) {
										header("Location: home.php?user=".$otherusername."");  // eventually change this to get the same page where the user was previously
									}
									else {
										mysqli_stmt_bind_param($newrelationSTMT, "si", $date, $relationid);
										mysqli_stmt_execute($newrelationSTMT);
										header("Location: home.php?user=".$otherusername."");
									}
								}
								else {

								}
							}
							else if ($userid == $user2) {
								// follow,			0-2
								if (($newstatus == 'follow') AND ($currentstatus == 0)) {
									$newrelationSQL = "UPDATE friends SET status = 2, statusdate = ? WHERE relationid = ?";
									$newrelationSTMT = mysqli_stmt_init($conn);
									if (!mysqli_stmt_prepare($newrelationSTMT, $newrelationSQL)) {
										header("Location: home.php?user=".$otherusername."");  // eventually change this to get the same page where the user was previously
									}
									else {
										mysqli_stmt_bind_param($newrelationSTMT, "si", $date, $relationid);
										mysqli_stmt_execute($newrelationSTMT);
										header("Location: home.php?user=".$otherusername."");
									}
								}
								// unfollow,		2-0
								else if (($newstatus == 'unfollow') AND ($currentstatus == 2)) {
									$newrelationSQL = "UPDATE friends SET status = 0, statusdate = ? WHERE relationid = ?";
									$newrelationSTMT = mysqli_stmt_init($conn);
									if (!mysqli_stmt_prepare($newrelationSTMT, $newrelationSQL)) {
										header("Location: home.php?user=".$otherusername."");  // eventually change this to get the same page where the user was previously
									}
									else {
										mysqli_stmt_bind_param($newrelationSTMT, "si", $date, $relationid);
										mysqli_stmt_execute($newrelationSTMT);
										header("Location: home.php?user=".$otherusername."");
									}
								}
								// friend,			1-3
								else if (($newstatus == 'follow') AND ($currentstatus == 1)) {
									$newrelationSQL = "UPDATE friends SET status = 3, statusdate = ? WHERE relationid = ?";
									$newrelationSTMT = mysqli_stmt_init($conn);
									if (!mysqli_stmt_prepare($newrelationSTMT, $newrelationSQL)) {
										header("Location: home.php?user=".$otherusername."");  // eventually change this to get the same page where the user was previously
									}
									else {
										mysqli_stmt_bind_param($newrelationSTMT, "si", $date, $relationid);
										mysqli_stmt_execute($newrelationSTMT);
										header("Location: home.php?user=".$otherusername."");
									}
								}
								// unfriend,		3-1
								else if (($newstatus == 'unfollow') AND ($currentstatus == 3)) {
									$newrelationSQL = "UPDATE friends SET status = 1, statusdate = ? WHERE relationid = ?";
									$newrelationSTMT = mysqli_stmt_init($conn);
									if (!mysqli_stmt_prepare($newrelationSTMT, $newrelationSQL)) {
										header("Location: home.php?user=".$otherusername."");  // eventually change this to get the same page where the user was previously
									}
									else {
										mysqli_stmt_bind_param($newrelationSTMT, "si", $date, $relationid);
										mysqli_stmt_execute($newrelationSTMT);
										header("Location: home.php?user=".$otherusername."");
									}
								}
								// block,			0-5		1-5		2-5		3-5
								else if (($newstatus == 'block') AND (($currentstatus == 0) OR ($currentstatus == 1) OR ($currentstatus == 2) OR ($currentstatus == 3))) {
									$newrelationSQL = "UPDATE friends SET status = 5, statusdate = ? WHERE relationid = ?";
									$newrelationSTMT = mysqli_stmt_init($conn);
									if (!mysqli_stmt_prepare($newrelationSTMT, $newrelationSQL)) {
										header("Location: home.php?user=".$otherusername."");  // eventually change this to get the same page where the user was previously
									}
									else {
										mysqli_stmt_bind_param($newrelationSTMT, "si", $date, $relationid);
										mysqli_stmt_execute($newrelationSTMT);
										header("Location: home.php?user=".$otherusername."");
									}
								}
								// unlblock,		5-0
								else if (($newstatus == 'unblock') AND ($currentstatus == 5)) {
									$newrelationSQL = "UPDATE friends SET status = 0, statusdate = ? WHERE relationid = ?";
									$newrelationSTMT = mysqli_stmt_init($conn);
									if (!mysqli_stmt_prepare($newrelationSTMT, $newrelationSQL)) {
										header("Location: home.php?user=".$otherusername."");  // eventually change this to get the same page where the user was previously
									}
									else {
										mysqli_stmt_bind_param($newrelationSTMT, "si", $date, $relationid);
										mysqli_stmt_execute($newrelationSTMT);
										header("Location: home.php?user=".$otherusername."");
									}
								}
								// block mutual,	4-6	
								else if (($newstatus == 'block') AND ($currentstatus == 4)) {
									$newrelationSQL = "UPDATE friends SET status = 6, statusdate = ? WHERE relationid = ?";
									$newrelationSTMT = mysqli_stmt_init($conn);
									if (!mysqli_stmt_prepare($newrelationSTMT, $newrelationSQL)) {
										header("Location: home.php?user=".$otherusername."");  // eventually change this to get the same page where the user was previously
									}
									else {
										mysqli_stmt_bind_param($newrelationSTMT, "si", $date, $relationid);
										mysqli_stmt_execute($newrelationSTMT);
										header("Location: home.php?user=".$otherusername."");
									}
								}		
								// unblock mutual,	6-4
								else if (($newstatus == 'unblock') AND ($currentstatus == 6)) {
									$newrelationSQL = "UPDATE friends SET status = 4, statusdate = ? WHERE relationid = ?";
									$newrelationSTMT = mysqli_stmt_init($conn);
									if (!mysqli_stmt_prepare($newrelationSTMT, $newrelationSQL)) {
										header("Location: home.php?user=".$otherusername."");  // eventually change this to get the same page where the user was previously
									}
									else {
										mysqli_stmt_bind_param($newrelationSTMT, "si", $date, $relationid);
										mysqli_stmt_execute($newrelationSTMT);
										header("Location: home.php?user=".$otherusername."");
									}
								}
								else {

								}
							}
							else {

							}
						}
					}
				}
			}
		}
	}
	else {
		// send back to original page
	}