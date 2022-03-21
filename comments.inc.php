<?php 


	/* THIS IS AN EXTRA FILE THAT WAS MOVED TO posts.inc.php  THIS FILE SHOULD NOT BE NEEDED


// This function makes comments out of the given variables and puts the comments in the database

function setComments($conn) {
	if (isset($_POST['commentSubmit'])) {
		$uid = $_POST['uid'];
		$date = $_POST['date'];
		$message = $_POST['message'];

		$sql = "INSERT INTO comments (uid, date, message) VALUES ('$uid', '$date', '$message')";
		$result = $conn->query($sql);
		header("Location: index.php");
      	exit();
	}
}

// This function gets the comments from the database and puts them in the index page

function getComments($conn) {
	$sql = "SELECT * FROM comments";
	$result = $conn->query($sql);
	while ($row = $result->fetch_assoc()) {
		$id = $row['uid'];
		$sql2 = "SELECT * FROM users WHERE userid='$id'";
		$result2 = $conn->query($sql2);
		if ($row2 = $result2->fetch_assoc()) {

// This is the actual comment content

			echo "<div class='comment-box'><p>";
			echo $row2['userName']."<br>";
			echo $row['date']."<br>";
			echo nl2br($row['message']);
			echo "</p>";

// The code provides a delete and edit button if the user who wrote the comment is logged in

			if (isset($_SESSION['userID'])) {
				if ($_SESSION['userID'] == $row2['userid']) {
					echo "<form class='deletebutton' method='POST' action='".deleteComments($conn)."'>
							<input type='hidden' name='cid' value='".$row['cid']."'>
							<button type='submit' name='commentDelete'>Delete</button>
						</form>
						<form class='editbutton' method='POST' action='editcomment.php'>
							<input type='hidden' name='cid' value='".$row['cid']."'>
							<input type='hidden' name='uid' value='".$row['uid']."'>
							<input type='hidden' name='date' value='".$row['date']."'>
							<input type='hidden' name='message' value='".$row['message']."'>
							<button>Edit</button>
						</form>";
				} else {
					echo "<form class='editbutton' method='POST' action='".deleteComments($conn)."'>
							<input type='hidden' name='cid' value='".$row['cid']."'>
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

// This function takes you to another page to edit existing comments and then replaces the previous comment

function editComments($conn) {
	if (isset($_POST['commentSubmit'])) {
		$cid = $_POST['cid'];
		$uid = $_POST['uid'];
		$date = $_POST['date'];
		$message = $_POST['message'];

		$sql = "UPDATE comments SET message='$message' WHERE cid='$cid'";
		$result = $conn->query($sql);
		header("Location: index.php");
      	exit();
	}
}

// This function deletes existing comments

function deleteComments($conn) {
	if (isset($_POST['commentDelete'])) {
		$cid = $_POST['cid'];
		
		$sql = "DELETE FROM comments WHERE cid='$cid'";
		$result = $conn->query($sql);
		header("Location: index.php");
      	exit();
	}
}