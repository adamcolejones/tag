<?php
	require "header.php";
?>
	<div class="editCommentBody">
		<?php
		$commentid = $_POST['commentid'];
		$userid = $_POST['userid'];
		$date = $_POST['date'];
		$comment = $_POST['comment'];
		echo "<form method='POST' action='".editComments($conn)."'>
			<input type='hidden' name='commentid' value='".$commentid."'>
			<input type='hidden' name='userid' value='".$userid."'>
			<input type='hidden' name='date' value='".$date."'>
			<textarea name='comment'>".$comment."</textarea><br>
			<button type='submit' name='commentSubmit'>Edit</button>
		</form>";
		?>
		
	</div>
<?php
	require "footer.php";
?>
