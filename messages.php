<?php require "header.php";?>

<div class=homeBody>
	<?php 
		if (!isset($_SESSION['userID'])) {
			echo "You must be logged in to view your mail!";
		}
		else {
			// complete the friend system first
			// send a new message
			// view recieved messages
			// organize all messages from a single user into a single cell
			// This page should be treated like a text messaging system.
			// users should have the option to decide if they recieve users from anyone or just friends
		}
	;?>
</div>

<?php require "footer.php";?> 		