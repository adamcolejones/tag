<?php
	require "header.php";
?>
<div class="homeBody">
	<?php 
		if (isset($_GET["user"])) {
			$userName = $_GET["user"];
			$sql = "SELECT * FROM users WHERE userName=?"; // $userName
			$stmt = mysqli_stmt_init($conn);
			if (!mysqli_stmt_prepare($stmt, $sql)) {
				echo "error";
			}
			else {
				mysqli_stmt_bind_param($stmt, "s", $userName);
				mysqli_stmt_execute($stmt);
				$result = mysqli_stmt_get_result($stmt);
				while ($row = mysqli_fetch_assoc($result)) {
					$id = $row['userid'];
					$userName = $row['userName'];
					$firstName = $row['firstName'];
					$lastName = $row['lastName'];
					$joined = $row['joined'];
					echo "<div class='ProfileBanner'>";		
						if (isset($_SESSION['userID'])) {
							if ($_SESSION['userID'] == $id) {
								echo "";
							}
							else {
								// select all from friends where user1 or user2 is session user
								// select all from friends where user1 or user2 is homepage owner
								$friendSQL = "SELECT * FROM friends WHERE ((user1 = ?) OR (user2 = ?)) AND ((user1 = ?) OR (user2 = ?))";
								$friendSTMT = mysqli_stmt_init($conn);
								if (!mysqli_stmt_prepare($friendSTMT, $friendSQL)) {
									echo "error";
								}
								else {
									mysqli_stmt_bind_param($friendSTMT, "iiii", $_SESSION['userID'], $_SESSION['userID'], $id, $id);
									mysqli_stmt_execute($friendSTMT);
									$friendRESULT = mysqli_stmt_get_result($friendSTMT);
									$follow = "follow";
									$unfollow = "unfollow";
									$block = "block";
									$unblock = "unblock";
									if (mysqli_num_rows($friendRESULT) == 0) {
										echo "<div class='friendStatus'>";
											echo "<a href='followstatus.php?user=".$userName."&set=".$follow."'>
												<span>Follow</span>
												<span>Follow</span>
											</a>";
										echo "</div>"; 
										echo '<div class="HomeDropDownMenu">
													<button class="HomeDropDownButton">•••</button>
													<div class="HomeDropDownContent">
														<a href="#">Message</a>
														<a href="#">Report</a>
														<a href="followstatus.php?user='.$userName.'&set='.$block.'">Block</a>
													</div>
												</div>';
									}
									else {
										while ($friendROW = mysqli_fetch_assoc($friendRESULT)) {
											$user1 = $friendROW['user1'];
											$user2 = $friendROW['user2'];
											$status = $friendROW['status'];
											$statusdate = $friendROW['statusdate'];
											echo "<div class='friendStatus'>";
											if ($status == 0) {
												// NEITHER USERS ARE FOLLOWING EACH OTHER
												// BUTTON SAYS "+FOLLOW"
												echo "<a href='followstatus.php?user=".$userName."&set=".$follow."'>
													<span>Follow</span>
													<span>Follow</span>
												</a>";
												echo "</div>"; 
												echo '<div class="HomeDropDownMenu">
													<button class="HomeDropDownButton">•••</button>
													<div class="HomeDropDownContent">
														<a href="#">Message</a>
														<a href="#">Report</a>
														<a href="followstatus.php?user='.$userName.'&set='.$block.'">Block</a>
													</div>
												</div>';
												}
											else if ($status == 1) {
												if ($_SESSION['userID'] < $id) {
													// USER IS FOLLOWING OTHER
													// BUTTON SAYS "FOLLOWING"
													echo "<a href='followstatus.php?user=".$userName."&set=".$unfollow."'>
														<span>Following</span>
														<span>Unfollow</span>
													</a>";
													echo "</div>"; 
													echo '<div class="HomeDropDownMenu">
														<button class="HomeDropDownButton">•••</button>
														<div class="HomeDropDownContent">
															<a href="#">Message</a>
															<a href="#">Report</a>
															<a href="followstatus.php?user='.$userName.'&set='.$block.'">Block</a>
														</div>
													</div>';
												}
												else {
													// OTHER IS FOLLOWING USER
													// BUTTON SAYS "+FRIEND"
													echo "<a href='followstatus.php?user=".$userName."&set=".$follow."'>
														<span>Follow</span>
														<span>Follow</span>
													</a>";
													echo "</div>"; 
													echo '<div class="HomeDropDownMenu">
														<button class="HomeDropDownButton">•••</button>
														<div class="HomeDropDownContent">
															<a href="#">Message</a>
															<a href="#">Report</a>
															<a href="followstatus.php?user='.$userName.'&set='.$block.'">Block</a>
														</div>
													</div>';
												}
											}
											else if ($status == 2) {
												if ($_SESSION['userID'] < $id) {
													// OTHER IS FOLLOWING USER
													// BUTTON SAYS "+FRIEND"
													echo "<a href='followstatus.php?user=".$userName."&set=".$follow."'>
														<span>Follow</span>
														<span>Follow</span>
													</a>";
													echo "</div>"; 
													echo '<div class="HomeDropDownMenu">
														<button class="HomeDropDownButton">•••</button>
														<div class="HomeDropDownContent">
															<a href="#">Message</a>
															<a href="#">Report</a>
															<a href="followstatus.php?user='.$userName.'&set='.$block.'">Block</a>
														</div>
													</div>';
												}
												else {
													// USER IS FOLLOWING OTHER
													// BUTTON SAYS "FOLLOWING"
													echo "<a href='followstatus.php?user=".$userName."&set=".$unfollow."'>
														<span>Following</span>
														<span>Unfollow</span>
													</a>";
													echo "</div>"; 
													echo '<div class="HomeDropDownMenu">
														<button class="HomeDropDownButton">•••</button>
														<div class="HomeDropDownContent">
															<a href="#">Message</a>
															<a href="#">Report</a>
															<a href="followstatus.php?user='.$userName.'&set='.$block.'">Block</a>
														</div>
													</div>';
												}
											}
											else if ($status == 3) {
												// BOTH USERS ARE FOLLOWING EACH OTHER
												// BUTTON SAYS "FRIENDS"
												echo "<a href='followstatus.php?user=".$userName."&set=".$unfollow."'>
													<span>Friends</span>
													<span>Unfollow</span>
												</a>";
												echo "</div>"; 
												echo '<div class="HomeDropDownMenu">
													<button class="HomeDropDownButton">•••</button>
													<div class="HomeDropDownContent">
														<a href="#">Message</a>
														<a href="#">Report</a>
														<a href="followstatus.php?user='.$userName.'&set='.$block.'">Block</a>
													</div>
												</div>';
											}
											else if ($status == 4) {
												if ($_SESSION['userID'] < $id) {
													// USER IS BLOCKING OTHER
													// USER SHOULD NOT BE ABLE TO VIEW CONTENT
													// DROP DOWN MENU SHOULD GIVE THE OPTION TO UNBLOCK OTHER
													echo "</div>";
													echo "<p class='BlockText'>You are blocking this user.</p>";
													echo '<div class="HomeDropDownMenu">
														<button class="HomeDropDownButton">•••</button>
														<div class="HomeDropDownContent">
															<a href="#">Report</a>
															<a href="followstatus.php?user='.$userName.'&set='.$unblock.'">Unblock</a>
														</div>
													</div>';
												}
												else {
													// OTHER IS BLOCKING USER
													// USER SHOULD NOT BE ABLE TO VIEW CONTENT
													// DROP DOWN MENU SHOULD GIVE THE OPTION TO BLOCK
													echo "</div>"; 
													echo "<p class='BlockText'>You are blocked by this user.</p>";
													echo '<div class="HomeDropDownMenu">
														<button class="HomeDropDownButton">•••</button>
														<div class="HomeDropDownContent">
															<a href="#">Report</a>
															<a href="followstatus.php?user='.$userName.'&set='.$block.'">Block</a>
														</div>
													</div>';
												}
											}
											else if ($status == 5) {
												if ($_SESSION['userID'] < $id) {
													// OTHER IS BLOCKING USER
													// USER SHOULD NOT BE ABLE TO VIEW CONTENT
													// DROP DOWN MENU SHOULD GIVE THE OPTION TO BLOCK
													echo "</div>";
													echo "<p class='BlockText'>You are blocked by this user.</p>";
													echo '<div class="HomeDropDownMenu">
														<button class="HomeDropDownButton">•••</button>
														<div class="HomeDropDownContent">
															<a href="#">Report</a>
															<a href="followstatus.php?user='.$userName.'&set='.$block.'">Block</a>
														</div>
													</div>';
												}
												else {
													// USER IS BLOCKING OTHER
													// USER SHOULD NOT BE ABLE TO VIEW CONTENT
													// DROP DOWN MENU SHOULD GIVE THE OPTION TO UNBLOCK OTHER
													echo "</div>"; 
													echo "<p class='BlockText'>You are blocking this user.</p>";
													echo '<div class="HomeDropDownMenu">
														<button class="HomeDropDownButton">•••</button>
														<div class="HomeDropDownContent">
															<a href="#">Report</a>
															<a href="followstatus.php?user='.$userName.'&set='.$unblock.'">Unblock</a>
														</div>
													</div>';
												}
											}
											else if ($status == 6) {
												// BOTH USERS ARE BLOCKING EACH OTHER
												// USER SHOULD NOT BE ABLE TO VIEW CONTENT
												// GIVE THE OPTION TO UNBLOCK IN A DROP DOWN MENU
												echo "</div>";
												echo "<p class='BlockText'>You are blocking this user.</p>";
												echo '<div class="HomeDropDownMenu">
													<button class="HomeDropDownButton">•••</button>
													<div class="HomeDropDownContent">
														<a href="#">Report</a>
														<a href="followstatus.php?user='.$userName.'&set='.$unblock.'">Unblock</a>
													</div>
												</div>';
											}
											else if ($status == 7) {
												echo "</div>"; 
											}
										}
									}
								}
								// change friendship accordingly
								// get url to send them back to the page when the friendship has been accepted
							}
						}
						else {

						}
						if ($row['profileimg'] == 1) {
							$filename = "profilepics/profile".$id."*";
							$fileinfo = glob($filename);
							$fileext = explode(".", $fileinfo[0]);
							$fileactualext = $fileext[1];
							echo "<div class='ProfileBannerPicture'><img src='profilepics/profile".$id.".".$fileactualext."?".mt_rand()."'></div>";
						}
						else {
							echo "<div class='ProfileBannerPicture'><img src='profilepics/noUser.png'></div>";
						}
						echo "<div class='ProfileBannerUserName'>".$userName."</div>";
						// echo "<div class='ProfileBannerRealName'>".$firstName." ".$lastName."</div>";
						// echo "<div class='ProfileBannerJoined'>Joined: ".$joined."</div>";
					echo "</div>";

					/*These should be buttons instead of links, use isset post to find what tab the user clicked*/
					echo "<div class='sidebar'>";
					echo "<a href='#'>Posts</a>";
					echo "<a href='#'>Folders</a>"; /*all folders made by the user and some defaults like "likes" "dislikes" "most popular" etc*/
					echo "<a href='#'>Games</a>"; /*All, completed, beaten, backlog, wishlist*/
					echo "<a href='friends.php?user=".$userName."'>Friends</a>"; //right now this is a test file that displays all users and trys to see which are friends and which are not
					if (isset($_SESSION['userID'])) {
						$userUserName = $_SESSION['userUserName'];
						if ($userUserName == $userName) {
							echo "<a href='mail.php'>My Mail</a>";
						}
						else {
							echo "";
						}
					}
					else { 
						echo "";
					}
					echo "</div>";
					getHomeUploads($conn); 
				}
			}	
		} 
		?>
</div>
<?php
	require "footer.php";
?> 		