<?php 

// postText (clean)
	function postText($conn) {
		if (isset($_POST['textSubmit'])) {
			$username = mysqli_real_escape_string($conn, $_SESSION['userUserName']);
			$userid = mysqli_real_escape_string($conn, $_POST['userid']);
			$hostid = mysqli_real_escape_string($conn, $_POST['userid']);
			$date = mysqli_real_escape_string($conn, $_POST['date']);
			$title = mysqli_real_escape_string($conn, $_POST['title']);
			$body = mysqli_real_escape_string($conn, $_POST['body']);
			$commentid = 0;
			$replyid = 0;
			$fileid = 1;

			$sql = "SELECT MAX(postid) AS maxid FROM posts WHERE hostid = ?;";
			$stmt = mysqli_stmt_init($conn);
			if (!mysqli_stmt_prepare($stmt, $sql)) {
				echo "Error";
			}
			else {
				mysqli_stmt_bind_param($stmt, "s", $hostid);
				mysqli_stmt_execute($stmt);
				$result = mysqli_stmt_get_result($stmt);
				//  $result = mysqli_query($conn, $sql);
				$resultCheck = mysqli_num_rows($result);
				if ($resultCheck > 0) {
					while ($row = mysqli_fetch_assoc($result)) {
						$postid = $row['maxid'] + 1;
					}
				}
				else {
					$postid = 1;
				}
			}

			$insertsql = "INSERT INTO posts (hostid, postid, userid, date, title, description, fileid, commentid, replyid) 
				VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);";
			$insertstmt = mysqli_stmt_init($conn);
			if (!mysqli_stmt_prepare($insertstmt, $insertsql)) {
				echo "Error";
			}
			else {
				mysqli_stmt_bind_param($insertstmt, "ssssssiii", $hostid, $postid, $userid, $date, $title, $body, $fileid, $commentid, $replyid);
				mysqli_stmt_execute($insertstmt);
			}

			header('Location: home.php?user='.$username.'');
	      	exit();
			}
		else {
		}
	}

// postPicture (clean)
	function postPicture($conn) {
		if (isset($_POST['uploadSubmit'])) {

			$userid = mysqli_real_escape_string($conn, $_POST['userid']);
			$hostid = mysqli_real_escape_string($conn, $_POST['userid']);
			$username = mysqli_real_escape_string($conn, $_SESSION['userUserName']);

			$sql = "SELECT MAX(postid) AS maxid FROM posts WHERE hostid = ?;";
			$stmt = mysqli_stmt_init($conn);
			if(!mysqli_stmt_prepare($stmt, $sql)) {
				echo "Error";
			}
			else {
				mysqli_stmt_bind_param($stmt, "s", $hostid);
				mysqli_stmt_execute($stmt);
				$result = mysqli_stmt_get_result($stmt);
				$resultCheck = mysqli_num_rows($result);
				if ($resultCheck > 0) {
					while ($row = mysqli_fetch_assoc($result)) {
						$postid = $row['maxid'] + 1;
					}
				}
				else {
					$postid = 1;
				}
			}

			
			$date = mysqli_real_escape_string($conn, $_POST['date']);
			$title = mysqli_real_escape_string($conn, $_POST['title']);
			$description = mysqli_real_escape_string($conn, $_POST['description']);
			$commentid = 0;
			$replyid = 0;

			$file = $_FILES['file'];
			$fileName = mysqli_real_escape_string($conn, $file['name']);
			$ext = pathinfo($fileName, PATHINFO_EXTENSION);
			$fileTmpName = $file['tmp_name'];
			$fileSize = $file['size'];
			$fileError = $file['error'];

			$allowed = array('jpg', 'jpeg', 'png', 'pdf', 'gif', 'mp4', 'mov', 'avi', 'mpeg4');
			$pictureExt = array('jpg', 'jpeg', 'png', 'gif');

			if (in_array($ext, $allowed)) {
				if ($fileError === 0) {
					if ($fileSize < 50000000) { 

						if (in_array($ext, $pictureExt)) {
								$fileNameNew = "".$postid.".".$ext;
								$fileDestination = 'posts/'.$hostid.'/'.$fileNameNew;
								move_uploaded_file($fileTmpName, $fileDestination);
								$fileid = 2;
						}
						else {
							header("Location: index.php?error4");
							exit();
						}
					} 
					else {
						header("Location: index.php?error1");
						exit();
					}
				}
				else {
					header("Location: index.php?error2");
					exit();
				}
			}
			else {
				header("Location: index.php?error3");
				exit();
			}
			$insertsql = "INSERT INTO posts (hostid, postid, userid, date, title, description, fileid, commentid, replyid)
				VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);";
			$insertstmt = mysqli_stmt_init($conn);
			if (!mysqli_stmt_prepare($insertstmt, $insertsql)) {
				echo "Error";
			}
			else {
				mysqli_stmt_bind_param($insertstmt, "ssssssiii", $hostid, $postid, $userid, $date, $title, $description, $fileid, $commentid, $replyid);
				mysqli_stmt_execute($insertstmt);
			}

			header('Location: home.php?user='.$username.'');
	      	exit();
		}
		else {
		}
	}

// getHomeUploads (clean)
	function getHomeUploads($conn) {
		$userName = mysqli_real_escape_string($conn, $_GET["user"]);
		$sqluserid = "SELECT * FROM users WHERE userName = ?;";
		$useridstmt = mysqli_stmt_init($conn);
		if (!mysqli_stmt_prepare($useridstmt, $sqluserid)) {
			echo "Error";
		}
		else {
			mysqli_stmt_bind_param($useridstmt, "s", $userName);
			mysqli_stmt_execute($useridstmt);
			$useridresult = mysqli_stmt_get_result($useridstmt);
			while ($useridrow = mysqli_fetch_assoc($useridresult)) {
				if($useridrow['userid'] > 0) {
					$userid = $useridrow['userid'];
					$sqlusercontent = "SELECT * FROM posts WHERE hostid = ? AND userid = ? AND commentid = 0;";
					$stmtusercontent = mysqli_stmt_init($conn);
					if (!mysqli_stmt_prepare($stmtusercontent, $sqlusercontent)) {
						echo "Error";
					}
					else {
						mysqli_stmt_bind_param($stmtusercontent, "ss", $userid, $userid);
						mysqli_stmt_execute($stmtusercontent);
						$usercontent = mysqli_stmt_get_result($stmtusercontent);
						$postid = 1;
						echo "<div class='homepostcontainer'>";
						echo "<div class='homepostcontainerscroll'>";
						while ($row = mysqli_fetch_assoc($usercontent)) {
							// Text fileid
							if ($row['fileid'] == 1) {
								$title = mysqli_real_escape_string($conn, $row['title']);
								$date = mysqli_real_escape_string($conn, $row['date']);
								$description = mysqli_real_escape_string($conn, $row['description']);
								echo "<div class='postbox'>";
								echo "<div class='postscroll'>";
								echo $title."<br>";
								echo $date."<br>";
								echo $description."<br>";
								echo "</div>";
								echo "<div class='InfoBar'> <a href=view.php?user=".$userName."&post=".$postid.">•••</a> </div>";
								echo "</div>";
							}
							// Picture fileid
							else if ($row['fileid'] == 2) {
								$title = mysqli_real_escape_string($conn, $row['title']);
								$date = mysqli_real_escape_string($conn, $row['date']);
								$description = mysqli_real_escape_string($conn, $row['description']);
								$filename = "posts/".$userid."/".$postid."*";
								$fileinfo = glob($filename);
								$fileext = explode(".", $fileinfo[0]);
								$fileactualext = $fileext[1];
								echo "<div class='postbox'>";
								echo "<div class='postscroll'>";
								echo $title."<br>";
								echo $date."<br>";
								echo "<img src='posts/".$userid."/".$postid.".".$fileactualext."'><br>";
								echo $description."<br>";
								echo "</div>";
								echo "<div class='InfoBar'> <a href=view.php?user=".$userName."&post=".$postid.">•••</a> </div>";
								echo "</div>";
							}
							else {
								echo '';
							}
							$postid++;
						}
						echo "</div>";
						echo "</div>";	
					}	
				}
			}
		}
	}

// getPosts (clean)
	function getPost($conn) {
		$userName = mysqli_real_escape_string($conn, $_GET["user"]);
		$postid = mysqli_real_escape_string($conn, $_GET["post"]);
		$usersql = "SELECT * FROM users WHERE userName = ?;";
		$userstmt = mysqli_stmt_init($conn);
		if (!mysqli_stmt_prepare($userstmt, $usersql)) {
			echo "Error";
		}
		else {
			mysqli_stmt_bind_param($userstmt, "s", $userName);
			mysqli_stmt_execute($userstmt);
			$userresult = mysqli_stmt_get_result($userstmt);
			while ($userrow = mysqli_fetch_assoc($userresult)) {
				$hostid = $userrow['userid'];
				$postsql = "SELECT * FROM posts WHERE hostid = ? AND postid = ? AND commentid = 0;";
				$poststmt = mysqli_stmt_init($conn);
				if (!mysqli_stmt_prepare($poststmt, $postsql)) {
					echo "Error";
				}
				else {
					mysqli_stmt_bind_param($poststmt, "ii", $hostid, $postid);
					mysqli_stmt_execute($poststmt);
					$postresult = mysqli_stmt_get_result($poststmt);
					while ($postrow = mysqli_fetch_assoc($postresult)) {
						if (mysqli_num_rows($postresult)==0) {
							echo '<p>Error.  Post does not exist.</p>';
						}
						/* This is where you should check the file type of the post, this should not effect anything in the comment area on the right */
						else {
							$title = mysqli_real_escape_string($conn, $postrow['title']);
							$description = mysqli_real_escape_string($conn, $postrow['description']);
							$postrow = mysqli_fetch_assoc($postresult);
							$fileid = mysqli_real_escape_string($conn, $postrow['fileid']);
							if ($fileid = 1) {
								echo '<div class="PostBox">
									'.mysqli_real_escape_string($conn, $postrow['title']).'<br>
									'.mysqli_real_escape_string($conn, $postrow['date']).'<br>
									'.mysqli_real_escape_string($conn, $postrow['description']).'
								</div>';
							}
							else if ($fileid = 2) {
								$filename = "posts/".$hostid."/".$postid.".*";
								$ext = pathinfo($filename, PATHINFO_EXTENSION);
								//$fileinfo = glob($filename);
								//$fileext = explode(".", $fileinfo[0]);
								//$fileactualext = $fileext[1];
								echo '<div class="PostBox">
									<img class= "PostImg" src="posts/'.$hostid.'/'.$postid.'.'.$ext.'"> <br>
									'.mysqli_real_escape_string($conn, $postrow['title']).'<br>
									'.mysqli_real_escape_string($conn, $postrow['date']).'<br>
									'.mysqli_real_escape_string($conn, $postrow['description']).'
								</div>';
							}
							else {

							}
							$maxcidsql = "SELECT MAX(commentid) AS maxcid FROM posts WHERE hostid = ? AND postid = ?";
							$maxcidstmt = mysqli_stmt_init($conn);
							if (!mysqli_stmt_prepare($maxcidstmt, $maxcidsql)) {
								echo "Error";
							}
							else {
								mysqli_stmt_bind_param($maxcidstmt, "ii", $hostid, $postid);
								mysqli_stmt_execute($maxcidstmt);
								$maxcidresult = mysqli_stmt_get_result($maxcidstmt);
								$maxcidresultCheck = mysqli_num_rows($maxcidresult);
								if ($maxcidresultCheck > 0) {
									while ($maxcidrow = mysqli_fetch_assoc($maxcidresult)) {
										$maxcid = $maxcidrow['maxcid'] + 1;
									}
								}
								else {
									$maxcid = 1;
								}
								if (isset($_SESSION['userID'])) {
									echo '
										<div class="CommentingContainer">
											<form method="POST" action="'.setComments($conn).'">
												<input type="hidden" name="userid" value="'.$_SESSION["userID"].'">
												<input type="hidden" name="date" value="'.date("Y-m-d H:i:s").'">
												<input type="hidden" name="postid" value="'.$postid.'">
												<input type="hidden" name="hostid" value="'.$hostid.'">
												<input type="hidden" name="fileid" value="'.$fileid.'">
												<input type="hidden" name="maxcid" value="'.$maxcid.'">
												<div class="CommentSubmit"><button type="submit" name="CommentSubmit">Comment</button></div> 
												<textarea name="comment" placeholder="Write a comment here!"></textarea>
											</form>
										</div>
									';
								}
								else {
										// Create a box that notifies the user that they should log in, at the same position where the comment box is
								}
								/* This is where the system starts looking comments and replies */
								echo '<div class="PostCommentSet">';
								$commentsql = "SELECT * FROM posts WHERE hostid = ? AND postid = ? AND commentid > 0 AND replyid = 0";
								$commentstmt = mysqli_stmt_init($conn);
								if (!mysqli_stmt_prepare($commentstmt, $commentsql)) {
									echo "Error";
								}
								else {
									mysqli_stmt_bind_param($commentstmt, "ii", $hostid, $postid);
									mysqli_stmt_execute($commentstmt);
									$commentresult = mysqli_stmt_get_result($commentstmt);
									while ($commentrow = mysqli_fetch_assoc($commentresult)) { // this automatically increments the comment values
										if (mysqli_num_rows($commentresult)==0) {
											echo '';
										}
										else {
											$commenterid = $commentrow['userid'];
											$commentersql = "SELECT * FROM users WHERE userid = ?";
											$commenterstmt = mysqli_stmt_init($conn);
											if(!mysqli_stmt_prepare($commenterstmt, $commentersql)) {
												echo "Error";
											}
											else {
												mysqli_stmt_bind_param($commenterstmt, "i", $commenterid);
												mysqli_stmt_execute($commenterstmt);
												$commenterresult = mysqli_stmt_get_result($commenterstmt);
												while ($commenterrow = mysqli_fetch_assoc($commenterresult)) {
													echo 	'<div class="PostComments">';
													if ($commenterrow['profileimg'] == 1) {
														$filename = "profilepics/profile".$commenterid."*";
														// $ext = pathinfo($filename, PATHINFO_EXTENSION);
														$fileinfo = glob($filename);
														$fileext = explode(".", $fileinfo[0]);
														$fileactualext = $fileext[1];
														echo "<div class='CommentProfilePicture'><img src='profilepics/profile".$commenterid.".".$fileactualext."?".mt_rand()."'></div>";
													}
													else {
														echo "<div class='CommentProfilePicture'><img src='profilepics/noUser.png'></div>";
													}
													echo 	"<div class='CommentUserName'>".mysqli_real_escape_string($conn, $commenterrow['userName'])."</div>";		// Prevent the username and comment from containing harmful keys
													echo	"<div class='CommenterComment'>".mysqli_real_escape_string($conn, $commentrow['comment'])."</div> </div>";	// and replies!!
												}
												$currentcommentid = $commentrow['commentid'];
												$replysql = "SELECT * FROM posts WHERE hostid = ? AND postid = ? AND commentid = ? AND replyid > 0";
												$replystmt = mysqli_stmt_init($conn);
												if (!mysqli_stmt_prepare($replystmt, $replysql)) {
													echo "Error";
												}
												else {
													mysqli_stmt_bind_param($replystmt, "iii", $hostid, $postid, $currentcommentid);
													mysqli_stmt_execute($replystmt);
													$replyresult = mysqli_stmt_get_result($replystmt); 
													while ($replyrow = mysqli_fetch_assoc($replyresult)) {
														if (mysqli_num_rows($replyresult)==0) {
															echo '<br>';
														}
														else {
															$replierid = $replyrow['userid'];
															$repliersql = "SELECT * FROM users WHERE userid = ?";
															$replierstmt = mysqli_stmt_init($conn);
															if (!mysqli_stmt_prepare($replierstmt, $repliersql)) {
																echo "Error";
															}
															else {
																mysqli_stmt_bind_param($replierstmt, "i", $replierid);
																mysqli_stmt_execute($replierstmt);
																$replierresult = mysqli_stmt_get_result($replierstmt);
																while ($replierrow = mysqli_fetch_assoc($replierresult)) {
																	echo 	'<div class="PostReplies">';
																	if ($replierrow['profileimg'] == 1) {
																		$filename = "profilepics/profile".$replierid."*";
																		// $ext = pathinfo($filename, PATHINFO_EXTENSION);
																		$fileinfo = glob($filename);
																		$fileext = explode(".", $fileinfo[0]);
																		$fileactualext = $fileext[1];
																		echo "<div class='ReplyProfilePicture'><img src='profilepics/profile".$replierid.".".$fileactualext."?".mt_rand()."'></div>";
																	}
																	else {
																		echo "<div class='ReplyProfilePicture'><img src='profilepics/noUser.png'></div>";
																	}
																	echo '
																			<div class="ReplyUserName">'.mysqli_real_escape_string($conn, $replierrow['userName']).'</div>	
																			<div class="ReplierReply">'.mysqli_real_escape_string($conn, $replyrow['reply']).'</div>
																		</div>
																	';
																}
															}
														}
													}
												}
											}
										}
									}
								}
							echo '</div>';
							} 
						}
					}
				}
			}
		}	
	}

// getActivityPosts (clean)
	function getActivityPosts($conn) {
		$Postsql = "SELECT * FROM posts WHERE commentid = 0";
		$Postresult = mysqli_query($conn, $Postsql);
		while ($Postrow = mysqli_fetch_assoc($Postresult)) {
			$hostid = mysqli_real_escape_string($conn, $Postrow['hostid']);
			$title = mysqli_real_escape_string($conn, $Postrow['title']);
			$description = mysqli_real_escape_string($conn, $Postrow['description']);
			$date = mysqli_real_escape_string($conn, $Postrow['date']);
			echo "<div class='ActivityPostBox'>";
				echo "<div class='ActivityPostBoxUserName'>".$hostid."</div>";
				echo "<div class='ActivityPostBoxDate'>".$date."</div>";
				echo "<div class='ActivityPostBoxTitle'>".$title."</div>";
				echo "<div class='ActivityPostBoxDescription'>".$description."</div>";
			echo "</div>";
		}
	}

// setComments (clean)
	function setComments($conn) {
		if (isset($_POST['CommentSubmit'])) {
			$postid = mysqli_real_escape_string($conn, $_POST['postid']);
			$hostid = mysqli_real_escape_string($conn, $_POST['hostid']);
			$userid = mysqli_real_escape_string($conn, $_POST['userid']);
			$date = mysqli_real_escape_string($conn, $_POST['date']);
			$comment = mysqli_real_escape_String($conn, $_POST['comment']);
			$fileid = mysqli_real_escape_String($conn, $_POST['fileid']);
			$maxcid = mysqli_real_escape_string($conn, $_POST['maxcid']);
			$userNameSql = "SELECT userName FROM users WHERE userid = ?";
			$userNamestmt = mysqli_stmt_init($conn);
			if (!mysqli_stmt_prepare($userNamestmt, $userNameSql)) {
				echo "error";
			}
			else {
				mysqli_stmt_bind_param($userNamestmt, "s", $hostid);
				mysqli_stmt_execute($userNamestmt);
				$userNameResult = mysqli_stmt_get_result($userNamestmt);
				while ($userNameRow = mysqli_fetch_assoc($userNameResult)) {
					$userName = mysqli_real_escape_String($conn, $userNameRow['userName']);
					$sql = "INSERT INTO posts (hostid, postid, userid, date, comment, fileid, commentid) VALUES (?, ?, ?, ?, ?, ?, ?)";
					$stmt = mysqli_stmt_init($conn);
					if (!mysqli_stmt_prepare($stmt, $sql)) {
						echo "error";
					}
					else {
						mysqli_stmt_bind_param($stmt, "iiissii", $hostid, $postid, $userid, $date, $comment, $fileid, $maxcid);
						mysqli_stmt_execute($stmt);
						header("Location: view.php?user=".$userName."&post=".$postid."");
			      		exit();
					}
			    }
			}
		}
	}

// getComments (old/dirty)
	function getComments($conn) {
		$sql = "SELECT * FROM posts";
		$result = $conn->query($sql);
		while ($row = $result->fetch_assoc()) {
			$id = $row['userid'];
			$sql2 = "SELECT * FROM users WHERE userid='$id'";
			$result2 = $conn->query($sql2);
			if ($row2 = $result2->fetch_assoc()) {

	// This is the actual comment content
				echo "<div class='comment-box'><p>";
				echo $row2['userName']."<br>";
				echo $row['date']."<br>";
				echo nl2br($row['comment']);
				echo "</p>";

	// The code provides a delete and edit button if the user who wrote the comment is logged in
				if (isset($_SESSION['userID'])) {
					if ($_SESSION['userID'] == $row2['userid']) {
						echo "<form class='deletebutton' method='POST' action='".deleteComments($conn)."'>
								<input type='hidden' name='commentid' value='".$row['commentid']."'>
								<button type='submit' name='commentDelete'>Delete</button>
							</form>
							<form class='editbutton' method='POST' action='editcomment.php'>
								<input type='hidden' name='commentid' value='".$row['commentid']."'>
								<input type='hidden' name='userid' value='".$row['userid']."'>
								<input type='hidden' name='date' value='".$row['date']."'>
								<input type='hidden' name='comment' value='".$row['comment']."'>
								<button>Edit</button>
							</form>";
					} else {
						echo "<form class='editbutton' method='POST' action='".deleteComments($conn)."'>
								<input type='hidden' name='commentid' value='".$row['commentid']."'>
								<button type='submit' name='commentDelete'>Reply</button>
							</form>";
					}
				} else {
					echo "<p class='replyerror'>Login to Reply!</p>";
				}
					echo "</div>";
			}	
		}
	}

// editComments (old/dirty)
	function editComments($conn) {
		if (isset($_POST['commentSubmit'])) {
			$commentid = $_POST['commentid'];
			$uid = $_POST['userid'];
			$date = $_POST['date'];
			$comment = $_POST['comment'];

			$sql = "UPDATE posts SET comment='$comment' WHERE commentid='$commentid'";
			$result = $conn->query($sql);
			header("Location: index.php");
	      	exit();
		}
	}

// deleteComments (old/dirty)
	function deleteComments($conn) {
		if (isset($_POST['commentDelete'])) {
			$commentid = $_POST['commentid'];
			
			$sql = "DELETE FROM posts WHERE commentid='$commentid'";
			$result = $conn->query($sql);
			header("Location: index.php");
	      	exit();
		}
	}