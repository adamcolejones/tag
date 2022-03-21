<?php 
	require "header.php";
?>

<div class="friendListContainer">
	<?php 

		// $userID = $_SESSION['userID'];*/
		$userName = $_GET['user'];
		$userIDsql = "SELECT userid FROM users WHERE userName = ?";
		$useridstmt = mysqli_stmt_init($conn);
		if (!mysqli_stmt_prepare($useridstmt, $userIDsql)) {
			echo "error";
		}
		else {
			mysqli_stmt_bind_param($useridstmt, "s", $userName);
			mysqli_stmt_execute($useridstmt);
			$userIDresult = mysqli_stmt_get_result($useridstmt);
			while ($userrow = mysqli_fetch_assoc($userIDresult)) {
				$userID = $userrow['userid'];
			// Following (user follows other) (other is followed by user)
				echo "<div class='friendStatus1'>";
					echo "Following";
					//status1($conn);
					$stat1sql = "SELECT * FROM friends WHERE (user1 = ? AND status = 1) OR (user2 = ? AND status= 2)";
					$stat1stmt = mysqli_stmt_init($conn);
					if (!mysqli_stmt_prepare($stat1stmt, $stat1sql)) {
						echo "error1";
					}
					else {
						mysqli_stmt_bind_param($stat1stmt, "ii", $userID, $userID);
						mysqli_stmt_execute($stat1stmt);
						$stat1result = mysqli_stmt_get_result($stat1stmt);
						while ($stat1row = mysqli_fetch_assoc($stat1result)) {
						 	if ($userID == $stat1row['user1']) {
						 		$otheruserID = $stat1row['user2'];
						 	}
						 	else {
						 		$otheruserID = $stat1row['user1'];
						 	}
					 		$otheruserNamesql = "SELECT userName FROM users WHERE userid = ?";
						 	$otheruserNamestmt = mysqli_stmt_init($conn);
						 	if (!mysqli_stmt_prepare($otheruserNamestmt, $otheruserNamesql)) {
						 		echo "error";
						 	}
						 	else {
						 		mysqli_stmt_bind_param($otheruserNamestmt, "i", $otheruserID);
						 		mysqli_stmt_execute($otheruserNamestmt);
						 		$otheruserNameresult = mysqli_stmt_get_result($otheruserNamestmt);
						 		while ($otheruserNamerow = mysqli_fetch_assoc($otheruserNameresult)) {
						 			echo "<div class='friendBox'>";
						 				echo "<a href='home.php?user=".$otheruserNamerow['userName']."'>".$otheruserNamerow['userName']."</a>";
						 			echo "</div>";
						 		}
						 	}
						}
					}

				echo "</div>"; 
			// Followers (user is followed by other) (other follows user)
				echo "<div class='friendStatus2'>";
				echo "Followers";
				$stat2sql = "SELECT * FROM friends WHERE (user1 = ? AND status = 2) OR (user2 = ? AND status = 1)";
				$stat2stmt = mysqli_stmt_init($conn);
				if (!mysqli_stmt_prepare($stat2stmt, $stat2sql)) {
					echo "error";
				}
				else {
					mysqli_stmt_bind_param($stat2stmt, "ii", $userID, $userID);
					mysqli_stmt_execute($stat2stmt);
					$stat2result = mysqli_stmt_get_result($stat2stmt);
					while ($stat2row = mysqli_fetch_assoc($stat2result)) {
					 	if ($userID == $stat2row['user1']) {
					 		$otheruserID = $stat2row['user2'];
					 	}
					 	else {
					 		$otheruserID = $stat2row['user1'];
					 	}
					 	$otheruserNamesql = "SELECT userName FROM users WHERE userid = ?";
					 	$otheruserNamestmt = mysqli_stmt_init($conn);
					 	if (!mysqli_stmt_prepare($otheruserNamestmt, $otheruserNamesql)) {
					 		echo "error";
					 	}
					 	else {
					 		mysqli_stmt_bind_param($otheruserNamestmt, "i", $otheruserID);
					 		mysqli_stmt_execute($otheruserNamestmt);
					 		$otheruserNameresult = mysqli_stmt_get_result($otheruserNamestmt);
					 		while ($otheruserNamerow = mysqli_fetch_assoc($otheruserNameresult)) {
					 			echo "<div class='friendBox'>";
					 				echo "<a href='home.php?user=".$otheruserNamerow['userName']."'>".$otheruserNamerow['userName']."</a>";
					 			echo "</div>";
					 		}
					 	}
					} 
				}
				echo "</div>";
			// Friends (both users follow each other)
				echo "<div class='friendStatus3'>";
					echo "Friends";
					$stat3sql = "SELECT * FROM friends WHERE (user1 = ? OR user2 = ?) AND status = 3";
					$stat3stmt = mysqli_stmt_init($conn);
					if (!mysqli_stmt_prepare($stat3stmt, $stat3sql)) {
						echo "error";
					}
					else {
						mysqli_stmt_bind_param($stat3stmt, "ii", $userID, $userID);
						mysqli_stmt_execute($stat3stmt);
						$stat3result = mysqli_stmt_get_result($stat3stmt);
						while ($stat3row = mysqli_fetch_assoc($stat3result)) {
						 	if ($userID == $stat3row['user1']) {
						 		$otheruserID = $stat3row['user2'];
						 	}
						 	else {
						 		$otheruserID = $stat3row['user1'];
						 	}
						 	$otheruserNamesql = "SELECT userName FROM users WHERE userid = ?";
						 	$otheruserNamestmt = mysqli_stmt_init($conn);
						 	if (!mysqli_stmt_prepare($otheruserNamestmt, $otheruserNamesql)) {
						 		echo "error";
						 	}
						 	else {
						 		mysqli_stmt_bind_param($otheruserNamestmt, "i", $otheruserID);
						 		mysqli_stmt_execute($otheruserNamestmt);
						 		$otheruserNameresult = mysqli_stmt_get_result($otheruserNamestmt);
						 		while ($otheruserNamerow = mysqli_fetch_assoc($otheruserNameresult)) {
						 			echo "<div class='friendBox'>";
						 				echo "<a href='home.php?user=".$otheruserNamerow['userName']."'>".$otheruserNamerow['userName']."</a>";
						 			echo "</div>";
						 		}
						 	}
						}
					}
				echo "</div>"; 
			// Blocked (user is blocking other) (other is blocked by user)
				echo "<div class='friendStatus4'>";
					echo "Blocked";
					$stat4sql = "SELECT * FROM friends WHERE (user1 = ? AND status = 4) OR (user2 = ? AND status = 5)";
					$stat4stmt = mysqli_stmt_init($conn);
					if (!mysqli_stmt_prepare($stat4stmt, $stat4sql)) {
						echo "error";
					}
					else {
						mysqli_stmt_bind_param($stat4stmt, "ii", $userID, $userID);
						mysqli_stmt_execute($stat4stmt);
						$stat4result = mysqli_stmt_get_result($stat4stmt);
						while ($stat4row = mysqli_fetch_assoc($stat4result)) {
						 	if ($userID == $stat4row['user1']) {
						 		$otheruserID = $stat4row['user2'];
						 	}
						 	else {
						 		$otheruserID = $stat4row['user1'];
						 	}
						 	$otheruserNamesql = "SELECT userName FROM users WHERE userid = ?";
						 	$otheruserNamestmt = mysqli_stmt_init($conn);
						 	if (!mysqli_stmt_prepare($otheruserNamestmt, $otheruserNamesql)) {
						 		echo "error";
						 	}
						 	else {
						 		mysqli_stmt_bind_param($otheruserNamestmt, "i", $otheruserID);
						 		mysqli_stmt_execute($otheruserNamestmt);
						 		$otheruserNameresult = mysqli_stmt_get_result($otheruserNamestmt);
						 		while ($otheruserNamerow = mysqli_fetch_assoc($otheruserNameresult)) {
						 			echo "<div class='friendBox'>";
						 				echo "<a href='home.php?user=".$otheruserNamerow['userName']."'>".$otheruserNamerow['userName']."</a>";
						 			echo "</div>";
						 		}
						 	}
						}
					}
				echo "</div>"; 
			// Blocked By (user is blocked by other) (other is blocking user)
				echo "<div class='friendStatus5'>";
					echo "Blocked By";
					$stat5sql = "SELECT * FROM friends WHERE (user1 = ? AND status = 5) OR (user2 = ? AND status = 4)";
					$stat5stmt = mysqli_stmt_init($conn);
					if (!mysqli_stmt_prepare($stat5stmt, $stat5sql)) {
						echo "error";
					}
					else {
						mysqli_stmt_bind_param($stat5stmt, "ii", $userID, $userID);
						mysqli_stmt_execute($stat5stmt);
						$stat5result = mysqli_stmt_get_result($stat5stmt);
						while ($stat5row = mysqli_fetch_assoc($stat5result)) {
						 	if ($userID == $stat5row['user1']) {
						 		$otheruserID = $stat5row['user2'];
						 	}
						 	else {
						 		$otheruserID = $stat5row['user1'];
						 	}
						 	$otheruserNamesql = "SELECT userName FROM users WHERE userid = ?";
						 	$otheruserNamestmt = mysqli_stmt_init($conn);
						 	if (!mysqli_stmt_prepare($otheruserNamestmt, $otheruserNamesql)) {
						 		echo "error";
						 	}
						 	else {
						 		mysqli_stmt_bind_param($otheruserNamestmt, "i", $otheruserID);
						 		mysqli_stmt_execute($otheruserNamestmt);
						 		$otheruserNameresult = mysqli_stmt_get_result($otheruserNamestmt);
						 		while ($otheruserNamerow = mysqli_fetch_assoc($otheruserNameresult)) {
						 			echo "<div class='friendBox'>";
						 				echo "<a href='home.php?user=".$otheruserNamerow['userName']."'>".$otheruserNamerow['userName']."</a>";
						 			echo "</div>";
						 		}
						 	}
						}
					}
				echo "</div>"; 
			// Both Blocked (both users blocked each other)
					echo "<div class='friendStatus6'>";
						echo "Both Blocked";
						$stat6sql = "SELECT * FROM friends WHERE (user1 = ? OR user2 = ?) AND status = 6";
						$stat6stmt = mysqli_stmt_init($conn);
						if (!mysqli_stmt_prepare($stat6stmt, $stat6sql)) {
							echo "error";
						}
						else {
							mysqli_stmt_bind_param($stat6stmt, "ii", $userID, $userID);
							mysqli_stmt_execute($stat6stmt);
							$stat6result = mysqli_stmt_get_result($stat6stmt);
							while ($stat6row = mysqli_fetch_assoc($stat6result)) {
							 	if ($userID == $stat6row['user1']) {
							 		$otheruserID = $stat6row['user2'];
							 	}
							 	else {
							 		$otheruserID = $stat6row['user1'];
							 	}
							 	$otheruserNamesql = "SELECT userName FROM users WHERE userid = ?";
							 	$otheruserNamestmt = mysqli_stmt_init($conn);
							 	if (!mysqli_stmt_prepare($otheruserNamestmt, $otheruserNamesql)) {
							 		echo "error";
							 	}
							 	else {
							 		mysqli_stmt_bind_param($otheruserNamestmt, "i", $otheruserID);
							 		mysqli_stmt_execute($otheruserNamestmt);
							 		$otheruserNameresult = mysqli_stmt_get_result($otheruserNamestmt);
							 		while ($otheruserNamerow = mysqli_fetch_assoc($otheruserNameresult)) {
							 			echo "<div class='friendBox'>";
							 				echo "<a href='home.php?user=".$otheruserNamerow['userName']."'>".$otheruserNamerow['userName']."</a>";
							 			echo "</div>";
							 		}
							 	}
							}
						}
					echo "</div>";
			// Unassociated (user and other have removed their relationships)
					echo "<div class='friendStatus0'>";
						echo "Unassociated";
						$stat0sql = "SELECT * FROM friends WHERE (user1 = ? OR user2 = ?) AND status = 0";
						$stat0stmt = mysqli_stmt_init($conn);
						if (!mysqli_stmt_prepare($stat0stmt, $stat0sql)) {
							echo "error";
						}
						else {
							mysqli_stmt_bind_param($stat0stmt, "ii", $userID, $userID);
							mysqli_stmt_execute($stat0stmt);
							$stat0result = mysqli_stmt_get_result($stat0stmt);
							while ($stat0row = mysqli_fetch_assoc($stat0result)) {
							 	if ($userID == $stat0row['user1']) {
							 		$otheruserID = $stat0row['user2'];
							 	}
							 	else {
							 		$otheruserID = $stat0row['user1'];
							 	}
							 	$otheruserNamesql = "SELECT userName FROM users WHERE userid = ?";
							 	$otheruserNamestmt = mysqli_stmt_init($conn);
							 	if (!mysqli_stmt_prepare($otheruserNamestmt, $otheruserNamesql)) {
							 		echo "error";
							 	}
							 	else {
							 		mysqli_stmt_bind_param($otheruserNamestmt, "i", $otheruserID);
							 		mysqli_stmt_execute($otheruserNamestmt);
							 		$otheruserNameresult = mysqli_stmt_get_result($otheruserNamestmt);
							 		while ($otheruserNamerow = mysqli_fetch_assoc($otheruserNameresult)) {
							 			echo "<div class='friendBox'>";
							 				echo "<a href='home.php?user=".$otheruserNamerow['userName']."'>".$otheruserNamerow['userName']."</a>";
							 			echo "</div>";
							 		}
							 	}
							}
						}
			// users have no recorded relationship
					/* $userID = $userrow['userid'];
					$unrecordedSQL = "SELECT * FROM users WHERE userid != ?";
					$unrecordedSTMT = mysqli_stmt_init($conn);
					if (!mysqli_stmt_prepare($unrecordedSTMT, $unrecordedSQL)) {
						echo "error";
					}
					else {
						mysqli_stmt_bind_param($unrecordedSTMT, "i", $userID);
						mysqli_stmt_execute($unrecordedSTMT);
						$unrecordedResult = mysqli_stmt_get_result($unrecordedSTMT);
						while ($unrecordedROW = mysqli_fetch_assoc($unrecordedResult)) {
							$otheruserID = $unrecordedROW['userid'];
							$otheruserName = $unrecordedROW['userName'];
							$unrecordedCheckSQL = "SELECT * FROM friends WHERE ((user1 = ?) AND (user2 = ?)) OR ((user1 = ?) AND (user2 = ?))";
							$unrecordedCheckSTMT = mysqli_stmt_init($conn);
							if (mysqli_stmt_prepare($unrecordedCheckSTMT, $unrecordedCheckSQL)) {
								mysqli_stmt_bind_param($unrecordedCheckSTMT, "iiii", $userID, $otheruserID, $otheruserID, $userID);
								mysqli_stmt_execute($unrecordedCheckSTMT);
							}
							else {
								echo "<div class='friendBox'>";
							 		echo "<a href='home.php?user=".$otheruserName."'>".$otheruserName."</a>";
							 	echo "</div>";	
							}
						}
					} */
				echo "</div>";
			}
		}	
	;?>
</div>

<?php require "footer.php";?> 		