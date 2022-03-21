<?php
// status1
	function status1($conn) {
		$stat1sql = "SELECT * FROM friends WHERE (user1 = ?) AND status = 1";
		$stat1stmt = mysqli_stmt_init($conn);
		if (!mysqli_stmt_prepare($stat1stmt, $stat1sql)) {
			echo "error1";
		}
		else {
			mysqli_stmt_bind_param($stat1stmt, "i", $userID);
			mysqli_stmt_execute($stat1stmt);
			$stat1result = mysqli_stmt_get_result($stat1stmt);
			while ($stat1row = mysqli_fetch_assoc($stat1result)) {
			 	$userID == $stat1row['user1'];
		 		$otheruserID = $stat1row['user2'];
		 		$otheruserNamesql = "SELECT userName FROM users WHERE userid = ?";
			 	$otheruserNamestmt = mysqli_stmt_init($conn);
			 	if (!mysqli_stmt_prepare($otheruserNamestmt, $otheruserNamesql)) {
			 		echo "error";
			 	}
			 	else {
			 		mysqli_stmt_bind_param($otheruserNamestmt, "s", $otheruserID);
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
	}

?>